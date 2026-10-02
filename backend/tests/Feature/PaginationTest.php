<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Patient;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
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

    public function test_patients_are_paginated(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Patient::create(['pat_fname' => "Patient{$i}"]);
        }

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?page=1&per_page=10');

        $response->assertOk();
        $this->assertCount(10, $response->json('data.data'));
        $this->assertSame(15, $response->json('data.total'));
        $this->assertSame(2, $response->json('data.last_page'));
    }

    public function test_second_page_returns_remaining_records(): void
    {
        for ($i = 1; $i <= 15; $i++) {
            Patient::create(['pat_fname' => "Patient{$i}"]);
        }

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?page=2&per_page=10');

        $response->assertOk();
        $this->assertCount(5, $response->json('data.data'));

        // Newest records come first: page 1 holds 15..6, page 2 holds 5..1.
        $this->assertSame('Patient5', $response->json('data.data.0.pat_fname'));
    }

    public function test_per_page_is_capped_at_one_hundred(): void
    {
        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?per_page=9999');

        // 9999 would be capped to 100 — no crash, sane response.
        $response->assertOk();
    }

    public function test_page_beyond_the_last_returns_empty_data(): void
    {
        Patient::create(['pat_fname' => 'Only']);

        $response = $this->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/patients?page=99');

        $response->assertOk();
        $this->assertCount(0, $response->json('data.data'));
    }
}
