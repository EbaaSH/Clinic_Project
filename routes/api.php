<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Middleware\Doctor;
use App\Http\Middleware\Patient;
use App\Http\Middleware\Secretary;
use App\Http\Middleware\TwoFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules;

use SadiqSalau\LaravelOtp\Facades\Otp;

use App\Models\User;
use App\Otp\UserRegistrationOtp;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->middleware(TwoFactor::class)->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::group([
    'middleware' => 'api'
], function ($router) {
    Route::post('/varify', [TwoFactorController::class, 'varify']);
    Route::get('/resendCode', [TwoFactorController::class, 'resendCode']);

});
Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::post('/addDates', [SecretaryController::class, 'addDates'])->middleware([TwoFactor::class, Secretary::class]);
    Route::delete('/deleteDate/{id}', [SecretaryController::class, 'deleteDate'])->middleware([TwoFactor::class, Secretary::class]);
    Route::put('/updateDate/{id}', [SecretaryController::class, 'updateDate'])->middleware([TwoFactor::class, Secretary::class]);
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('/getAllDates', [PatientController::class, 'getAllDates'])->middleware([TwoFactor::class, Patient::class]);
    Route::post('/reserveDate/{id}', [PatientController::class, 'reserveDate'])->middleware([TwoFactor::class, Patient::class]);
});

Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::put('/previewDate/{id}', [DoctorController::class, 'previewDate'])->middleware([TwoFactor::class, Doctor::class]);
    Route::get('/getDatesForDoctor', [DoctorController::class, 'getDatesForDoctor'])->middleware([TwoFactor::class, Doctor::class]);
});

