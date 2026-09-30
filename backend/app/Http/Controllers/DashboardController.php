<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Laboratory;
use App\Models\Patient;
use App\Models\Pharmaceutical;
use App\Models\Prescription;
use App\Models\Doctor;
use App\Models\Surgery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard statistics, mirroring the legacy admin/doctor dashboards.
     */
    public function index(Request $request): JsonResponse
    {
        $isAdmin = $request->user() instanceof Admin;

        $stats = [
            'patients' => Patient::count(),
            'active_patients' => Patient::active()->count(),
            'doctors' => Doctor::count(),
            'lab_tests' => Laboratory::count(),
            'pending_lab_tests' => Laboratory::where(function ($q) {
                $q->whereNull('lab_pat_results')->orWhere('lab_pat_results', '');
            })->count(),
            'surgeries' => Surgery::count(),
            'prescriptions' => Prescription::count(),
            'medicines' => Pharmaceutical::count(),
        ];

        $recentPatients = Patient::orderByDesc('pat_id')
            ->limit(5)
            ->get(['pat_id', 'pat_fname', 'pat_lname', 'pat_number', 'pat_ailment', 'pat_type', 'pat_date_joined']);

        $recentLabTests = Laboratory::orderByDesc('lab_id')
            ->limit(5)
            ->get(['lab_id', 'lab_pat_name', 'lab_pat_number', 'lab_number', 'lab_pat_tests', 'lab_pat_results', 'lab_date_rec']);

        $pendingSurgeries = collect();

        if ($isAdmin) {
            $pendingSurgeries = Surgery::orderByDesc('s_id')
                ->limit(5)
                ->get(['s_id', 's_number', 's_doc', 's_pat_name', 's_pat_number', 's_pat_status', 's_pat_date']);
        }

        return response()->json([
            'role' => $isAdmin ? 'admin' : 'doctor',
            'stats' => $stats,
            'recent_patients' => $recentPatients,
            'recent_lab_tests' => $recentLabTests,
            'pending_surgeries' => $pendingSurgeries,
        ]);
    }
}
