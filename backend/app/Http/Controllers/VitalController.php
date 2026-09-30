<?php

namespace App\Http\Controllers;

use App\Models\Vital;
use App\Support\HisCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VitalController extends Controller
{
    /**
     * List vitals records, optionally filtered by patient number.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Vital::query();

        if ($patNumber = trim((string) $request->query('pat_number'))) {
            $query->where('vit_pat_number', $patNumber);
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('vit_pat_number', 'like', "%{$search}%")
                    ->orWhere('vit_number', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'data' => $query->orderByDesc('vit_id')->get(),
        ]);
    }

    /**
     * Add a vitals record. Legacy logic preserved: the vitals number is
     * generated with the original algorithm when not supplied.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'vit_pat_number' => ['required', 'string', 'max:200'],
            'vit_bodytemp' => ['nullable', 'string', 'max:200'],
            'vit_heartpulse' => ['nullable', 'string', 'max:200'],
            'vit_resprate' => ['nullable', 'string', 'max:200'],
            'vit_bloodpress' => ['nullable', 'string', 'max:200'],
            'vit_number' => ['nullable', 'string', 'max:200'],
        ]);

        $data['vit_number'] = $data['vit_number'] ?? HisCode::vitalNumber();

        $vital = Vital::create($data);

        return response()->json([
            'message' => 'Vitals Added',
            'data' => $vital,
        ], 201);
    }
}
