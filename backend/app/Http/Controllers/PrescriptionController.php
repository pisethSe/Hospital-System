<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PrescriptionController extends Controller
{
    /**
     * List prescriptions with their medicines.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Prescription::with('medicines');

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('pres_pat_name', 'like', "%{$search}%")
                    ->orWhere('pres_pat_number', 'like', "%{$search}%")
                    ->orWhere('pres_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderByDesc('pres_id')->get(),
        ]);
    }

    public function show(Prescription $prescription): JsonResponse
    {
        return response()->json([
            'data' => $prescription->load('medicines'),
        ]);
    }

    /**
     * Add a prescription. Legacy logic preserved: the prescription number
     * is generated with the original algorithm when not supplied, and the
     * medicines are stored in his_prescription_medicines tied to the
     * prescription (same behaviour as his_doc_add_single_pres.php).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pres_pat_name' => ['required', 'string', 'max:200'],
            'pres_pat_age' => ['nullable', 'string', 'max:200'],
            'pres_pat_number' => ['nullable', 'string', 'max:200'],
            'pres_pat_addr' => ['nullable', 'string', 'max:200'],
            'pres_pat_type' => ['nullable', 'string', 'max:200'],
            'pres_pat_ailment' => ['nullable', 'string', 'max:200'],
            'pres_ins' => ['nullable', 'string'],
            'pres_number' => ['nullable', 'string', 'max:200'],
            'medicines' => ['nullable', 'array'],
            'medicines.*.name' => ['required_with:medicines', 'string', 'max:255'],
            'medicines.*.qty' => ['required_with:medicines', 'string', 'max:100'],
            'medicines.*.time' => ['required_with:medicines', 'string', 'max:100'],
        ]);

        $data['pres_number'] = $data['pres_number'] ?? HisCode::prescriptionNumber();

        $prescription = Prescription::create(Arr::except($data, 'medicines'));

        foreach ($request->input('medicines', []) as $medicine) {
            PrescriptionMedicine::create([
                'pres_id' => $prescription->pres_id,
                'pres_number' => $prescription->pres_number,
                'medicine_name' => $medicine['name'],
                'medicine_qty' => $medicine['qty'],
                'medicine_time' => $medicine['time'],
            ]);
        }

        return response()->json([
            'message' => 'Prescription Added',
            'data' => $prescription->load('medicines'),
        ], 201);
    }
}
