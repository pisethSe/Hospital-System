<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Support\HisCode;
use App\Support\HisPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Doctor::query();

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($w) use ($search) {
                $w->where('doc_fname', 'like', "%{$search}%")
                    ->orWhere('doc_lname', 'like', "%{$search}%")
                    ->orWhere('doc_number', 'like', "%{$search}%")
                    ->orWhere('doc_dept', 'like', "%{$search}%");
            });
        }

        if ($dept = trim((string) $request->query('dept'))) {
            $query->where('doc_dept', $dept);
        }

        return response()->json([
            'data' => $query->orderBy('doc_id')->get(),
        ]);
    }

    public function show(Doctor $doctor): JsonResponse
    {
        return response()->json(['data' => $doctor]);
    }

    /**
     * Add a doctor / employee. Legacy logic preserved: the doctor ID
     * (doc_number) uses the original random algorithm and the password is
     * double-encrypted with sha1(md5()).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'doc_fname' => ['required', 'string', 'max:200'],
            'doc_lname' => ['required', 'string', 'max:200'],
            'doc_email' => ['nullable', 'email', 'max:200'],
            'doc_dept' => ['nullable', 'string', 'max:200'],
            'doc_dpic' => ['nullable', 'string', 'max:200'],
            'doc_number' => ['nullable', 'string', 'max:200'],
            'password' => ['required', 'string', 'min:3'],
        ]);

        $doctor = Doctor::create([
            'doc_fname' => $data['doc_fname'],
            'doc_lname' => $data['doc_lname'],
            'doc_email' => $data['doc_email'] ?? null,
            'doc_dept' => $data['doc_dept'] ?? null,
            'doc_dpic' => $data['doc_dpic'] ?? null,
            'doc_number' => $data['doc_number'] ?? (function () {
                do {
                    $number = HisCode::generate();
                } while (Doctor::where('doc_number', $number)->exists());
                return $number;
            })(),
            'doc_pwd' => HisPassword::hash($data['password']),
        ]);

        return response()->json([
            'message' => 'Employee Details Added',
            'data' => $doctor,
        ], 201);
    }

    public function update(Request $request, Doctor $doctor): JsonResponse
    {
        $data = $request->validate([
            'doc_fname' => ['sometimes', 'string', 'max:200'],
            'doc_lname' => ['sometimes', 'string', 'max:200'],
            'doc_email' => ['sometimes', 'nullable', 'email', 'max:200'],
            'doc_dept' => ['sometimes', 'nullable', 'string', 'max:200'],
            'doc_dpic' => ['sometimes', 'nullable', 'string', 'max:200'],
            'doc_number' => ['sometimes', 'string', 'max:200'],
            'password' => ['sometimes', 'nullable', 'string', 'min:3'],
        ]);

        if (array_key_exists('password', $data)) {
            $doctor->doc_pwd = $data['password'] !== null
                ? HisPassword::hash($data['password'])
                : $doctor->doc_pwd;
            unset($data['password']);
        }

        $doctor->update($data);

        return response()->json([
            'message' => 'Employee Details Updated',
            'data' => $doctor->fresh(),
        ]);
    }

    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();

        return response()->json(['message' => 'Employee Removed']);
    }
}
