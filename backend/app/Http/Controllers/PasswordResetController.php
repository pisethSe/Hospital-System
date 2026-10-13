<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetApproved;
use App\Models\Doctor;
use App\Models\PwdReset;
use App\Support\HisCode;
use App\Support\HisPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

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

        // Only doctors with a matching email can be reset — and respond
        // identically either way so the endpoint cannot enumerate emails.
        // Production hardening: the response no longer returns the temp
        // password or token (anyone could have fetched a would-be
        // password for any email). The administrator sees the request
        // and communicates the new password through a secure channel.
        $doctorExists = Doctor::where('doc_email', $data['email'])->exists();

        if ($doctorExists) {
            $plainToken = HisCode::resetToken();
            $tempPassword = HisCode::resetPassword();

            PwdReset::create([
                'email' => $data['email'],
                'token' => sha1(md5($plainToken)), // legacy double-encrypted token
                'status' => 'Pending',
                'pwd' => $tempPassword,
            ]);
        }

        return response()->json([
            'message' => 'Your reset request has been submitted. The administrator will provide your new password.',
        ], 201);
    }

    /**
     * Approve a reset request. Legacy logic preserved from
     * his_admin_update_doc_password.php: the doctor's password is updated
     * by email (hashed with the legacy algorithm) and the reset request
     * status is updated by email.
     *
     * Production hardening: the new password must satisfy the password
     * policy, the notification email goes to the doctor's own email
     * address, and existing tokens for the affected doctors are revoked
     * so a leaked token cannot outlive the password change.
     */
    public function approve(Request $request, PwdReset $reset): JsonResponse
    {
        $data = $request->validate([
            'pwd' => [
                'sometimes', 'nullable', 'string',
                Password::min(8)->letters()->numbers(),
            ],
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

        // Notify the doctor at their own email address. A missing or
        // unconfigured mailer must never break the approval itself.
        $doctors->filter(fn ($doctor) => filled($doctor->doc_email))
            ->each(function ($doctor) use ($newPassword) {
                try {
                    Mail::to($doctor->doc_email)
                        ->send(new PasswordResetApproved($doctor->full_name, $newPassword));
                } catch (Throwable $e) {
                    Log::warning('Password reset notification failed', [
                        'email' => $doctor->doc_email,
                        'error' => $e->getMessage(),
                    ]);
                }
            });

        return response()->json(['message' => 'Password reset approved']);
    }
}
