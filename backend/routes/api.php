<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LaboratoryController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\VitalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Roles map to the legacy login tables: "admin" -> his_admin,
| "doctor" -> his_docs. All endpoints keep the legacy business logic.
|
*/

// Public authentication endpoints
Route::post('login/admin', [AuthController::class, 'loginAdmin'])->middleware('throttle:20,1');
Route::post('login/doctor', [AuthController::class, 'loginDoctor'])->middleware('throttle:20,1');

Route::middleware('auth:sanctum')->group(function () {
    // Session info
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

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

    /*
    |----------------------------------------------------------------------
    | Shared modules (admin + doctor) - write access
    |----------------------------------------------------------------------
    */
    Route::post('lab-tests', [LaboratoryController::class, 'store'])->middleware('role:admin,doctor');
    Route::put('lab-tests/{laboratory}/result', [LaboratoryController::class, 'storeResult'])->middleware('role:admin,doctor');

    Route::post('prescriptions', [PrescriptionController::class, 'store'])->middleware('role:admin,doctor');

    Route::post('vitals', [VitalController::class, 'store'])->middleware('role:admin,doctor');

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

        // Pharmacy
        Route::get('pharmaceuticals', [PharmacyController::class, 'pharmaceuticals']);
        Route::post('pharmaceuticals', [PharmacyController::class, 'storePharmaceutical']);
        Route::get('pharmaceutical-categories', [PharmacyController::class, 'categories']);
        Route::post('pharmaceutical-categories', [PharmacyController::class, 'storeCategory']);
        Route::get('vendors', [PharmacyController::class, 'vendors']);
        Route::post('vendors', [PharmacyController::class, 'storeVendor']);
    });
});
