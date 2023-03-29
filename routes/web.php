<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('app-content/{slug}',[HomeController::class,'getAppContent'])->name('app.content');
Route::get('buyer/update/status',[HomeController::class,'updateTourHome']);
Route::get('app-faq',[HomeController::class,'getAppContentFaqWeb'])->name('app.faq.web');
Route::get('app-support',[HomeController::class,'getAppSupport'])->name('app.support.web');
Route::post('app-support-submit',[HomeController::class,'submitSupportEmail'])->name('app.submit.support');
Route::get('user/verify/{name}',[UserController::class,'verifyEmail'])->name('verifyEmail');
Route::match(['get','post'],'user/reset-password/{any}',[UserController::class,'resetPassword'])
    ->name('reset-password');

