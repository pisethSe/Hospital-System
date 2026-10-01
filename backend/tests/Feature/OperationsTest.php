<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Equipment;
use App\Models\Payroll;
use App\Support\HisPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationsTest extends TestCase
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

    public function test_admin_can_generate_payroll_with_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/payrolls', [
                'pay_doc_name' => 'Pheakdey',
                'pay_doc_number' => 'pkd',
                'pay_emp_salary' => '1200',
                'pay_descr' => 'Monthly',
            ]);

        $response->assertStatus(201)->assertJsonPath('message', 'Payroll Generated');

        $this->assertDatabaseHas('his_payrolls', [
            'pay_doc_name' => 'Pheakdey',
            'pay_emp_salary' => '1200',
        ]);

        $this->assertMatchesRegularExpression('/^[0-9A-Z]{5}$/', $response->json('data.pay_number'));
    }

    public function test_payroll_update_sets_status_by_payroll_number(): void
    {
        $payroll = Payroll::create([
            'pay_number' => 'K67PL',
            'pay_doc_name' => 'Pheakdey',
            'pay_status' => 'Pending',
        ]);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/payrolls/{$payroll->pay_id}", ['pay_status' => 'Paid'])
            ->assertOk()
            ->assertJsonPath('data.pay_status', 'Paid')
            ->assertJsonPath('data.pay_number', 'K67PL');
    }

    public function test_payroll_can_be_deleted_by_admin(): void
    {
        $payroll = Payroll::create(['pay_number' => 'K67PL', 'pay_doc_name' => 'Pheakdey']);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/payrolls/{$payroll->pay_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_payrolls', ['pay_id' => $payroll->pay_id]);
    }

    public function test_doctor_can_view_but_not_write_payrolls(): void
    {
        Payroll::create(['pay_number' => 'K67PL', 'pay_doc_name' => 'Pheakdey']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/payrolls')
            ->assertOk()
            ->assertJsonFragment(['pay_doc_name' => 'Pheakdey']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/payrolls', ['pay_doc_name' => 'X'])
            ->assertStatus(403);
    }

    public function test_accounts_are_admin_only_with_numeric_generated_number(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/accounts', [
                'acc_name' => 'Electricity bill',
                'acc_desc' => 'Monthly',
                'acc_type' => 'Payable',
                'acc_amount' => '300',
            ]);

        $response->assertStatus(201);
        $account = Account::find($response->json('data.acc_id'));

        // Legacy algorithm: account numbers are 5-digit numeric codes.
        $this->assertMatchesRegularExpression('/^[0-9]{5}$/', $account->acc_number);

        // Legacy: updates changed name, description, type and amount —
        // never the account number.
        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/accounts/{$account->acc_id}", ['acc_amount' => '310'])
            ->assertOk()
            ->assertJsonPath('data.acc_number', $account->acc_number)
            ->assertJsonPath('data.acc_amount', '310');

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/accounts/{$account->acc_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_accounts', ['acc_id' => $account->acc_id]);

        // Legacy: accounts were admin-only.
        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/accounts')
            ->assertStatus(403);
    }

    public function test_accounts_filter_by_type(): void
    {
        Account::create(['acc_name' => 'Owed', 'acc_type' => 'Payable', 'acc_number' => '11111']);
        Account::create(['acc_name' => 'Owed to us', 'acc_type' => 'Receivable', 'acc_number' => '22222']);

        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->getJson('/api/accounts?type=Payable');

        $names = collect($response->json('data'))->pluck('acc_name');
        $this->assertContains('Owed', $names);
        $this->assertNotContains('Owed to us', $names);
    }

    public function test_equipment_add_update_delete_with_numeric_code(): void
    {
        $response = $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->postJson('/api/equipments', [
                'eqp_name' => 'X-Ray',
                'eqp_dept' => 'Radiology',
                'eqp_qty' => '2',
            ]);

        $response->assertStatus(201);
        $equipment = Equipment::find($response->json('data.eqp_id'));

        // Legacy algorithm: equipment codes are 5-digit numeric barcodes.
        $this->assertMatchesRegularExpression('/^[0-9]{5}$/', $equipment->eqp_code);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->putJson("/api/equipments/{$equipment->eqp_id}", ['eqp_status' => 'Maintenance'])
            ->assertOk()
            ->assertJsonPath('data.eqp_status', 'Maintenance')
            ->assertJsonPath('data.eqp_code', $equipment->eqp_code);

        $this->freshAuth()->withToken($this->tokenFor($this->admin(), 'admin'))
            ->deleteJson("/api/equipments/{$equipment->eqp_id}")
            ->assertOk();

        $this->assertDatabaseMissing('his_equipments', ['eqp_id' => $equipment->eqp_id]);
    }

    public function test_doctor_can_view_but_not_write_equipment(): void
    {
        Equipment::create(['eqp_name' => 'X-Ray', 'eqp_code' => '12345']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->getJson('/api/equipments')
            ->assertOk()
            ->assertJsonFragment(['eqp_name' => 'X-Ray']);

        $this->freshAuth()->withToken($this->tokenFor($this->doctor(), 'doctor'))
            ->postJson('/api/equipments', ['eqp_name' => 'New'])
            ->assertStatus(403);
    }
}
