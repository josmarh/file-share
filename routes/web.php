<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataSourceController;

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
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/data-sources', [DataSourceController::class, 'index'])->name('data-sources');
    Route::post('/data-sources/new',[DataSourceController::class, 'store'])->name('data-sources.store');
    Route::get('/data-sources/edit',[DataSourceController::class, 'edit'])->name('data-sources.edit');
    Route::put('/data-sources/{id}',[DataSourceController::class, 'update'])->name('data-sources.update');
    Route::delete('/data-sources/{id}',[DataSourceController::class, 'destroy'])->name('data-sources.destroy');
});