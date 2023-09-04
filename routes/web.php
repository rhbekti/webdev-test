<?php

use App\Http\Controllers\BilanganController;
use App\Http\Controllers\PengirimanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BilanganController::class, 'index'])->name('home.index');
Route::post('/bilangan', [BilanganController::class, 'store'])->name('bilangan.store');
Route::get('/city', [PengirimanController::class, 'city'])->name('pengiriman.city');
Route::post('/ongkir', [PengirimanController::class, 'ongkir'])->name('pengiriman.ongkir');
