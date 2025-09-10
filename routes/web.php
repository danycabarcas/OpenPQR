<?php


use Illuminate\Support\Facades\Route;

// IMPORTS DE CONTROLLERS
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\App\PqrsController;

// ⚠️ Usa el que tengas realmente:
// Si tu controlador está en: app/Http/Controllers/Public/SignupController.php
use App\Http\Controllers\Public\SignupController;
// Si lo cambiaste a PublicSite:
// use App\Http\Controllers\PublicSite\SignupController;

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



// Portal de empresa (usuarios autenticados de una compañía)
Route::middleware(['auth','verified','company.active'])->prefix('app')->name('app.')->group(function () {
    // Dashboard de empresa
    Route::get('/dashboard', [PqrsController::class, 'dashboard'])->name('dashboard');

    // PQRSD: listado y detalle
    Route::get('/requests', [PqrsController::class, 'index'])->name('requests.index');
    Route::get('/requests/{id}', [PqrsController::class, 'show'])->name('requests.show');

    // Acciones de gestión (según rol/policy)
    Route::patch('/requests/{id}/status', [PqrsController::class, 'updateStatus'])->name('requests.updateStatus');
    Route::patch('/requests/{id}/assign', [PqrsController::class, 'assign'])->name('requests.assign');
});





require __DIR__.'/auth.php';
