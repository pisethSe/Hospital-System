<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicalRecordTest extends TestCase
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

    public function test_admin_can_add_medical_record_with_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/medical-records', [
                'mdr_pat_name' => 'Record Patient',
                'mdr_pat_number' => '7EW0L',
                'mdr_pat_age' => '50',
                'mdr_pat_adr' => 'Phnom Penh',
                'mdr_pat_ailment' => 'Flu',
                'mdr_pat_prescr' => 'Rest',
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Medical Record Added');

        $this->assertDatabaseHas('his_medical_records', [
            'mdr_pat_name' => 'Record Patient',
            'mdr_pat_ailment' => 'Flu',
        ]);

        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.mdr_number'));
    }

    public function test_update_changes_only_the_legacy_fields(): void
    {
        // Legacy: address, age, prescription and ailment were updated by
        // record number — the name and number never changed.
        $record = MedicalRecord::create([
            'mdr_pat_name' => 'Record Patient',
            'mdr_number' => 'K67PL',
            'mdr_pat_age' => '50',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/medical-records/{$record->mdr_id}", [
                'mdr_pat_age' => '51',
                'mdr_pat_prescr' => 'Rest + fluids',
            ])
            ->assertOk()
            ->assertJsonPath('data.mdr_pat_age', '51')
            ->assertJsonPath('data.mdr_pat_prescr', 'Rest + fluids')
            ->assertJsonPath('data.mdr_number', 'K67PL');
    }

    public function test_medical_records_are_admin_only(): void
    {
        // Legacy: the medical records module lived in the admin panel only.
        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/medical-records')
            ->assertStatus(403);
    }
}
