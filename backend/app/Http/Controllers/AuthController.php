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

    /**
     * Update the signed-in user's own profile. Legacy logic preserved
     * from his_admin_account.php / his_doc_update-account.php: names,
     * email and avatar are updated by the account's own id.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'first_name' => ['sometimes', 'string', 'max:200'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:200'],
            'email' => ['sometimes', 'email', 'max:200'],
            'avatar' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        if ($user instanceof Admin) {
            $user->update([
                'ad_fname' => $data['first_name'] ?? $user->ad_fname,
                'ad_lname' => array_key_exists('last_name', $data) ? $data['last_name'] : $user->ad_lname,
                'ad_email' => $data['email'] ?? $user->ad_email,
                'ad_dpic' => array_key_exists('avatar', $data) ? $data['avatar'] : $user->ad_dpic,
            ]);
        } else {
            $user->update([
                'doc_fname' => $data['first_name'] ?? $user->doc_fname,
                'doc_lname' => array_key_exists('last_name', $data) ? $data['last_name'] : $user->doc_lname,
                'doc_email' => $data['email'] ?? $user->doc_email,
                'doc_dpic' => array_key_exists('avatar', $data) ? $data['avatar'] : $user->doc_dpic,
            ]);
        }

        return response()->json([
            'message' => 'Account Updated',
            'user' => $this->profile($user->fresh(), $user instanceof Admin ? 'admin' : 'doctor'),
        ]);
    }

    /**
     * Update the signed-in user's own password. Legacy logic preserved:
     * admins update by their ad_id, doctors by their doc_number, and the
     * password is double-encrypted with sha1(md5()) in both cases.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:3'],
        ]);

        $user = $request->user();

        if ($user instanceof Admin) {
            Admin::where('ad_id', $user->ad_id)
                ->update(['ad_pwd' => HisPassword::hash($data['password'])]);
        } else {
            Doctor::where('doc_number', $user->doc_number)
                ->update(['doc_pwd' => HisPassword::hash($data['password'])]);
        }

        return response()->json(['message' => 'Password Updated']);
    }

    /**
     * Upload the signed-in user's profile photo. Legacy logic preserved
     * from his_admin_account.php / his_doc_update-account.php: the photo
     * is stored under users/ with its original file name and the path is
     * saved in the dpic column.
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $data = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        $user = $request->user();

        // Legacy behaviour: keep the original file name.
        $file = $data['photo'];
        $path = $file->storeAs('users', $file->getClientOriginalName(), 'public');

        if ($user instanceof Admin) {
            Admin::where('ad_id', $user->ad_id)->update(['ad_dpic' => $path]);
        } else {
            Doctor::where('doc_number', $user->doc_number)->update(['doc_dpic' => $path]);
        }

        return response()->json([
            'message' => 'Photo Uploaded',
            'avatar' => $path,
            'url' => asset("storage/{$path}"),
        ]);
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
