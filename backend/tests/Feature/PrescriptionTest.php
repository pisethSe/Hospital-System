<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrescriptionTest extends TestCase
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

    public function test_doctor_can_write_prescription_with_medicines(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/prescriptions', [
                'pres_pat_name' => 'Rx Patient',
                'pres_pat_number' => '7EW0L',
                'pres_pat_type' => 'Outpatient',
                'pres_pat_age' => '40',
                'pres_pat_addr' => 'Phnom Penh',
                'pres_pat_ailment' => 'Flu',
                'pres_ins' => 'Drink water',
                'medicines' => [
                    ['name' => 'Paracetamol', 'qty' => '1 tablet', 'time' => 'every 6 hours'],
                    ['name' => 'Vitamin C', 'qty' => '1 pill', 'time' => 'daily'],
                ],
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Prescription Added');

        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.pres_number'));

        // Medicines are stored in his_prescription_medicines, tied to the
        // prescription (same behaviour as his_doc_add_single_pres.php).
        $this->assertDatabaseCount('his_prescription_medicines', 2);
        $this->assertDatabaseHas('his_prescription_medicines', [
            'medicine_name' => 'Paracetamol',
            'medicine_qty' => '1 tablet',
            'medicine_time' => 'every 6 hours',
        ]);
    }

    public function test_view_loads_medicines(): void
    {
        $prescription = Prescription::create([
            'pres_pat_name' => 'Rx Patient',
            'pres_number' => 'K67PL',
        ]);
        $prescription->medicines()->create([
            'medicine_name' => 'Aspirin',
            'medicine_qty' => '1 pill',
            'medicine_time' => 'daily',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson("/api/prescriptions/{$prescription->pres_id}")
            ->assertOk()
            ->assertJsonPath('data.pres_number', 'K67PL')
            ->assertJsonPath('data.medicines.0.medicine_name', 'Aspirin');
    }

    public function test_update_preserves_legacy_field_set(): void
    {
        // Legacy: name, type, addr, age, ailment and instructions were
        // updated by prescription number — the number never changed.
        $prescription = Prescription::create([
            'pres_pat_name' => 'Old Name',
            'pres_number' => 'K67PL',
            'pres_ins' => 'Old instructions',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/prescriptions/{$prescription->pres_id}", [
                'pres_pat_name' => 'New Name',
                'pres_ins' => 'New instructions',
            ])
            ->assertOk()
            ->assertJsonPath('data.pres_pat_name', 'New Name')
            ->assertJsonPath('data.pres_number', 'K67PL');
    }
}
