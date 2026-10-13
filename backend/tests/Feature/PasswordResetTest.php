<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\PwdReset;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'ad_fname' => 'Admin', 'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);
    }

    public function test_reset_request_is_public_and_does_not_leak_secrets(): void
    {
        // The request endpoint is public, but the response must never
        // contain the temp password or token — anyone could otherwise
        // fetch a would-be password for any email.
        Doctor::create([
            'doc_fname' => 'Reset',
            'doc_lname' => 'Doc',
            'doc_email' => 'reset.doc@hospital.com',
            'doc_number' => 'rst1',
            'doc_pwd' => HisPassword::hash('original123'),
        ]);

        $response = $this->postJson('/api/password-resets', [
            'email' => 'reset.doc@hospital.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Your reset request has been submitted. The administrator will provide your new password.')
            ->assertJsonMissing(['temp_password'])
            ->assertJsonMissing(['token']);

        $reset = PwdReset::where('email', 'reset.doc@hospital.com')->first();
        $this->assertNotNull($reset);
        $this->assertSame('Pending', $reset->status);

        // Legacy lengths: 10-char temp password, 40-char sha1(md5(token)).
        $this->assertSame(10, strlen($reset->pwd));
        $this->assertSame(40, strlen($reset->token));
    }

    public function test_reset_request_for_unknown_email_creates_no_row(): void
    {
        // Anti-enumeration: the response is identical for unknown emails
        // but no reset row is created.
        $response = $this->postJson('/api/password-resets', [
            'email' => 'nobody@hospital.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonMissing(['temp_password']);

        $this->assertDatabaseMissing('his_pwdresets', ['email' => 'nobody@hospital.com']);
    }

    public function test_admin_can_see_reset_requests(): void
    {
        PwdReset::create([
            'email' => 'staff@hospital.com',
            'token' => sha1(md5('token123')),
            'status' => 'Pending',
            'pwd' => 'temppass12',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/password-resets')
            ->assertOk()
            ->assertJsonFragment(['email' => 'staff@hospital.com']);
    }

    public function test_doctor_cannot_manage_reset_requests(): void
    {
        $doctor = Doctor::create([
            'doc_fname' => 'Visal', 'doc_lname' => '',
            'doc_number' => 'visal',
            'doc_pwd' => HisPassword::hash('visal123'),
        ]);

        $this->freshAuth()->withToken($this->tokenFor($doctor, 'doctor'))
            ->getJson('/api/password-resets')
            ->assertStatus(403);
    }

    public function test_approval_updates_doctor_password_by_email(): void
    {
        // Legacy his_admin_update_doc_password.php: the doctor's password
        // was updated by email (hashed with the legacy algorithm) and the
        // reset status was updated by email.
        $doctor = Doctor::create([
            'doc_fname' => 'Reset',
            'doc_lname' => 'Doc',
            'doc_email' => 'reset.doc@hospital.com',
            'doc_number' => 'rst1',
            'doc_pwd' => HisPassword::hash('original123'),
        ]);

        $reset = PwdReset::create([
            'email' => 'reset.doc@hospital.com',
            'token' => sha1(md5('token123')),
            'status' => 'Pending',
            'pwd' => 'temppass12',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson("/api/password-resets/{$reset->id}/approve", [
                'pwd' => 'temppass12',
                'status' => 'Approved',
            ])
            ->assertOk();

        $doctor->refresh();
        $this->assertTrue(HisPassword::verify('temppass12', $doctor->getAuthPassword()));
        $this->assertFalse(HisPassword::verify('original123', $doctor->getAuthPassword()));
        $this->assertSame('Approved', $reset->fresh()->status);
    }

    public function test_approval_revokes_the_doctors_existing_tokens(): void
    {
        $doctor = Doctor::create([
            'doc_fname' => 'Reset',
            'doc_lname' => 'Doc',
            'doc_email' => 'reset.doc@hospital.com',
            'doc_number' => 'rst1',
            'doc_pwd' => HisPassword::hash('original123'),
        ]);

        $reset = PwdReset::create([
            'email' => 'reset.doc@hospital.com',
            'token' => sha1(md5('token123')),
            'status' => 'Pending',
            'pwd' => 'temppass12',
        ]);

        $doctorOldToken = $this->tokenFor($doctor, 'doctor');

        // The admin approves the reset.
        $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson("/api/password-resets/{$reset->id}/approve", ['status' => 'Approved'])
            ->assertOk();

        // The doctor's old token no longer works.
        $this->freshAuth()->withToken($doctorOldToken)
            ->getJson('/api/me')
            ->assertStatus(401);
    }
}
