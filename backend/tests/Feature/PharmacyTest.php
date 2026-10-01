<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Pharmaceutical;
use App\Models\PharmaceuticalCategory;
use App\Models\Vendor;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyTest extends TestCase
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

    public function test_doctor_can_add_medicine_with_numeric_barcode(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/pharmaceuticals', [
                'phar_name' => 'Aspirin',
                'phar_qty' => '100',
                'phar_cat' => 'Painkillers',
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Pharmaceutical Added');

        $this->assertDatabaseHas('his_pharmaceuticals', ['phar_name' => 'Aspirin']);

        // Legacy algorithm: barcodes are 5-digit numeric codes.
        $barcode = $response->json('data.phar_bcode');
        $this->assertMatchesRegularExpression('/^[0-9]{5}$/', $barcode);
    }

    public function test_update_keeps_barcode_immutable(): void
    {
        $medicine = Pharmaceutical::create([
            'phar_name' => 'Aspirin',
            'phar_bcode' => '12345',
            'phar_qty' => '100',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/pharmaceuticals/{$medicine->phar_id}", ['phar_qty' => '95'])
            ->assertOk()
            ->assertJsonPath('data.phar_bcode', '12345')
            ->assertJsonPath('data.phar_qty', '95');
    }

    public function test_doctor_can_delete_medicine(): void
    {
        $medicine = Pharmaceutical::create(['phar_name' => 'Aspirin', 'phar_bcode' => '12345']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->deleteJson("/api/pharmaceuticals/{$medicine->phar_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_pharmaceuticals', ['phar_id' => $medicine->phar_id]);
    }

    public function test_category_update_only_changes_vendor_and_description(): void
    {
        // Legacy: the category update set vendor and description by
        // category name — the name never changed.
        $category = PharmaceuticalCategory::create([
            'pharm_cat_name' => 'Painkillers',
            'pharm_cat_vendor' => 'MedSupply',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/pharmaceutical-categories/{$category->pharm_cat_id}", [
                'pharm_cat_vendor' => 'NewVendor',
                'pharm_cat_desc' => 'Pain relief',
            ])
            ->assertOk()
            ->assertJsonPath('data.pharm_cat_name', 'Painkillers')
            ->assertJsonPath('data.pharm_cat_vendor', 'NewVendor');

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->putJson("/api/pharmaceutical-categories/{$category->pharm_cat_id}", ['pharm_cat_desc' => 'X'])
            ->assertStatus(403);
    }

    public function test_category_can_be_deleted_by_admin(): void
    {
        $category = PharmaceuticalCategory::create(['pharm_cat_name' => 'Painkillers']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/pharmaceutical-categories/{$category->pharm_cat_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_pharmaceuticals_categories', ['pharm_cat_id' => $category->pharm_cat_id]);
    }

    public function test_vendor_add_update_and_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/vendors', ['v_name' => 'MedSupply Co']);

        $response->assertStatus(201);
        $vendor = Vendor::find($response->json('data.v_id'));

        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $vendor->v_number);

        // Legacy: name, address, email, phone and description were updated
        // by vendor number — the number never changed.
        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/vendors/{$vendor->v_id}", ['v_email' => 'sales@medsupply.com'])
            ->assertOk()
            ->assertJsonPath('data.v_email', 'sales@medsupply.com')
            ->assertJsonPath('data.v_number', $vendor->v_number);
    }
}
