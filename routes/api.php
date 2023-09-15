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

/**
 * Todas las rutas que se encuentren dentro del siguiente grupo son las que estan protegidas que requieren autenticación.
 */
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

    //Bancos Cuentas
    Route::post('/bancosCuentas/addBancoCuenta',        'App\Http\Controllers\BancosCuentasController@addBancoCuenta');
    Route::put('/bancosCuentas/updateBancoCuenta',        'App\Http\Controllers\BancosCuentasController@updateBancoCuenta');
    Route::delete('/bancosCuentas/deleteBancoCuenta',        'App\Http\Controllers\BancosCuentasController@deleteBancoCuenta');

    //Tipos
    Route::get('/tipos/getAllTipos',        'App\Http\Controllers\TiposController@getAllTipos');

    //Tipos Movimientos
    Route::post('/movimientosTipos/addMovimientoTipo',        'App\Http\Controllers\MovimientosTiposController@addMovimientoTipo');
    Route::put('/movimientosTipos/updateMovimientoTipo',        'App\Http\Controllers\MovimientosTiposController@updateMovimientoTipo');
    Route::delete('/movimientosTipos/deleteMovimientoTipo',        'App\Http\Controllers\MovimientosTiposController@deleteMovimientoTipo');

    //Movimientos
    Route::post('/movimientos/addMovimiento',        'App\Http\Controllers\MovimientosController@addMovimiento');
    Route::put('/movimientos/updateMovimiento',        'App\Http\Controllers\MovimientosController@updateMovimiento');
    Route::delete('/movimientos/deleteMovimiento',        'App\Http\Controllers\MovimientosController@deleteMovimiento');

    //Movimientos Accesos Directos
    Route::post('/movimientosAccesosDirectos/addMovimientoAccesoDirecto',        'App\Http\Controllers\MovimientosAccesosDirectosController@addMovimientoAccesoDirecto');
    Route::put('/movimientosAccesosDirectos/updateMovimientoAccesoDirecto',        'App\Http\Controllers\MovimientosAccesosDirectosController@updateMovimientoAccesoDirecto');
    Route::delete('/movimientosAccesosDirectos/deleteMovimientoAccesoDirecto',        'App\Http\Controllers\MovimientosAccesosDirectosController@deleteMovimientoAccesoDirecto');
});

Route::post('/register', [AuthController::class, 'register']);
Route::get('/countries',        'App\Http\Controllers\CountriesController@index');
/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});  */





