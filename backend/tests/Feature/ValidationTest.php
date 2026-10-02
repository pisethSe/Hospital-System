<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    private function adminToken(): string
    {
        $admin = Admin::create([
            'ad_fname' => 'Admin', 'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);

        return $this->tokenFor($admin, 'admin');
    }

    public function test_login_requires_email_format(): void
    {
        $this->postJson('/api/login/admin', [
            'email' => 'not-an-email',
            'password' => 'x',
        ])->assertStatus(422);
    }

    public function test_login_requires_both_fields(): void
    {
        $this->postJson('/api/login/admin', ['email' => 'admin@hospital.com'])->assertStatus(422);
        $this->postJson('/api/login/admin', ['password' => 'x'])->assertStatus(422);
        $this->postJson('/api/login/doctor', ['doc_number' => 'pkd'])->assertStatus(422);
    }

    public function test_patient_requires_first_and_last_name(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/patients', ['pat_fname' => 'OnlyFirst'])
            ->assertStatus(422);

        $this->withToken($this->adminToken())
            ->postJson('/api/patients', ['pat_lname' => 'OnlyLast'])
            ->assertStatus(422);
    }

    public function test_doctor_requires_password_on_create(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/doctors', [
                'doc_fname' => 'No',
                'doc_lname' => 'Password',
                'doc_number' => 'np1',
            ])
            ->assertStatus(422);
    }

    public function test_password_minimum_length_is_enforced(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/doctors', [
                'doc_fname' => 'Short',
                'doc_lname' => 'Password',
                'doc_number' => 'sp1',
                'password' => 'ab', // min:3
            ])
            ->assertStatus(422);
    }

    public function test_prescription_requires_patient_name_and_medicine_fields(): void
    {
        $token = $this->adminToken();

        $this->withToken($token)
            ->postJson('/api/prescriptions', ['pres_pat_age' => '30'])
            ->assertStatus(422);

        // medicines require name, qty and time together
        $this->withToken($token)
            ->postJson('/api/prescriptions', [
                'pres_pat_name' => 'Rx',
                'medicines' => [['name' => 'Aspirin']], // missing qty + time
            ])
            ->assertStatus(422);
    }

    public function test_account_requires_a_name(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/accounts', ['acc_desc' => 'no name'])
            ->assertStatus(422);
    }

    public function test_equipment_requires_a_name(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/equipments', ['eqp_dept' => 'no name'])
            ->assertStatus(422);
    }

    public function test_lab_test_requires_patient_name_and_tests(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/lab-tests', ['lab_pat_name' => 'No tests'])
            ->assertStatus(422);
    }

    public function test_password_reset_requires_a_valid_email(): void
    {
        $this->postJson('/api/password-resets', ['email' => 'bad'])
            ->assertStatus(422);
        $this->postJson('/api/password-resets', [])->assertStatus(422);
    }

    public function test_vitals_requires_patient_number(): void
    {
        $this->withToken($this->adminToken())
            ->postJson('/api/vitals', ['vit_bodytemp' => '36.8 C'])
            ->assertStatus(422);
    }
}
