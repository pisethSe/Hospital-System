<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\PwdReset;
use App\Support\HisCode;
use App\Support\HisPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    /**
     * List password reset requests (admin manages them).
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => PwdReset::query()->orderByDesc('id')->get(),
        ]);
    }

    /**
     * Request a password reset. Legacy logic preserved from
     * his_admin_pwd_reset.php: a 10-character temp password and a
     * 30-character token are generated with the original charset, the
     * token is stored as sha1(md5(token)) and the temp password is stored
     * as received, with status "Pending".
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $plainToken = HisCode::resetToken();
        $tempPassword = HisCode::resetPassword();

        PwdReset::create([
            'email' => $data['email'],
            'token' => sha1(md5($plainToken)), // legacy double-encrypted token
            'status' => 'Pending',
            'pwd' => $tempPassword,
        ]);

        return response()->json([
            'message' => 'Check your inbox for password reset instructions',
            'temp_password' => $tempPassword,
            'token' => $plainToken,
        ], 201);
    }

    /**
     * Approve a reset request. Legacy logic preserved from
     * his_admin_update_doc_password.php: the doctor's password is updated
     * by email (hashed with the legacy algorithm) and the reset request
     * status is updated by email.
     */
    public function approve(Request $request, PwdReset $reset): JsonResponse
    {
        $data = $request->validate([
            'pwd' => ['sometimes', 'nullable', 'string', 'min:3'],
            'status' => ['sometimes', 'string', 'max:50'],
        ]);

        $newPassword = $data['pwd'] ?? $reset->pwd;
        $status = $data['status'] ?? 'Approved';

        $doctors = Doctor::where('doc_email', $reset->email)->get();

        $doctors->each(function ($doctor) use ($newPassword) {
            $doctor->update(['doc_pwd' => HisPassword::hash($newPassword)]);
        });

        PwdReset::where('email', $reset->email)
            ->update(['status' => $status]);

        // Security: revoke existing tokens for the affected doctors so a
        // leaked token cannot outlive the password change.
        $doctors->each(fn ($doctor) => $doctor->tokens()->delete());

        return response()->json(['message' => 'Password reset approved']);
    }
}
