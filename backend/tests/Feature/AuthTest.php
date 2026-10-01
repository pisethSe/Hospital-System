<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_email_and_password(): void
    {
        Admin::create([
            'ad_fname' => 'Admin',
            'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => \App\Support\HisPassword::hash('admin123'),
        ]);

        $response = $this->postJson('/api/login/admin', [
            'email' => 'admin@hospital.com',
            'password' => 'admin123',
        ]);

        $response->assertOk()
            ->assertJsonPath('role', 'admin')
            ->assertJsonPath('user.email', 'admin@hospital.com')
            ->assertJsonStructure(['token']);
    }

    public function test_admin_login_rejects_wrong_password(): void
    {
        Admin::create([
            'ad_fname' => 'Admin',
            'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => \App\Support\HisPassword::hash('admin123'),
        ]);

        $this->postJson('/api/login/admin', [
            'email' => 'admin@hospital.com',
            'password' => 'wrong',
        ])->assertStatus(422);
    }

    public function test_doctor_can_login_with_doctor_id_not_email(): void
    {
        // Legacy logic: doctors sign in by doc_number, not email.
        Doctor::create([
            'doc_fname' => 'Pheakdey',
            'doc_lname' => '',
            'doc_number' => 'pkd',
            'doc_pwd' => \App\Support\HisPassword::hash('pkd123'),
        ]);

        $this->postJson('/api/login/doctor', [
            'doc_number' => 'pkd',
            'password' => 'pkd123',
        ])->assertOk()
            ->assertJsonPath('role', 'doctor');

        // Email must not work as a doctor identifier.
        $this->postJson('/api/login/doctor', [
            'doc_number' => 'admin@hospital.com',
            'password' => 'pkd123',
        ])->assertStatus(422);
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/me')->assertStatus(401);
    }

    public function test_me_returns_authenticated_profile(): void
    {
        $admin = Admin::create([
            'ad_fname' => 'Admin',
            'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => \App\Support\HisPassword::hash('admin123'),
        ]);

        $this->freshAuth()->withToken($this->tokenFor($admin, 'admin'))
            ->getJson('/api/me')
            ->assertOk()
            ->assertJsonPath('user.role', 'admin')
            ->assertJsonPath('user.name', 'Admin User')
            ->assertJsonMissing(['ad_pwd']);
    }

    public function test_logout_revokes_the_token(): void
    {
        $admin = Admin::create([
            'ad_fname' => 'Admin',
            'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => \App\Support\HisPassword::hash('admin123'),
        ]);

        $token = $admin->createToken('auth', ['role:admin'])->plainTextToken;

        $this->freshAuth()->withToken($token)->postJson('/api/logout')->assertOk();
        $this->freshAuth()->withToken($token)->getJson('/api/me')->assertStatus(401);
    }

    public function test_passwords_are_double_encrypted_with_legacy_algorithm(): void
    {
        // Business logic preserved from the original system:
        // sha1(md5($password)).
        $hash = \App\Support\HisPassword::hash('admin123');

        $this->assertSame(sha1(md5('admin123')), $hash);
        $this->assertNotSame('admin123', $hash);
        $this->assertNotSame(md5('admin123'), $hash);
        $this->assertTrue(\App\Support\HisPassword::verify('admin123', $hash));
        $this->assertFalse(\App\Support\HisPassword::verify('wrong', $hash));
    }
}
