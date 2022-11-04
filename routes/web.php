<?php

use App\Http\Controllers\CostosVentasController;
use App\Http\Controllers\GastosOperacionalesController;
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
});
Route::get('ventas/totales', [VentasNetasController::class, 'total_sales']);
Route::get('ventas/costos', [CostosVentasController::class, 'total_costs']);
Route::get('gastos/operacionales', [GastosOperacionalesController::class, 'operational_expenses']);

