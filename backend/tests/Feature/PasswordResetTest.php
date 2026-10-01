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

    public function test_reset_request_is_public_and_uses_legacy_lengths(): void
    {
        $response = $this->postJson('/api/password-resets', [
            'email' => 'staff@hospital.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Check your inbox for password reset instructions');

        // Legacy: 10-char temp password, 30-char token.
        $this->assertSame(10, strlen($response->json('temp_password')));
        $this->assertSame(30, strlen($response->json('token')));

        // Legacy: the token is stored double-encrypted (sha1(md5(token))).
        $reset = PwdReset::where('email', 'staff@hospital.com')->first();
        $this->assertNotNull($reset);
        $this->assertSame('Pending', $reset->status);
        $this->assertSame(
            sha1(md5($response->json('token'))),
            $reset->token,
        );
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
}
