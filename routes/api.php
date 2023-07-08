<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/users', 'App\Http\Controllers\UserController@index');


Route::middleware('auth:sanctum')->group(function () {
    // Rutas protegidas que requieren autenticación
    Route::post('/logout',      [AuthController::class, 'logout']);
    Route::get('/users',        'App\Http\Controllers\UserController@index');
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

    //Cuentas
    Route::post('/cuentas/addCuenta',        'App\Http\Controllers\CuentasController@addCuenta');
    Route::put('/cuentas/updateCuenta',        'App\Http\Controllers\CuentasController@updateCuenta');
    Route::delete('/cuentas/deleteCuenta',        'App\Http\Controllers\CuentasController@deleteCuenta');

    //Personas
    Route::post('/personas/addPersona',        'App\Http\Controllers\PersonasController@addPersona');
    Route::put('/personas/updatePersona',        'App\Http\Controllers\PersonasController@updatePersona');
    Route::delete('/personas/deletePersona',        'App\Http\Controllers\PersonasController@deletePersona');

    //Bancos
    Route::post('/bancos/addBanco',        'App\Http\Controllers\BancosController@addBanco');
    Route::put('/bancos/updateBanco',        'App\Http\Controllers\BancosController@updateBanco');
    Route::delete('/bancos/deleteBanco',        'App\Http\Controllers\BancosController@deleteBanco');


});

Route::post('/register', [AuthController::class, 'register']);
Route::get('/countries',        'App\Http\Controllers\CountriesController@index');
/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});  */





