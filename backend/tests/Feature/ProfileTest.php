<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_own_profile(): void
    {
        // Legacy his_admin_account.php: names, email and avatar updated by
        // the admin's own id.
        $admin = Admin::create([
            'ad_fname' => 'Admin', 'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);

        $this->freshAuth()->withToken($this->tokenFor($admin, 'admin'))
            ->putJson('/api/profile', [
                'first_name' => 'Renamed',
                'last_name' => 'Admin',
            ])
            ->assertOk()
            ->assertJsonPath('user.name', 'Renamed Admin');

        $admin->refresh();
        $this->assertSame('Renamed', $admin->ad_fname);
    }

    public function test_admin_can_update_own_password(): void
    {
        $admin = Admin::create([
            'ad_fname' => 'Admin', 'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);

        $this->freshAuth()->withToken($this->tokenFor($admin, 'admin'))
            ->putJson('/api/profile/password', ['password' => 'newpass456'])
            ->assertOk();

        $admin->refresh();
        $this->assertTrue(HisPassword::verify('newpass456', $admin->getAuthPassword()));
        $this->assertFalse(HisPassword::verify('admin123', $admin->getAuthPassword()));
    }

    public function test_doctor_can_update_own_profile_and_password(): void
    {
        // Legacy his_doc_update-account.php: doctor password updated by
        // doc_number with the legacy hash; doctors still sign in by ID.
        $doctor = Doctor::create([
            'doc_fname' => 'Pheakdey', 'doc_lname' => '',
            'doc_number' => 'pkd',
            'doc_pwd' => HisPassword::hash('pkd123'),
        ]);

        $this->freshAuth()->withToken($this->tokenFor($doctor, 'doctor'))
            ->putJson('/api/profile', ['first_name' => 'Renamed'])
            ->assertOk()
            ->assertJsonPath('user.name', 'Renamed');

        $this->freshAuth()->withToken($this->tokenFor($doctor, 'doctor'))
            ->putJson('/api/profile/password', ['password' => 'docpass789'])
            ->assertOk();

        $doctor->refresh();
        $this->assertTrue(HisPassword::verify('docpass789', $doctor->getAuthPassword()));

        // Still signs in with the doctor ID + new password.
        $this->postJson('/api/login/doctor', [
            'doc_number' => 'pkd',
            'password' => 'docpass789',
        ])->assertOk();
    }

    public function test_password_change_revokes_existing_tokens(): void
    {
        // Security: a leaked token cannot outlive a password change.
        $admin = Admin::create([
            'ad_fname' => 'Admin', 'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);

        $oldToken = $this->tokenFor($admin, 'admin');

        $this->withToken($oldToken)
            ->putJson('/api/profile/password', ['password' => 'newpass456'])
            ->assertOk();

        // The old token no longer works.
        $this->freshAuth()->withToken($oldToken)
            ->getJson('/api/me')
            ->assertStatus(401);
    }

    public function test_profile_requires_authentication(): void
    {
        $this->putJson('/api/profile', ['first_name' => 'X'])->assertStatus(401);
        $this->putJson('/api/profile/password', ['password' => 'X'])->assertStatus(401);
        $this->postJson('/api/profile/avatar')->assertStatus(401);
    }
}
