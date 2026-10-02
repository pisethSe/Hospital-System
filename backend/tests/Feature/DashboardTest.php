<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Laboratory;
use App\Models\Patient;
use App\Models\Pharmaceutical;
use App\Models\Prescription;
use App\Models\Surgery;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    public function test_stats_count_everything_correctly(): void
    {
        Patient::create(['pat_fname' => 'One']);
        Patient::create(['pat_fname' => 'Two']);
        Doctor::create(['doc_fname' => 'Doc', 'doc_number' => 'pkd']);
        Laboratory::create(['lab_pat_name' => 'Lab', 'lab_number' => 'AAA11']);
        Surgery::create(['s_pat_name' => 'Surg', 's_number' => 'BBB22']);
        Prescription::create(['pres_pat_name' => 'Rx', 'pres_number' => 'CCC33']);
        Pharmaceutical::create(['phar_name' => 'Med', 'phar_bcode' => '12345']);

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/dashboard');

        $response->assertOk()->assertJsonPath('stats.patients', 2)
            ->assertJsonPath('stats.doctors', 1)
            ->assertJsonPath('stats.lab_tests', 1)
            ->assertJsonPath('stats.surgeries', 1)
            ->assertJsonPath('stats.prescriptions', 1)
            ->assertJsonPath('stats.medicines', 1);
    }

    public function test_active_patients_excludes_discharged(): void
    {
        Patient::create(['pat_fname' => 'Active', 'pat_lname' => 'One']);
        Patient::create(['pat_fname' => 'Discharged', 'pat_lname' => 'One', 'pat_walk_out_date' => now()]);

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/dashboard');

        $response->assertOk()
            ->assertJsonPath('stats.patients', 2)
            ->assertJsonPath('stats.active_patients', 1);
    }

    public function test_pending_lab_tests_only_counts_tests_without_results(): void
    {
        Laboratory::create(['lab_pat_name' => 'Waiting', 'lab_pat_tests' => 'Blood', 'lab_number' => 'AAA11']);
        Laboratory::create(['lab_pat_name' => 'Done', 'lab_pat_tests' => 'Blood', 'lab_pat_results' => 'OK', 'lab_number' => 'BBB22']);
        Laboratory::create(['lab_pat_name' => 'EmptyString', 'lab_pat_tests' => 'Blood', 'lab_pat_results' => '', 'lab_number' => 'CCC33']);

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/dashboard');

        // Waiting + EmptyString are pending; Done is complete.
        $response->assertOk()->assertJsonPath('stats.pending_lab_tests', 2);
    }

    public function test_recent_lists_are_limited_to_five(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Patient::create(['pat_fname' => "Patient{$i}"]);
        }
        for ($i = 1; $i <= 6; $i++) {
            Laboratory::create(['lab_pat_name' => "Lab{$i}", 'lab_number' => "L000{$i}"]);
        }

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/dashboard');

        $response->assertOk();
        $this->assertCount(5, $response->json('recent_patients'));
        $this->assertCount(5, $response->json('recent_lab_tests'));

        // The most recent patient comes first.
        $this->assertSame('Patient8', $response->json('recent_patients.0.pat_fname'));
    }

    public function test_doctor_dashboard_excludes_surgeries(): void
    {
        // Legacy: the doctor dashboard had no theatre section.
        $doctor = Doctor::create([
            'doc_fname' => 'Visal', 'doc_lname' => '',
            'doc_number' => 'visal',
            'doc_pwd' => HisPassword::hash('visal123'),
        ]);

        $response = $this->withToken($this->tokenFor($doctor, 'doctor'))
            ->getJson('/api/dashboard');

        $response->assertOk()
            ->assertJsonPath('role', 'doctor')
            ->assertJsonMissing(['surg']);
    }
}
