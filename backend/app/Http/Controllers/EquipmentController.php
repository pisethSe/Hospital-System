<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * List equipment. Doctors could view the inventory in the legacy
     * system; writing is admin-only.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Equipment::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('eqp_name', 'like', "%{$search}%")
                    ->orWhere('eqp_code', 'like', "%{$search}%")
                    ->orWhere('eqp_vendor', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderBy('eqp_id')->get(),
        ]);
    }

    /**
     * Add equipment. Legacy logic preserved: the equipment code is a
     * random numeric barcode generated with the original algorithm (same
     * as his_admin_add_equipment.php / his_admin_add_lab_equipment.php).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'eqp_name' => ['required', 'string', 'max:200'],
            'eqp_vendor' => ['nullable', 'string', 'max:200'],
            'eqp_desc' => ['nullable', 'string'],
            'eqp_dept' => ['nullable', 'string', 'max:200'],
            'eqp_status' => ['nullable', 'string', 'max:200'],
            'eqp_qty' => ['nullable', 'string', 'max:200'],
            'eqp_code' => ['nullable', 'string', 'max:200'],
        ]);

        if (!isset($data['eqp_code'])) {
            do {
                $data['eqp_code'] = HisCode::numericCode();
            } while (Equipment::where('eqp_code', $data['eqp_code'])->exists());
        }

        $equipment = Equipment::create($data);

        return response()->json([
            'message' => 'Equipment Added',
            'data' => $equipment,
        ], 201);
    }

    /**
     * Update equipment. Legacy logic preserved: the original update set
     * name, vendor, description, department, status and quantity by
     * equipment code — the code itself never changes.
     */
    public function update(Request $request, Equipment $equipment): JsonResponse
    {
        $data = $request->validate([
            'eqp_name' => ['sometimes', 'string', 'max:200'],
            'eqp_vendor' => ['sometimes', 'nullable', 'string', 'max:200'],
            'eqp_desc' => ['sometimes', 'nullable', 'string'],
            'eqp_dept' => ['sometimes', 'nullable', 'string', 'max:200'],
            'eqp_status' => ['sometimes', 'nullable', 'string', 'max:200'],
            'eqp_qty' => ['sometimes', 'nullable', 'string', 'max:200'],
        ]);

        $equipment->update($data);

        return response()->json([
            'message' => 'Equipment Updated',
            'data' => $equipment->fresh(),
        ]);
    }

    public function destroy(Equipment $equipment): JsonResponse
    {
        $equipment->delete();

        return response()->json(['message' => 'Equipment Removed']);
    }
}
