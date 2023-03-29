<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\BuyerPropertyController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AppContentController;
use App\Http\Controllers\Api\AppFaqController;
use App\Http\Controllers\Api\lendersStateDisclosureController;
use App\Http\Controllers\Api\RatingController;
use App\Http\Controllers\Api\UserSubscriptionController;
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

Route::middleware(['api_authorization'])->group(function(){
    Route::post('data-truncate',[UserController::class,'dataTruncate']);
    Route::post('user/login',[UserController::class,'login']);
    Route::post('user/forgot-password',[UserController::class,'forgotPassword']);
    Route::post('user/change-password',[UserController::class,'changePassword']);
    Route::post('user/logout',[UserController::class,'userLogout']);
    Route::post('user/social-login',[UserController::class,'socialLogin']);
    Route::post('user/verify-code',[UserController::class,'verifyCode']);
    Route::post('user/resend-code',[UserController::class,'resendCode']);
    Route::get('get/customer/agent',[UserController::class,'getAgentProfile']);
    Route::resource('user',UserController::class)->except(['delete']);
 
    Route::middleware(['custom_auth:api'])->group(function(){
        Route::post('share/user-profile',[UserController::class,'shareUserProfile']);
        Route::post('store/disclosure',[lendersStateDisclosureController::class,'storeDisclosure']);
        Route::post('agent-agrement-store',[UserController::class,'agentAgrementStore']);
        Route::post('accept-agent-agrement',[UserController::class,'acceptAgentAgrement']);
        Route::get('get/disclosure',[lendersStateDisclosureController::class,'getDisclosure']);
        Route::get('delete/disclosure',[lendersStateDisclosureController::class,'deleteDisclosure']);
        Route::get('get/faqs',[AppFaqController::class,'getFaqs']);
        Route::get('get/appcontent',[AppContentController::class,'getContent']);
        Route::get('user-notification',[NotificationController::class,'index']);
        Route::get('count-notification',[NotificationController::class,'countNotification']);
        Route::put('notification/{any}',[NotificationController::class,'update']);
        Route::post('notification/send',[NotificationController::class,'sendNotification']);
        Route::post('notification/setting',[NotificationController::class,'saveNotificationSetting']);
        Route::get('notification/setting',[NotificationController::class,'getNotificationSetting']);
        Route::post('property/contract/status',[PropertyController::class,'updateContractStatus']);
        Route::get('get/property/contract/status',[PropertyController::class,'getPropertyContractStatus']);
        Route::post('property/loaninfo/update',[PropertyController::class,'updateLoanInfo']);
        Route::post('property/initiate/contract',[PropertyController::class,'updateInitiateContract']);
        Route::get('property/initiate/contract/list',[PropertyController::class,'getInitiateBuyerPropertyList']);
        Route::get('get/property/loaninfo/contract',[PropertyController::class,'getPropertyContractLoanInfo']);
        Route::get('update-property-status',[PropertyController::class,'updatePropertyChangeStatus']);
        Route::resource('property',PropertyController::class)->except(['delete']);
        Route::get('lead/invitation/send',[LeadController::class,'sendInvitation']);
		Route::resource('lead',LeadController::class)->except(['delete']);
		Route::get('buyer/recommended/homes',[BuyerController::class,'getRecommendedHome']);
        Route::get('buyer/tour/homes',[BuyerController::class,'getTourHome']);
        Route::resource('buyer',BuyerController::class)->except(['delete']);
        Route::resource('appointments',AppointmentController::class)->except(['delete']);
        Route::resource('rating',RatingController::class,['only' => ['index', 'show', 'store']]);
        Route::resource('user-subscriptions',UserSubscriptionController::class,['only' => ['index', 'show']]);
        Route::post('user-subscription',[UserSubscriptionController::class,'storeUserSubscription']);

    });
});
