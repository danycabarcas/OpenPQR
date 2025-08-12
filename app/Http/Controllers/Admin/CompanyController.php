<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $q = Company::query();

        // búsqueda por texto (name/slug)
        if ($search = trim((string) $request->input('q', ''))) {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                   ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // filtro por estado (active/inactive)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $q->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $q->where('is_active', false);
            }
        }

        $companies = $q->latest()->paginate(12)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    /*
    |--------------------------------------------------------------------------
    | Crear
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $company = new Company();
        $plans   = Plan::orderBy('price')->get();

        // Plan por defecto para el create: startup (si existe), de lo contrario el primero
        $activePlanId = Plan::where('slug', 'startup')->value('id') ?? optional($plans->first())->id;

        return view('admin.companies.create', compact('company', 'plans', 'activePlanId'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['slug']      = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $company = Company::create($data);

        // Crear suscripción activa con el plan seleccionado
        $plan = Plan::findOrFail($request->input('plan_id'));

        Subscription::create([
            'company_id'        => $company->id,
            'plan_id'           => $plan->id,
            'is_trial'          => false, // si no tienes esta columna, quítala
            'status'            => 'active',
            'start_date'        => now()->toDateString(),
            'end_date'          => null,
            'price'             => $plan->price,
            'last_payment_date' => null,
            'next_billing_date' => now()->addMonth()->toDateString(),
        ]);

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */
    public function edit(Company $company)
    {
        $plans = Plan::orderBy('price')->get();

        // ID del plan activo actual (puede ser null si no tiene suscripción)
        $activePlanId = optional($company->activeSubscription)->plan_id;

        return view('admin.companies.edit', compact('company', 'plans', 'activePlanId'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $this->validateData($request, $company);

        $data['slug']      = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $company->update($data);

        // ¿cambió de plan?
        $newPlanId      = (int) $request->input('plan_id');
        $currentPlanId  = (int) optional($company->activeSubscription)->plan_id;

        if (!$currentPlanId || $newPlanId !== $currentPlanId) {
            // cerrar suscripción anterior (si existe)
            if ($company->activeSubscription) {
                $company->activeSubscription->update([
                    'status'   => 'cancelled',
                    'end_date' => now()->toDateString(),
                ]);
            }

            // abrir nueva suscripción
            $plan = Plan::findOrFail($newPlanId);

            Subscription::create([
                'company_id'        => $company->id,
                'plan_id'           => $plan->id,
                'is_trial'          => false, // si no tienes esta columna, quítala
                'status'            => 'active',
                'start_date'        => now()->toDateString(),
                'end_date'          => null,
                'price'             => $plan->price,
                'last_payment_date' => null,
                'next_billing_date' => now()->addMonth()->toDateString(),
            ]);
        }

        return redirect()
            ->route('admin.companies.edit', $company)
            ->with('success', 'Empresa actualizada correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */
    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa eliminada.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    protected function validateData(Request $request, ?Company $company = null): array
    {
        return $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255', Rule::unique('companies', 'slug')->ignore($company?->id)],
            'sector'        => ['nullable', 'string', 'max:100'],
            'logo_url'      => ['nullable', 'string', 'max:255'],
            'banner_url'    => ['nullable', 'string', 'max:255'],
            'color_primary' => ['nullable', 'string', 'max:20'],
            'email_contact' => ['nullable', 'email', 'max:255'],
            'phone_contact' => ['nullable', 'string', 'max:50'],
            'is_active'     => ['sometimes', 'boolean'],

            // plan seleccionado en el formulario
            'plan_id'       => ['required', 'exists:plans,id'],
        ]);
    }
}
