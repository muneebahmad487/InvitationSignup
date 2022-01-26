<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InvitationController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth::routes(['register' => false, 'reset' => false,]);
// Route::middleware(['auth'])->group(function () {
// //After Login the routes are accept by the loginUsers...
//     Route::post('invite', [InvitationController::class, 'process_invites'])->name('process_invites');
// });
// // {token} is a required parameter that will be exposed to us in the controller method
// Route::get('accept/{token}', [InvitationController::class, 'accept'])->name('accept');
//API route for login user
Route::post('/login', [AuthController::class, 'login']);

Route::get('/registration/{token}', [InvitationController::class, 'registration_view']);
Route::POST('/registration', [AuthController::class, 'register']);
Route::POST('/verifyOtp', [AuthController::class, 'verifyOTP']);
//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });
    Route::post('sendInvite', [InvitationController::class, 'sendInvites']);
    Route::post('updateUserProfile', [AuthController::class, 'updateUserProfile']);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});


