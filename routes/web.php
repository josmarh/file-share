<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataSourceController;
use App\Http\Controllers\EmailSubscribersController;
use App\Http\Controllers\FileUploadsController;
use App\Http\Controllers\DashboardController;

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

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');

    Route::get('/file-uploads', [FileUploadsController::class, 'index'])->name('file-uploads');
    Route::post('/file-uploads/post',[FileUploadsController::class, 'store'])->name('file-uploads.store');
    Route::post('/file-uploads/post/direct',[FileUploadsController::class, 'sendDirect'])->name('file-uploads.store.direct');
    Route::get('/file-uploads/directemail',[FileUploadsController::class, 'getUserMail'])->name('file-uploads.getmail');
});

Route::middleware(['auth', 'role:superadministrator'])->group(function () {
    
    Route::delete('/file-uploads/destroy{id}',[FileUploadsController::class, 'destroy'])->name('file-uploads.destroy');
    Route::get('/file-uploads/bulkdelete',[FileUploadsController::class, 'bulkDelete'])->name('file-uploads.delete');

    Route::get('/data-sources', [DataSourceController::class, 'index'])->name('data-sources');
    Route::post('/data-sources/post',[DataSourceController::class, 'store'])->name('data-sources.store');
    Route::get('/data-sources/edit',[DataSourceController::class, 'edit'])->name('data-sources.edit');
    Route::put('/data-sources/update/{id}',[DataSourceController::class, 'update'])->name('data-sources.update');
    Route::delete('/data-sources/destroy/{id}',[DataSourceController::class, 'destroy'])->name('data-sources.destroy');
    Route::get('/data-sources/bulkdelete',[DataSourceController::class, 'bulkDelete'])->name('file-uploads.delete');

    Route::get('/data-sources/create', [DataSourceController::class, 'create'])->name('data-sources.create');

    Route::get('/mail-subscribers', [EmailSubscribersController::class, 'index'])->name('mail-subscribers');
    Route::post('/mail-subscribers/post',[EmailSubscribersController::class, 'store'])->name('mail-subscribers.store');
    Route::get('/mail-subscribers/edit',[EmailSubscribersController::class, 'edit'])->name('mail-subscribers.edit');
    Route::put('/dmail-subscribers/update/{id}',[EmailSubscribersController::class, 'update'])->name('mail-subscribers.update');
    Route::delete('/mail-subscribers/destroy/{id}',[EmailSubscribersController::class, 'destroy'])->name('mail-subscribers.destroy');
    Route::put('/mail-subscribers/status/{id}',[EmailSubscribersController::class, 'status'])->name('mail-subscribers.status');
    Route::get('/mail-subscribers/bulkdelete',[EmailSubscribersController::class, 'bulkDelete'])->name('mail-subscribers.delete');

    Route::put('/user/role/{id}',[EmailSubscribersController::class, 'userRole'])->name('user.role');
    Route::get('/users',[EmailSubscribersController::class, 'users'])->name('users');
});
Route::get('/file-uploads/download/{id}',[FileUploadsController::class, 'download'])->name('file-uploads.download');