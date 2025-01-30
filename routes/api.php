<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BlogController;
use App\Http\Controllers\WeatherController;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Route::get('/create-role', function (Request $request) {
    $role = Role::create(['name' => 'writer']);
    $permission = Permission::create(['name' => 'create blogs']);

    $role->givePermissionTo($permission);

    return $role;
});

Route::group(['prefix' => 'v1'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');


    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/blogs', [BlogController::class, 'bloglariGetir']);
    Route::post('/blogs', [BlogController::class, 'blogOlustur'])
        ->middleware('auth:sanctum');

    Route::delete('/blogs', [BlogController::class, 'blogSil'])
        ->middleware('auth:sanctum');

    Route::put('/blogs/{id}', [BlogController::class, 'blogGuncelle'])
        ->middleware(['auth:sanctum', 'throttle:blog']);

    Route::get('/weather', [WeatherController::class, 'getWeather']);
});


