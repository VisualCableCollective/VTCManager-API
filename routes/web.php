<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Company\CompanyController;
use App\Http\Controllers\Company\EmployeesController;
use App\Http\Controllers\Company\JobApplicationsController;
use Illuminate\Support\Facades\Broadcast;

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

Route::prefix('auth')->name('auth.')->group(function(){
    Route::prefix('vcc')->name('vcc.')->group(function(){
        Route::prefix('web-app')->name('web-app.')->group(function(){
            Route::get('redirect', [\App\Http\Controllers\Auth\WebAppAuthController::class, 'redirect']);
            Route::get('callback', [\App\Http\Controllers\Auth\WebAppAuthController::class, 'callback']);
        });
        Route::prefix('desktop-client')->name('desktop-client.')->group(function(){
            Route::get('redirect', [\App\Http\Controllers\Auth\DesktopClientAuthController::class, 'redirect']);
            Route::get('callback', [\App\Http\Controllers\Auth\DesktopClientAuthController::class, 'callback'])->name('callback');
        });
    });
});

Route::prefix('social')->name('social.')->group(function(){
    Route::redirect("discord", "https://discord.gg/XuY8Bah")->name('discord');
});

Route::get('/', function () {
    return redirect(config('services.vtcm-web-app.redirect', 'https://vtcmanager.eu/'), 308);
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);
