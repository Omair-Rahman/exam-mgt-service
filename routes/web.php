<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\UserManagementController;
use App\Http\Controllers\Auth\PasswordResetOtpController;
use App\Http\Controllers\Auth\ExamineeRegistrationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamineeDashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionYearController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\CountdownController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('index');

// Password login
Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/login/otp', [OtpLoginController::class, 'show'])->name('otp.show');
Route::post('/otp/send', [OtpLoginController::class, 'send'])->name('otp.send');
Route::get('/otp/verify', [OtpLoginController::class, 'showVerify'])->name('otp.verify.show');
Route::post('/otp/verify', [OtpLoginController::class, 'verify'])->name('otp.verify');

Route::get('/examinee/register', [ExamineeRegistrationController::class, 'show'])->name('examinee.register.show');
Route::post('/examinee/register', [ExamineeRegistrationController::class, 'store'])->name('examinee.register.store');

Route::get('/password/forgot', [PasswordResetOtpController::class, 'show'])->name('password.forgot');
Route::post('/password/forgot', [PasswordResetOtpController::class, 'send'])->name('password.forgot.send');

Route::get('/password/reset-otp', [PasswordResetOtpController::class, 'showVerify'])->name('password.reset.otp');
Route::post('/password/reset-otp', [PasswordResetOtpController::class, 'verifyAndReset'])->name('password.reset.otp.verify');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('admin');
        Route::get('/manager', [DashboardController::class, 'manager'])->name('manager');
        Route::get('/employee', [DashboardController::class, 'employee'])->name('employee');
        Route::get('/adv-user', [DashboardController::class, 'advUser'])->name('adv-user');
    });

    // examinee dashboard route
    Route::get('/examinee', [ExamineeDashboardController::class, 'index'])->name('dashboard.examinee');

    // User management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::get('/{id}', [UserManagementController::class, 'details'])->name('details');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/{user}/edit', [UserManagementController::class, 'profile'])->name('edit');
        Route::put('/', [UserManagementController::class, 'profile_update'])->name('update');
    });

    // category routes
    Route::resource('categories', CategoryController::class)->except(['show']);
    // subcategory routes
    Route::resource('subcategories', SubcategoryController::class)->except(['show']);
    // question year routes
    Route::resource('question_years', QuestionYearController::class)->except(['show']);
    // dependent dropdown JSON endpoint
    Route::get('subcategories/by-category', [SubcategoryController::class, 'byCategory'])
        ->name('subcategories.by-category');
    // package routes
    Route::resource('packages', PackageController::class)->except(['show']);

    // question routes
    Route::resource('questions', QuestionController::class)->except(['show']);

    // Dependent subcategory dropdown
    Route::get('/ajax/subcategories', [QuestionController::class, 'subcategories'])
        ->name('ajax.subcategories');

    // Remaining counters (per year, and per year+category)
    Route::get('/ajax/remaining', [QuestionController::class, 'remaining'])
        ->name('ajax.remaining');
    // Full table (all years)
    Route::get('/questions/table', [QuestionController::class, 'tableAll'])
        ->name('questions.table.all');

    // Table filtered by a specific year
    Route::get('/questions/table/year/{year}', [QuestionController::class, 'tableByYear'])
        ->name('questions.table.year');

    // (Optional AJAX endpoint — only if you want same-page switching without reload)
    Route::get('/ajax/questions/year/{year}', [QuestionController::class, 'ajaxByYear'])
        ->name('ajax.questions.byYear');

    // CountDoun routes

    Route::get('/admin/countdown', [CountdownController::class, 'edit'])->name('countdown.edit');
    Route::post('/admin/countdown', [CountdownController::class, 'save'])->name('countdown.save');

    // Public JSON (optional; useful for frontend widgets anywhere)
    Route::get('/countdown.json', [CountdownController::class, 'json'])->name('countdown.json');
});
