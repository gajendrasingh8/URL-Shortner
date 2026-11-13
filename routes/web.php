<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\InvitationAcceptController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\ShortUrlRedirectController;
use App\Http\Controllers\TeamMemberController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/s/{code}', [ShortUrlRedirectController::class,'redirect'])->name('short-urls.redirect');

Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/invitation/{token}', [InvitationAcceptController::class, 'show'])->name('invitations.show');
    Route::post('/invitation/{token}', [InvitationAcceptController::class, 'store'])->name('invitations.accept');
});

Route::middleware(['auth'])->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', function() {
        return view('dashboard');
    });
    Route::get('/short-urls', [ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/create', [ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('short-urls.store');

    Route::middleware('role:SuperAdmin')->group(function(){
        Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('companies.store');
    });
    Route::middleware('role:Admin')->group(function(){
        Route::get('/team-members', [TeamMemberController::class, 'index'])->name('team-members.index');
    });
    Route::middleware('role:SuperAdmin,Admin')->group(function() {
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class,'store'])->name('invitations.store');
       
    });
});
