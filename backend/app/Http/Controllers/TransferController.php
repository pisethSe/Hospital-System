<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientTransfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * List patient transfers.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PatientTransfer::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('t_pat_name', 'like', "%{$search}%")
                    ->orWhere('t_pat_number', 'like', "%{$search}%")
                    ->orWhere('t_hospital', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderByDesc('t_id')->get(),
        ]);
    }

    /**
     * Transfer a patient to another hospital. Legacy logic preserved from
     * the doctor-side transfer page: the transfer row is inserted and the
     * patient's type is set to "Transferred" by record number.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            't_pat_number' => ['required', 'string', 'max:200'],
            't_pat_name' => ['required', 'string', 'max:200'],
            't_date' => ['nullable', 'string', 'max:200'],
            't_hospital' => ['nullable', 'string', 'max:200'],
            't_status' => ['nullable', 'string', 'max:200'],
        ]);

        $transfer = PatientTransfer::create($data);

        // Legacy doctor-side rule: mark the patient as transferred.
        Patient::where('pat_number', $data['t_pat_number'])
            ->update(['pat_type' => 'Transferred']);

        return response()->json([
            'message' => 'Patient Transferred',
            'data' => $transfer,
        ], 201);
    }
}
