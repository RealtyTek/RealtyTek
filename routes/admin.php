<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CmsUserController;
use App\Http\Controllers\Admin\CmsRoleController;
use App\Http\Controllers\Admin\ApplicationSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\AppContentController;
use App\Http\Controllers\Admin\UserSubscriptionController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::prefix('admin')->group(function () {

    Route::middleware(['guest:cms_user'])->group( function(){

        Route::match( ['get','post'],'login', [ LoginController::class,'login' ])->name('admin.login');
        Route::match( ['get','post'],'forgot-password', [ ForgotPasswordController::class,'forgotPassword' ])->name('admin.forgot-password');
        Route::match( ['get','post'],'reset-password/{any}', [ ResetPasswordController::class,'resetPassword' ])->name('admin.reset-password');

    });

    Route::middleware(['custom_auth:cms_user'])->group( function(){

        Route::match(['get','post'],'profile',[CmsUserController::class,'profile'])->name('admin.profile');
        Route::match(['get','post'],'change-password',[CmsUserController::class,'changePassword'])->name('admin.change-password');
        Route::get('logout',[CmsUserController::class,'logout'])->name('admin.logout');
        Route::get('dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('dashboard/small-widget',[DashboardController::class,'getSmallWidget'])->name('admin.dashboard.small-widget');
        Route::get('dashboard/line-chart',[DashboardController::class,'getLineChart'])->name('admin.dashboard.line-chart');

        Route::get('cms-roles-management/ajax-listing',[CmsRoleController::class,'ajaxListing'])->name('cms-roles-management.ajax-listing');
        Route::resource('cms-roles-management',CmsRoleController::class);

        Route::get('cms-users-management/ajax-listing',[CmsUserController::class,'ajaxListing'])->name('cms-users-management.ajax-listing');
        Route::resource('cms-users-management',CmsUserController::class);

        Route::match(['get','post'],'application-setting',[ApplicationSettingController::class,'index'])->name('admin.application-setting');

        Route::get('app-users/ajax-listing',[UserController::class,'ajaxListing'])->name('app-users.ajax-listing');
        Route::resource('app-users',UserController::class);

        Route::get('admin-property/ajax-listing',[PropertyController::class,'ajaxListing'])->name('admin-property.ajax-listing');
        Route::get('admin-property/lead/list/{id}',[PropertyController::class,'getLeadList'])->name('admin-property.lead.list');
        Route::resource('admin-property',PropertyController::class);

        Route::get('admin-customer/ajax-listing',[LeadController::class,'ajaxListing'])->name('admin-customer.ajax-listing');
        Route::resource('admin-customer',LeadController::class);

        Route::get('admin-faqs/ajax-listing',[FaqsController::class,'ajaxListing'])->name('faqs.ajax-listing');
        Route::resource('admin-faqs',FaqsController::class);

        Route::get('admin-app-content/ajax-listing',[AppContentController::class,'ajaxListing'])->name('admin-app-content.ajax-listing');
        Route::resource('admin-app-content',AppContentController::class);


        Route::get('admin-user-subscription/ajax-listing',[UserSubscriptionController::class,'ajaxListing'])->name('admin-user-subscription.ajax-listing');
        Route::resource('admin-user-subscription',UserSubscriptionController::class);
    });

});
