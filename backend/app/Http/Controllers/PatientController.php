<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * List patients with optional search + status filter
     * (mirrors the legacy manage/discharge records pages).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Patient::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('pat_fname', 'like', "%{$search}%")
                    ->orWhere('pat_lname', 'like', "%{$search}%")
                    ->orWhere('pat_number', 'like', "%{$search}%")
                    ->orWhere('pat_ailment', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'discharged') {
                $query->whereNotNull('pat_walk_out_date');
            }
        }

        $perPage = max(1, min(100, (int) $request->query('per_page', 15)));

        return response()->json([
            'data' => $query->orderByDesc('pat_id')->paginate($perPage),
        ]);
    }

    public function show(Patient $patient): JsonResponse
    {
        return response()->json(['data' => $patient]);
    }

    /**
     * Register a patient. Legacy logic preserved: the patient number is
     * generated with the original algorithm when not supplied.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pat_fname' => ['required', 'string', 'max:200'],
            'pat_lname' => ['required', 'string', 'max:200'],
            'pat_dob' => ['nullable', 'string', 'max:200'],
            'pat_age' => ['nullable', 'string', 'max:200'],
            'pat_phone' => ['nullable', 'string', 'max:200'],
            'pat_type' => ['nullable', 'string', 'max:200'],
            'pat_addr' => ['nullable', 'string', 'max:200'],
            'pat_ailment' => ['nullable', 'string', 'max:200'],
            'pat_room_number' => ['nullable', 'string', 'max:50'],
            'pat_number' => ['nullable', 'string', 'max:200'],
        ]);

        $data['pat_number'] = $data['pat_number'] ?? HisCode::patientNumber();

        $patient = Patient::create($data);

        return response()->json([
            'message' => 'Patient Details Added',
            'data' => $patient,
        ], 201);
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'pat_fname' => ['sometimes', 'string', 'max:200'],
            'pat_lname' => ['sometimes', 'string', 'max:200'],
            'pat_dob' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_age' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_phone' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_type' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_addr' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_ailment' => ['sometimes', 'nullable', 'string', 'max:200'],
            'pat_room_number' => ['sometimes', 'nullable', 'string', 'max:50'],
        ]);

        $patient->update($data);

        return response()->json([
            'message' => 'Patient Details Updated',
            'data' => $patient->fresh(),
        ]);
    }

    /**
     * Discharge a patient. Legacy logic preserved: the discharge handler
     * set pat_discharge_status, and the discharge records listing relies
     * on pat_walk_out_date - both are kept.
     */
    public function discharge(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'pat_discharge_status' => ['nullable', 'string', 'max:200'],
        ]);

        $patient->update([
            'pat_discharge_status' => $data['pat_discharge_status'] ?? 'Discharged',
            'pat_walk_out_date' => $patient->pat_walk_out_date ?? now(),
        ]);

        return response()->json([
            'message' => 'Patient Discharged',
            'data' => $patient->fresh(),
        ]);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(['message' => 'Patient Removed']);
    }
}
