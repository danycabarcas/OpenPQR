<?php

use Illuminate\Support\Facades\Route;

// Controllers generales
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\SignupController; // ajusta si tu namespace es distinto

// Controllers de Super Admin
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\CompanyController;

// Controllers de Empresa (App)
use App\Http\Controllers\App\PqrsController;

// Controllers de Landing Empresas
use App\Http\Controllers\Public\LandingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página inicial pública
Route::get('/', function () {
    return view('welcome');
});

// Dashboard por defecto (no usarás si ya tienes admin/app separados)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil de usuario (común a todos los roles)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Registro público (formulario de alta inicial)
Route::get('/registro', [SignupController::class, 'create'])->name('signup.create');
Route::post('/registro', [SignupController::class, 'store'])->name('signup.store');

/*
|--------------------------------------------------------------------------
| Super Admin (admin/*)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard global
        Route::get('/dashboard', function () {
            return view('admin.dashboard'); // crea tu vista
        })->name('dashboard');

        // Planes
        Route::resource('plans', PlanController::class);

        // Empresas
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/plan', [CompanyController::class,'updatePlan'])->name('companies.updatePlan');
        Route::post('companies/{company}/change-plan', [CompanyController::class,'changePlan'])->name('companies.change-plan');
    });

/*
|--------------------------------------------------------------------------
| Portal de Empresa (app/*)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','verified','company.active'])
    ->prefix('app')
    ->name('app.')
    ->group(function () {
        // Dashboard empresa
        Route::get('/dashboard', [PqrsController::class, 'dashboard'])->name('dashboard');

        // PQRSD
        Route::get('/requests', [PqrsController::class, 'index'])->name('requests.index');
        Route::get('/requests/{id}', [PqrsController::class, 'show'])->name('requests.show');
        Route::patch('/requests/{id}/status', [PqrsController::class, 'updateStatus'])->name('requests.updateStatus');
        Route::patch('/requests/{id}/assign', [PqrsController::class, 'assign'])->name('requests.assign');
    });


//Portal Clientes Landing empresas
Route::prefix('/')->group(function () {
    // Landing por empresa: /{slug}
    Route::get('{slug}', [LandingController::class, 'show'])->name('site.show');

    // Radicar PQRSD (POST)
    Route::post('{slug}/pqrsd', [LandingController::class, 'store'])->name('site.pqrsd.store');

    // Consultar estado (POST)
    Route::post('{slug}/track', [LandingController::class, 'track'])->name('site.pqrsd.track');
});


// Rutas de autenticación (Laravel Breeze/Fortify/Jetstream)
require __DIR__.'/auth.php';
