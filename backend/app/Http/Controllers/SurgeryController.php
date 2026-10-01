<?php

namespace App\Http\Controllers;

use App\Models\Surgery;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SurgeryController extends Controller
{
    /**
     * List surgery (theatre) records.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Surgery::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('s_pat_name', 'like', "%{$search}%")
                    ->orWhere('s_pat_number', 'like', "%{$search}%")
                    ->orWhere('s_number', 'like', "%{$search}%")
                    ->orWhere('s_doc', 'like', "%{$search}%");
            });
        }

        if ($status = trim((string) $request->query('status'))) {
            $query->where('s_pat_status', $status);
        }

        return response()->json([
            'data' => $query->orderByDesc('s_id')->get(),
        ]);
    }

    /**
     * Add a theatre patient. Legacy logic preserved: the surgery number is
     * generated with the original algorithm when not supplied, and the
     * status defaults to "Pending".
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            's_doc' => ['nullable', 'string', 'max:200'],
            's_pat_number' => ['required', 'string', 'max:200'],
            's_pat_name' => ['required', 'string', 'max:200'],
            's_pat_ailment' => ['nullable', 'string', 'max:200'],
            's_pat_date' => ['nullable', 'date'],
            's_pat_status' => ['nullable', 'string', 'max:200'],
            's_number' => ['nullable', 'string', 'max:200'],
        ]);

        if (!isset($data['s_number'])) {
            do {
                $data['s_number'] = HisCode::surgeryNumber();
            } while (Surgery::where('s_number', $data['s_number'])->exists());
        }
        $data['s_pat_status'] = $data['s_pat_status'] ?? 'Pending';

        $surgery = Surgery::create($data);

        return response()->json([
            'message' => 'Theatre Patient Added',
            'data' => $surgery,
        ], 201);
    }

    /**
     * Update a surgery record (e.g. mark Successful / change date).
     */
    public function update(Request $request, Surgery $surgery): JsonResponse
    {
        $data = $request->validate([
            's_doc' => ['sometimes', 'nullable', 'string', 'max:200'],
            's_pat_name' => ['sometimes', 'string', 'max:200'],
            's_pat_ailment' => ['sometimes', 'nullable', 'string', 'max:200'],
            's_pat_date' => ['sometimes', 'nullable', 'date'],
            's_pat_status' => ['sometimes', 'string', 'max:200'],
        ]);

        $surgery->update($data);

        return response()->json([
            'message' => 'Surgery Record Updated',
            'data' => $surgery->fresh(),
        ]);
    }

    /**
     * Remove a theatre record. Legacy logic preserved: the original
     * manage pages deleted surgery rows (by surgery number).
     */
    public function destroy(Surgery $surgery): JsonResponse
    {
        $surgery->delete();

        return response()->json(['message' => 'Surgery Record Removed']);
    }
}
