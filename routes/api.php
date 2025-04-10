<?php

use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\TwoFactorController;
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
    Route::post('/addDates', [SecretaryController::class, 'addDates']);

});


// Route::post('/otp/verify', function (Request $request) {

//     $request->validate([
//         'email' => ['required', 'string', 'email', 'max:255'],
//         'code' => ['required', 'string']
//     ]);

//     $otp = Otp::identifier($request->email)->attempt($request->code);

//     if ($otp['status'] != Otp::OTP_PROCESSED) {
//         abort(403, __($otp['status']));
//     }

//     return $otp['result'];
// });


// /** OTP Resend Route */
// Route::post('/otp/resend', function (Request $request) {

//     $request->validate([
//         'email' => ['required', 'string', 'email', 'max:255']
//     ]);

//     $otp = Otp::identifier($request->email)->update();

//     if ($otp['status'] != Otp::OTP_SENT) {
//         abort(403, __($otp['status']));
//     }
//     return __($otp['status']);
// });