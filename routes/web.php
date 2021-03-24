<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\DataSourceController;
use App\Http\Controllers\EmailSubscribersController;
use App\Http\Controllers\FileUploadsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

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

// email verification route
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



Route::middleware(['auth', 'verified'])->group(function () {

    Route::middleware(['auth', 'active_status_checks'])->group(function () {

        Route::get('/dashboard',function(){ return view('home'); })->name('dashboard');
        Route::get('/render',[DashboardController::class, 'renderDashboard']);
        Route::get('/admin/dashboard',[DashboardController::class, 'index'])->name('admin-dashboard');
        Route::get('/user/dashboard',[DashboardController::class, 'userDashboard'])->name('user-dashboard');
    
        Route::get('/file-uploads', [FileUploadsController::class, 'index'])->name('file-uploads');
        Route::post('/file-uploads/post',[FileUploadsController::class, 'store'])->name('file-uploads.store');
        Route::post('/file-uploads/post/direct',[FileUploadsController::class, 'sendDirect'])->name('file-uploads.store.direct');
        Route::get('/file-uploads/directemail',[FileUploadsController::class, 'getUserMail'])->name('file-uploads.getmail');

    });
    
});

Route::middleware(['auth', 'role:superadministrator'])->group(function () {
    
    Route::middleware(['auth', 'active_status_checks'])->group(function () {

        Route::delete('/file-uploads/destroy{id}',[FileUploadsController::class, 'destroy'])->name('file-uploads.destroy');
        Route::get('/file-uploads/bulkdelete',[FileUploadsController::class, 'bulkDelete'])->name('file-uploads.delete');

        // Route::get('/data-sources', [DataSourceController::class, 'index'])->name('data-sources');
        // Route::post('/data-sources/post',[DataSourceController::class, 'store'])->name('data-sources.store');
        // Route::get('/data-sources/edit',[DataSourceController::class, 'edit'])->name('data-sources.edit');
        // Route::put('/data-sources/update/{id}',[DataSourceController::class, 'update'])->name('data-sources.update');
        // Route::delete('/data-sources/destroy/{id}',[DataSourceController::class, 'destroy'])->name('data-sources.destroy');
        // Route::get('/data-sources/bulkdelete',[DataSourceController::class, 'bulkDelete'])->name('data-sources.delete');

        Route::get('/mail-subscribers', [EmailSubscribersController::class, 'index'])->name('mail-subscribers');
        Route::post('/mail-subscribers/post',[EmailSubscribersController::class, 'store'])->name('mail-subscribers.store');
        Route::get('/mail-subscribers/edit',[EmailSubscribersController::class, 'edit'])->name('mail-subscribers.edit');
        Route::put('/dmail-subscribers/update/{id}',[EmailSubscribersController::class, 'update'])->name('mail-subscribers.update');
        Route::delete('/mail-subscribers/destroy/{id}',[EmailSubscribersController::class, 'destroy'])->name('mail-subscribers.destroy');
        Route::put('/mail-subscribers/status/{id}',[EmailSubscribersController::class, 'status'])->name('mail-subscribers.status');
        Route::get('/mail-subscribers/bulkdelete',[EmailSubscribersController::class, 'bulkDelete'])->name('mail-subscribers.delete');

        Route::put('/user/role/{id}',[UserController::class, 'userRole'])->name('user.role');
        Route::put('/user/status/{id}',[UserController::class, 'userStatus'])->name('user.status');
        Route::post('/user/post',[UserController::class, 'userRegister'])->name('users.store');
        Route::get('/user/bulkdelete',[UserController::class, 'userDelete'])->name('user.delete');
        Route::get('/users',[UserController::class, 'users'])->name('users');

    });
});
Route::get('/file-uploads/download/{id}',[FileUploadsController::class, 'download'])->name('file-uploads.download');