<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Support\HisPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Admin login. Legacy logic preserved: match ad_email +
     * sha1(md5(password)) against his_admin.
     */
    public function loginAdmin(Request $request): JsonResponse
    {
        return $this->login($request, Admin::class, 'ad_email', 'admin');
    }

    /**
     * Doctor login. Legacy logic preserved: doctors log in with their
     * doctor ID (doc_number, e.g. "pkd") + sha1(md5(password)) against
     * his_docs - exactly like the original his_doc/index.php.
     */
    public function loginDoctor(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'doc_number' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = Doctor::where('doc_number', $credentials['doc_number'])->first();

        if (! $user || ! HisPassword::verify($credentials['password'], $user->getAuthPassword())) {
            return response()->json(['message' => 'Access Denied. Please Check Your Credentials'], 422);
        }

        return $this->respondWithToken($user, 'doctor');
    }

    private function login(Request $request, string $model, string $emailColumn, string $role): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = $model::where($emailColumn, $credentials['email'])->first();

        if (! $user || ! HisPassword::verify($credentials['password'], $user->getAuthPassword())) {
            return response()->json(['message' => 'Invalid email or password.'], 422);
        }

        return $this->respondWithToken($user, $role);
    }

    private function respondWithToken($user, string $role): JsonResponse
    {
        $token = $user->createToken('auth', ["role:{$role}"])->plainTextToken;

        return response()->json([
            'token' => $token,
            'role' => $role,
            'user' => $this->profile($user, $role),
        ]);
    }

    /**
     * Currently authenticated admin or doctor.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user instanceof Admin ? 'admin' : 'doctor';

        return response()->json([
            'role' => $role,
            'user' => $this->profile($user, $role),
        ]);
    }

    /**
     * Revoke the current API token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Logged out.']);
    }

    private function profile($user, string $role): array
    {
        if ($role === 'admin') {
            return [
                'id' => $user->ad_id,
                'name' => $user->full_name,
                'email' => $user->ad_email,
                'avatar' => $user->ad_dpic,
                'role' => 'admin',
            ];
        }

        return [
            'id' => $user->doc_id,
            'name' => $user->full_name,
            'email' => $user->doc_email,
            'dept' => $user->doc_dept,
            'number' => $user->doc_number,
            'avatar' => $user->doc_dpic,
            'role' => 'doctor',
        ];
    }
}
