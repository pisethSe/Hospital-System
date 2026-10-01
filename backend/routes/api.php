<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\VitalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Roles map to the legacy login tables: "admin" -> his_admin,
| "doctor" -> his_docs. All endpoints keep the legacy business logic,
| with role access mirroring the original admin/doctor panels.
|
*/

// Public endpoints
Route::post('login/admin', [AuthController::class, 'loginAdmin'])->middleware('throttle:20,1');
Route::post('login/doctor', [AuthController::class, 'loginDoctor'])->middleware('throttle:20,1');

// Forgot password (legacy his_admin_pwd_reset.php is a public form)
Route::post('password-resets', [PasswordResetController::class, 'store'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    // Session info
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Own profile (legacy his_admin_account.php / his_doc_update-account.php)
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::put('profile/password', [AuthController::class, 'updatePassword']);
    Route::post('profile/avatar', [AuthController::class, 'uploadAvatar']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    /*
    |----------------------------------------------------------------------
    | Shared modules (admin + doctor) - read access
    |----------------------------------------------------------------------
    */
    Route::get('patients', [PatientController::class, 'index']);
    Route::get('patients/{patient}', [PatientController::class, 'show']);

    Route::get('lab-tests', [LaboratoryController::class, 'index']);
    Route::get('lab-tests/{laboratory}', [LaboratoryController::class, 'show']);

    Route::get('prescriptions', [PrescriptionController::class, 'index']);
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show']);

    Route::get('vitals', [VitalController::class, 'index']);

    // Read-only for doctors in the legacy panels
    Route::get('payrolls', [PayrollController::class, 'index']);
    Route::get('equipments', [EquipmentController::class, 'index']);
    Route::get('transfers', [TransferController::class, 'index']);

    /*
    |----------------------------------------------------------------------
    | Shared modules (admin + doctor) - write access
    |----------------------------------------------------------------------
    */
    Route::post('lab-tests', [LaboratoryController::class, 'store'])->middleware('role:admin,doctor');
    Route::put('lab-tests/{laboratory}/result', [LaboratoryController::class, 'storeResult'])->middleware('role:admin,doctor');

    Route::post('prescriptions', [PrescriptionController::class, 'store'])->middleware('role:admin,doctor');
    Route::put('prescriptions/{prescription}', [PrescriptionController::class, 'update'])->middleware('role:admin,doctor');

    Route::post('vitals', [VitalController::class, 'store'])->middleware('role:admin,doctor');

    // Doctors could manage pharmaceuticals in the legacy panels
    Route::get('pharmaceuticals', [PharmacyController::class, 'pharmaceuticals']);
    Route::post('pharmaceuticals', [PharmacyController::class, 'storePharmaceutical'])->middleware('role:admin,doctor');
    Route::put('pharmaceuticals/{pharmaceutical}', [PharmacyController::class, 'updatePharmaceutical'])->middleware('role:admin,doctor');
    Route::delete('pharmaceuticals/{pharmaceutical}', [PharmacyController::class, 'destroyPharmaceutical'])->middleware('role:admin,doctor');

    // Both roles could transfer patients (doctor flow sets pat_type)
    Route::post('transfers', [TransferController::class, 'store'])->middleware('role:admin,doctor');

    /*
    |----------------------------------------------------------------------
    | Admin-only management (mirrors the legacy admin panel)
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // Patients
        Route::post('patients', [PatientController::class, 'store']);
        Route::put('patients/{patient}', [PatientController::class, 'update']);
        Route::post('patients/{patient}/discharge', [PatientController::class, 'discharge']);
        Route::delete('patients/{patient}', [PatientController::class, 'destroy']);

        // Doctors / employees
        Route::get('doctors', [DoctorController::class, 'index']);
        Route::get('doctors/{doctor}', [DoctorController::class, 'show']);
        Route::post('doctors', [DoctorController::class, 'store']);
        Route::put('doctors/{doctor}', [DoctorController::class, 'update']);
        Route::delete('doctors/{doctor}', [DoctorController::class, 'destroy']);

        // Surgery / theatre
        Route::get('surgeries', [SurgeryController::class, 'index']);
        Route::post('surgeries', [SurgeryController::class, 'store']);
        Route::put('surgeries/{surgery}', [SurgeryController::class, 'update']);
        Route::delete('surgeries/{surgery}', [SurgeryController::class, 'destroy']);

        // Laboratory (full edit was admin-only in the legacy panel)
        Route::put('lab-tests/{laboratory}', [LaboratoryController::class, 'update']);

        // Pharmacy categories & vendors (admin-only in the legacy panel)
        Route::get('pharmaceutical-categories', [PharmacyController::class, 'categories']);
        Route::post('pharmaceutical-categories', [PharmacyController::class, 'storeCategory']);
        Route::put('pharmaceutical-categories/{category}', [PharmacyController::class, 'updateCategory']);
        Route::delete('pharmaceutical-categories/{category}', [PharmacyController::class, 'destroyCategory']);
        Route::get('vendors', [PharmacyController::class, 'vendors']);
        Route::post('vendors', [PharmacyController::class, 'storeVendor']);
        Route::put('vendors/{vendor}', [PharmacyController::class, 'updateVendor']);

        // Medical records (admin-only module in the legacy panel)
        Route::get('medical-records', [MedicalRecordController::class, 'index']);
        Route::get('medical-records/{medicalRecord}', [MedicalRecordController::class, 'show']);
        Route::post('medical-records', [MedicalRecordController::class, 'store']);
        Route::put('medical-records/{medicalRecord}', [MedicalRecordController::class, 'update']);

        // Payrolls
        Route::post('payrolls', [PayrollController::class, 'store']);
        Route::put('payrolls/{payroll}', [PayrollController::class, 'update']);
        Route::delete('payrolls/{payroll}', [PayrollController::class, 'destroy']);

        // Accounts (payable / receivable)
        Route::get('accounts', [AccountController::class, 'index']);
        Route::post('accounts', [AccountController::class, 'store']);
        Route::put('accounts/{account}', [AccountController::class, 'update']);
        Route::delete('accounts/{account}', [AccountController::class, 'destroy']);

        // Equipments
        Route::post('equipments', [EquipmentController::class, 'store']);
        Route::put('equipments/{equipment}', [EquipmentController::class, 'update']);
        Route::delete('equipments/{equipment}', [EquipmentController::class, 'destroy']);

        // Password reset management (legacy approval flow)
        Route::get('password-resets', [PasswordResetController::class, 'index']);
        Route::post('password-resets/{reset}/approve', [PasswordResetController::class, 'approve']);
    });
});
