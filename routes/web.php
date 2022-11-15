<?php

use App\Http\Controllers\CostosVentasController;
use App\Http\Controllers\GastosNoOperacionalesController;
use App\Http\Controllers\GastosOperacionalesController;
use App\Http\Controllers\ToneladasController;
use App\Http\Controllers\VentasNetasController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

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


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('ventas/totales', [VentasNetasController::class, 'total_sales']);
    Route::post('ventas/filter', [VentasNetasController::class, 'total_sales']);
    Route::get('ventas/costos', [CostosVentasController::class, 'total_costs']);
    Route::get('gastos/operacionales', [GastosOperacionalesController::class, 'operational_expenses']);
    Route::get('gastos/NoOper', [GastosNoOperacionalesController::class, 'nonOperatinals']);
    Route::get('ventas/Toneladas', [ToneladasController::class, 'tons']);
    Route::get('ventas/TotUnit', [VentasNetasController::class, 'unit_sales']);
    Route::post('ventas/filterUnitS', [VentasNetasController::class, 'unit_sales']);
    Route::get('ventas/costUnit', [CostosVentasController::class, 'unit_sales_costs']);
    Route::get('gastos/operUnit', [GastosOperacionalesController::class, 'unit_operational_expenses']);
    Route::get('gastos/NoOperUnit', [GastosNoOperacionalesController::class, 'unit_nonOperatinals']);
});

