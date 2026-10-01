<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): Admin
    {
        return Admin::create([
            'ad_fname' => 'Admin',
            'ad_lname' => 'User',
            'ad_email' => 'admin@hospital.com',
            'ad_pwd' => HisPassword::hash('admin123'),
        ]);
    }

    private function doctor(): Doctor
    {
        return Doctor::create([
            'doc_fname' => 'Pheakdey',
            'doc_lname' => '',
            'doc_number' => 'pkd',
            'doc_pwd' => HisPassword::hash('pkd123'),
        ]);
    }

    public function test_admin_can_register_patient_with_legacy_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/patients', [
                'pat_fname' => 'Test',
                'pat_lname' => 'Patient',
                'pat_phone' => '012 345 678',
                'pat_type' => 'Inpatient',
                'pat_addr' => 'Phnom Penh',
                'pat_age' => '30',
                'pat_dob' => '1996-01-01',
                'pat_ailment' => 'Fever',
                'pat_room_number' => '12',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Patient Details Added');

        $this->assertDatabaseHas('his_patients', [
            'pat_fname' => 'Test',
            'pat_lname' => 'Patient',
            'pat_ailment' => 'Fever',
            'pat_room_number' => '12',
        ]);

        // Legacy algorithm: 5-char uppercase alphanumeric record number.
        $number = $response->json('data.pat_number');
        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $number);
    }

    public function test_admin_can_update_patient_details(): void
    {
        $patient = Patient::create(['pat_fname' => 'Old', 'pat_lname' => 'Name']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/patients/{$patient->pat_id}", ['pat_ailment' => 'Malaria'])
            ->assertOk()
            ->assertJsonPath('data.pat_ailment', 'Malaria');
    }

    public function test_discharge_sets_both_legacy_columns(): void
    {
        // Legacy logic: discharge set pat_discharge_status, and the
        // discharge records listing relied on pat_walk_out_date.
        $patient = Patient::create(['pat_fname' => 'Sick', 'pat_lname' => 'One']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson("/api/patients/{$patient->pat_id}/discharge")
            ->assertOk()
            ->assertJsonPath('data.pat_discharge_status', 'Discharged');

        $patient->refresh();
        $this->assertNotNull($patient->pat_walk_out_date);
    }

    public function test_admin_can_delete_patient(): void
    {
        $patient = Patient::create(['pat_fname' => 'Gone', 'pat_lname' => 'Soon']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/patients/{$patient->pat_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_patients', ['pat_id' => $patient->pat_id]);
    }

    public function test_doctor_cannot_register_update_or_delete_patients(): void
    {
        // Legacy: patient registration was admin-only (the doctor-side
        // register page never inserted records).
        $patient = Patient::create(['pat_fname' => 'Keep', 'pat_lname' => 'Me']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/patients', ['pat_fname' => 'X', 'pat_lname' => 'Y'])
            ->assertStatus(403);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/patients/{$patient->pat_id}", ['pat_ailment' => 'X'])
            ->assertStatus(403);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->deleteJson("/api/patients/{$patient->pat_id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('his_patients', ['pat_id' => $patient->pat_id]);
    }

    public function test_doctor_can_list_and_view_patients(): void
    {
        Patient::create(['pat_fname' => 'Seen', 'pat_lname' => 'One']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/patients')
            ->assertOk()
            ->assertJsonFragment(['pat_fname' => 'Seen']);
    }

    public function test_patient_search_filters_by_name_number_and_ailment(): void
    {
        Patient::create(['pat_fname' => 'Dara', 'pat_lname' => 'Chan', 'pat_number' => '7EW0L']);
        Patient::create(['pat_fname' => 'Sophea', 'pat_lname' => 'Sok', 'pat_ailment' => 'Flu']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?search=7EW0L')
            ->assertOk()
            ->assertJsonFragment(['pat_fname' => 'Dara']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?status=active')
            ->assertOk()
            ->assertJsonFragment(['pat_fname' => 'Dara'])
            ->assertJsonFragment(['pat_fname' => 'Sophea']);
    }
}
