<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Surgery;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SurgeryTest extends TestCase
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

    public function test_admin_can_add_theatre_patient_with_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/surgeries', [
                's_doc' => 'Pheakdey',
                's_pat_number' => '7EW0L',
                's_pat_name' => 'Theatre Patient',
                's_pat_ailment' => 'Appendix',
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Theatre Patient Added');

        $this->assertDatabaseHas('his_surgery', [
            's_pat_name' => 'Theatre Patient',
            's_doc' => 'Pheakdey',
        ]);

        // Legacy: generated number and status starts as Pending.
        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.s_number'));
        $this->assertSame('Pending', $response->json('data.s_pat_status'));
    }

    public function test_admin_can_update_surgery_status(): void
    {
        $surgery = Surgery::create([
            's_number' => '8KQWD',
            's_pat_name' => 'Theatre Patient',
            's_pat_status' => 'Pending',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/surgeries/{$surgery->s_id}", ['s_pat_status' => 'Successful'])
            ->assertOk()
            ->assertJsonPath('data.s_pat_status', 'Successful');
    }

    public function test_admin_can_delete_surgery_record(): void
    {
        $surgery = Surgery::create([
            's_number' => '8KQWD',
            's_pat_name' => 'Theatre Patient',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/surgeries/{$surgery->s_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_surgery', ['s_id' => $surgery->s_id]);
    }
}
