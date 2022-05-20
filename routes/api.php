<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/status', [\App\Http\Controllers\API\ServiceStatusController::class, 'index']);

Route::post('/user/activate', [\App\Http\Controllers\UserController::class, 'activate'])->middleware("auth:sanctum");

Route::middleware(['auth:sanctum', /*'licenseKey'*/])->group(function (){

    // User System
    Route::get('/user', function (Request $request) {
        return \App\Models\User::with("license_key")->find($request->user()->id);
    });

    //Job System
    Route::prefix('jobs')->group(function(){
        Route::get('/', [App\Http\Controllers\API\JobController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\API\JobController::class, 'store']);
        Route::get('/{id}', [App\Http\Controllers\API\JobController::class, 'show']);
        Route::post('/{id}/delivered', [\App\Http\Controllers\API\JobController::class, 'delivered']);
        Route::post('/{id}/cancelled', [\App\Http\Controllers\API\JobController::class, 'cancelled']);
    });

    //All Clients
    Route::prefix('webapp')->group(function(){
        Route::get('check', [App\Http\Controllers\API\WebAppController::class, 'check']);
    });

    //Desktop Client
    Route::prefix('client')->group(function(){
        Route::post('update', [\App\Http\Controllers\API\Client\ClientController::class, 'update']);
    });

    //WebApp
    Route::prefix('webapp')->group(function(){
        Route::get('dashboard', [App\Http\Controllers\API\WebAppController::class, 'dashboard']);
    });

    //Company
    Route::prefix('company')->group(function(){
        Route::delete('/', [App\Http\Controllers\API\WebApp\CompanyController::class, 'delete'])->middleware('owner');
        Route::post('create', [App\Http\Controllers\API\WebApp\CompanyController::class, 'create']);
        Route::get('dashboard', [App\Http\Controllers\API\WebApp\CompanyController::class, 'dashboard']);
        Route::get('jobs', [App\Http\Controllers\API\WebApp\CompanyController::class, 'jobs']);
        Route::get('search', [App\Http\Controllers\API\WebApp\CompanyController::class, 'search']);
        Route::post('{id}/apply', [App\Http\Controllers\API\WebApp\CompanyController::class, 'apply']);
        Route::get('applications', [App\Http\Controllers\API\WebApp\CompanyController::class, 'applications'])->middleware('owner');
        Route::get('application/{id}', [App\Http\Controllers\API\WebApp\CompanyController::class, 'application'])->middleware('owner');
        Route::post('application/{id}/accept', [App\Http\Controllers\API\WebApp\CompanyController::class, 'application_accept'])->middleware('owner');
        Route::post('application/{id}/decline', [App\Http\Controllers\API\WebApp\CompanyController::class, 'application_decline'])->middleware('owner');
        Route::get('employees', [App\Http\Controllers\API\WebApp\CompanyController::class, 'employees']);
        Route::get('employee/{id}/kick', [App\Http\Controllers\API\WebApp\CompanyController::class, 'employee_kick'])->middleware('owner');
        Route::delete('leave', [App\Http\Controllers\API\WebApp\CompanyController::class, 'leave']);
    });
});


