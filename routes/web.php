<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Public\SignupController;
use App\Http\Controllers\Admin\CompanyController;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('plans', PlanController::class);

        // CRUD de empresas
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/plan', [CompanyController::class,'updatePlan'])->name('companies.updatePlan');
        // Cambiar plan (opcional, si usas el modal del index)
        Route::post('companies/{company}/change-plan', [CompanyController::class, 'changePlan'])
            ->name('companies.change-plan');

});



Route::get('/registro', [SignupController::class, 'create'])->name('signup.create');
Route::post('/registro', [SignupController::class, 'store'])->name('signup.store');


require __DIR__.'/auth.php';
