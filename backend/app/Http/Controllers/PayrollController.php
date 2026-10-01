<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    /**
     * List payrolls. Doctors could view payrolls in the legacy system;
     * writing is admin-only.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Payroll::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('pay_doc_name', 'like', "%{$search}%")
                    ->orWhere('pay_doc_number', 'like', "%{$search}%")
                    ->orWhere('pay_number', 'like', "%{$search}%");
            });
        }

        if ($status = trim((string) $request->query('status'))) {
            $query->where('pay_status', $status);
        }

        return response()->json([
            'data' => $query->orderByDesc('pay_id')->get(),
        ]);
    }

    /**
     * Generate a payroll. Legacy logic preserved: the payroll number is
     * generated with the original algorithm when not supplied (same shape
     * as his_admin_add_single_employee_payroll.php).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pay_doc_name' => ['required', 'string', 'max:200'],
            'pay_doc_number' => ['nullable', 'string', 'max:200'],
            'pay_doc_email' => ['nullable', 'email', 'max:200'],
            'pay_emp_salary' => ['nullable', 'string', 'max:200'],
            'pay_descr' => ['nullable', 'string'],
            'pay_status' => ['nullable', 'string', 'max:200'],
            'pay_number' => ['nullable', 'string', 'max:200'],
        ]);

        $data['pay_number'] = $data['pay_number'] ?? HisCode::payrollNumber();

        $payroll = Payroll::create($data);

        return response()->json([
            'message' => 'Payroll Generated',
            'data' => $payroll,
        ], 201);
    }

    /**
     * Update a payroll. Legacy logic preserved: the original update set
     * the doctor info, salary, description and status by payroll number.
     */
    public function update(Request $request, Payroll $payroll): JsonResponse
    {
        $data = $request->validate([
            'pay_doc_name' => ['sometimes', 'string', 'max:200'],
            'pay_doc_number' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pay_doc_email' => ['sometimes', 'nullable', 'email', 'max:200'],
            'pay_emp_salary' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pay_descr' => ['sometimes', 'nullable', 'string'],
            'pay_status' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $payroll->update($data);

        return response()->json([
            'message' => 'Payroll Updated',
            'data' => $payroll->fresh(),
        ]);
    }

    public function destroy(Payroll $payroll): JsonResponse
    {
        $payroll->delete();

        return response()->json(['message' => 'Payroll Removed']);
    }
}
