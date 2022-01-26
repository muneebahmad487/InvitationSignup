<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Auth::routes(['register' => false, 'reset' => false,]);
// Route::middleware(['auth'])->group(function () {
// //After Login the routes are accept by the loginUsers...
//     Route::post('invite', [InvitationController::class, 'process_invites'])->name('process_invites');
// });
// // {token} is a required parameter that will be exposed to us in the controller method
// Route::get('accept/{token}', [InvitationController::class, 'accept'])->name('accept');