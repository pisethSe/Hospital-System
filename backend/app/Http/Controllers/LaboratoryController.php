<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    /**
     * List lab tests. Legacy behaviour preserved: pending = no results yet.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Laboratory::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('lab_pat_name', 'like', "%{$search}%")
                    ->orWhere('lab_pat_number', 'like', "%{$search}%")
                    ->orWhere('lab_number', 'like', "%{$search}%");
            });
        }

        if ($request->query('pending') === 'true') {
            $query->where(function ($w) {
                $w->whereNull('lab_pat_results')->orWhere('lab_pat_results', '');
            });
        }

        return response()->json([
            'data' => $query->orderByDesc('lab_id')->get(),
        ]);
    }

    public function show(Laboratory $laboratory): JsonResponse
    {
        return response()->json(['data' => $laboratory]);
    }

    /**
     * Add a lab test. Legacy logic preserved: the lab number is generated
     * with the original algorithm when not supplied.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'lab_pat_name' => ['required', 'string', 'max:200'],
            'lab_pat_ailment' => ['nullable', 'string', 'max:200'],
            'lab_pat_number' => ['nullable', 'string', 'max:200'],
            'lab_pat_tests' => ['required', 'string'],
            'lab_number' => ['nullable', 'string', 'max:200'],
        ]);

        $data['lab_number'] = $data['lab_number'] ?? HisCode::labNumber();
        $data['lab_pat_number'] = $data['lab_pat_number'] ?? '';

        $laboratory = Laboratory::create($data);

        return response()->json([
            'message' => 'Lab Test Added',
            'data' => $laboratory,
        ], 201);
    }

    /**
     * Add / update lab results (mirrors the legacy
     * his_admin_update_single_lab_result.php handler).
     */
    public function storeResult(Request $request, Laboratory $laboratory): JsonResponse
    {
        $data = $request->validate([
            'lab_pat_results' => ['required', 'string'],
        ]);

        $laboratory->update($data);

        return response()->json([
            'message' => 'Lab Results Updated',
            'data' => $laboratory->fresh(),
        ]);
    }
}
