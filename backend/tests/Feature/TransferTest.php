<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientTransfer;
use App\Models\Vital;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferTest extends TestCase
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

    private function doctor(): Doctor
    {
        return Doctor::create([
            'doc_fname' => 'Pheakdey', 'doc_lname' => '',
            'doc_number' => 'pkd',
            'doc_pwd' => HisPassword::hash('pkd123'),
        ]);
    }

    public function test_transfer_records_and_marks_patient_as_transferred(): void
    {
        // Legacy doctor-side rule: the transfer row is inserted and the
        // patient's type is set to "Transferred" by record number.
        $patient = Patient::create([
            'pat_fname' => 'Transfer',
            'pat_lname' => 'Me',
            'pat_number' => '7EW0L',
            'pat_type' => 'Inpatient',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/transfers', [
                't_pat_number' => '7EW0L',
                't_pat_name' => 'Transfer Me',
                't_hospital' => 'Khmer Soviet Friendship Hospital',
                't_status' => 'Success',
            ])
            ->assertStatus(201)
            ->assertJsonPath('message', 'Patient Transferred');

        $this->assertDatabaseHas('his_patient_transfers', [
            't_pat_number' => '7EW0L',
            't_hospital' => 'Khmer Soviet Friendship Hospital',
        ]);

        $patient->refresh();
        $this->assertSame('Transferred', $patient->pat_type);
    }

    public function test_transfers_can_be_listed(): void
    {
        PatientTransfer::create([
            't_pat_number' => '7EW0L',
            't_pat_name' => 'Transfer Me',
            't_hospital' => 'Referral Hospital',
            't_status' => 'Success',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/transfers')
            ->assertOk()
            ->assertJsonFragment(['t_pat_number' => '7EW0L']);
    }

    public function test_vitals_can_be_recorded_for_a_patient(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/vitals', [
                'vit_pat_number' => '7EW0L',
                'vit_bodytemp' => '36.8 C',
                'vit_heartpulse' => '72 bpm',
                'vit_resprate' => '16 rpm',
                'vit_bloodpress' => '120/80',
            ]);

        $response->assertStatus(201);
        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.vit_number'));

        $this->assertDatabaseHas('his_vitals', [
            'vit_pat_number' => '7EW0L',
            'vit_bloodpress' => '120/80',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/vitals?pat_number=7EW0L')
            ->assertOk()
            ->assertJsonFragment(['vit_pat_number' => '7EW0L']);
    }
}
