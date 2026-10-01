<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * List medical records.
     */
    public function index(Request $request): JsonResponse
    {
        $query = MedicalRecord::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('mdr_pat_name', 'like', "%{$search}%")
                    ->orWhere('mdr_pat_number', 'like', "%{$search}%")
                    ->orWhere('mdr_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderByDesc('mdr_id')->get(),
        ]);
    }

    public function show(MedicalRecord $medicalRecord): JsonResponse
    {
        return response()->json(['data' => $medicalRecord]);
    }

    /**
     * Add a medical record. Legacy logic preserved: the record number is
     * generated with the original algorithm when not supplied.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mdr_pat_name' => ['required', 'string', 'max:200'],
            'mdr_pat_number' => ['nullable', 'string', 'max:200'],
            'mdr_pat_adr' => ['nullable', 'string', 'max:200'],
            'mdr_pat_age' => ['nullable', 'string', 'max:200'],
            'mdr_pat_ailment' => ['nullable', 'string', 'max:200'],
            'mdr_pat_prescr' => ['nullable', 'string'],
            'mdr_number' => ['nullable', 'string', 'max:200'],
        ]);

        if (!isset($data['mdr_number'])) {
            do {
                $data['mdr_number'] = HisCode::medicalRecordNumber();
            } while (MedicalRecord::where('mdr_number', $data['mdr_number'])->exists());
        }

        $record = MedicalRecord::create($data);

        return response()->json([
            'message' => 'Medical Record Added',
            'data' => $record,
        ], 201);
    }

    /**
     * Update a medical record. Legacy logic preserved: the original
     * his_admin_upate_single_medical_record.php updated the address, age,
     * prescription and ailment by record number.
     */
    public function update(Request $request, MedicalRecord $medicalRecord): JsonResponse
    {
        $data = $request->validate([
            'mdr_pat_adr' => ['sometimes', 'nullable', 'string', 'max:200'],
            'mdr_pat_age' => ['sometimes', 'nullable', 'string', 'max:200'],
            'mdr_pat_prescr' => ['sometimes', 'nullable', 'string'],
            'mdr_pat_ailment' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $medicalRecord->update($data);

        return response()->json([
            'message' => 'Medical Record Updated',
            'data' => $medicalRecord->fresh(),
        ]);
    }
}
