<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * List accounts. Legacy logic preserved: the accounting module split
     * accounts into payable and receivable by acc_type.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Account::query();

        if ($type = trim((string) $request->query('type'))) {
            $query->where('acc_type', $type);
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('acc_name', 'like', "%{$search}%")
                    ->orWhere('acc_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderBy('acc_id')->get(),
        ]);
    }

    /**
     * Add an account. Legacy logic preserved: the account number is a
     * random numeric code generated with the original algorithm (same as
     * his_admin_add_acc.payable.php / his_admin_add_acc_receivable.php).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'acc_name' => ['required', 'string', 'max:200'],
            'acc_desc' => ['nullable', 'string'],
            'acc_type' => ['nullable', 'string', 'max:200'],
            'acc_amount' => ['nullable', 'string', 'max:200'],
            'acc_number' => ['nullable', 'string', 'max:200'],
        ]);

        $data['acc_number'] = $data['acc_number'] ?? HisCode::numericCode();

        $account = Account::create($data);

        return response()->json([
            'message' => 'Account Added',
            'data' => $account,
        ], 201);
    }

    /**
     * Update an account. Legacy logic preserved: the original update set
     * name, description, type and amount by account number — the account
     * number itself never changes.
     */
    public function update(Request $request, Account $account): JsonResponse
    {
        $data = $request->validate([
            'acc_name' => ['sometimes', 'string', 'max:200'],
            'acc_desc' => ['sometimes', 'nullable', 'string'],
            'acc_type' => ['sometimes', 'nullable', 'string', 'max:200'],
            'acc_amount' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $account->update($data);

        return response()->json([
            'message' => 'Account Updated',
            'data' => $account->fresh(),
        ]);
    }

    public function destroy(Account $account): JsonResponse
    {
        $account->delete();

        return response()->json(['message' => 'Account Removed']);
    }
}
