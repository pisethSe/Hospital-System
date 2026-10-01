<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Laboratory;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabTest extends TestCase
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
            'doc_fname' => 'Visal', 'doc_lname' => '',
            'doc_number' => 'visal',
            'doc_pwd' => HisPassword::hash('visal123'),
        ]);
    }

    public function test_doctor_can_request_lab_test_with_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/lab-tests', [
                'lab_pat_name' => 'Lab Patient',
                'lab_pat_number' => '7EW0L',
                'lab_pat_tests' => 'Blood, Stool',
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Lab Test Added');

        $this->assertDatabaseHas('his_laboratory', [
            'lab_pat_name' => 'Lab Patient',
            'lab_pat_tests' => 'Blood, Stool',
        ]);

        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.lab_number'));
        $this->assertEmpty($response->json('data.lab_pat_results'));
    }

    public function test_doctor_can_record_results(): void
    {
        $lab = Laboratory::create([
            'lab_pat_name' => 'Lab Patient',
            'lab_pat_tests' => 'Blood',
            'lab_number' => '6P8HJ',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/lab-tests/{$lab->lab_id}/result", ['lab_pat_results' => 'All normal'])
            ->assertOk()
            ->assertJsonPath('data.lab_pat_results', 'All normal');
    }

    public function test_full_edit_is_admin_only(): void
    {
        // Legacy: the full lab test update was admin-only.
        $lab = Laboratory::create([
            'lab_pat_name' => 'Lab Patient',
            'lab_pat_tests' => 'Blood',
            'lab_number' => '6P8HJ',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/lab-tests/{$lab->lab_id}", ['lab_pat_name' => 'Renamed'])
            ->assertStatus(403);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/lab-tests/{$lab->lab_id}", [
                'lab_pat_name' => 'Renamed',
                'lab_pat_tests' => 'Full panel',
                'lab_pat_results' => 'Normal',
            ])
            ->assertOk()
            ->assertJsonPath('data.lab_pat_name', 'Renamed')
            ->assertJsonPath('data.lab_pat_results', 'Normal');
    }

    public function test_pending_filter_lists_only_tests_without_results(): void
    {
        Laboratory::create(['lab_pat_name' => 'Waiting', 'lab_pat_tests' => 'Blood', 'lab_number' => 'AAA11']);
        Laboratory::create(['lab_pat_name' => 'Done', 'lab_pat_tests' => 'Blood', 'lab_pat_results' => 'OK', 'lab_number' => 'BBB22']);

        $response = $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/lab-tests?pending=true');

        $response->assertOk();
        $names = collect($response->json('data'))->pluck('lab_pat_name');
        $this->assertContains('Waiting', $names);
        $this->assertNotContains('Done', $names);
    }
}
