<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $q = Company::query()
            ->when($request->filled('q'), function ($qq) use ($request) {
                $s = trim($request->q);
                $qq->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                      ->orWhere('slug', 'like', "%{$s}%")
                      ->orWhere('email_contact', 'like', "%{$s}%");
                });
            })
            ->orderBy('name');

        $companies = $q->paginate(15)->withQueryString();
        $companies->load(['subscriptions' => fn($x) => $x->active()->latest('start_date'), 'subscriptions.plan']);

        $plans = Plan::where('is_active', true)->orderBy('price')->get();

        return view('admin.companies.index', compact('companies', 'plans'));
    }

    public function create()
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        return view('admin.companies.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['nullable', 'string', 'max:255', 'unique:companies,slug'],
            'sector'         => ['nullable', 'string', 'max:255'],
            'email_contact'  => ['nullable', 'email', 'max:255'],
            'phone_contact'  => ['nullable', 'string', 'max:50'],
            'plan_id'        => ['required', 'exists:plans,id'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        DB::transaction(function () use ($data) {
            $company = Company::create([
                'name'          => $data['name'],
                'slug'          => $data['slug'],
                'sector'        => $data['sector'] ?? null,
                'email_contact' => $data['email_contact'] ?? null,
                'phone_contact' => $data['phone_contact'] ?? null,
                'is_active'     => true,
            ]);

            // Suscripción inicial
            $plan = Plan::find($data['plan_id']);
            Subscription::create([
                'company_id'        => $company->id,
                'plan_id'           => $plan->id,
                'is_trial'          => false,
                'status'            => 'active',
                'start_date'        => now()->toDateString(),
                'end_date'          => null,
                'price'             => $plan->price,
                'last_payment_date' => null,
                'next_billing_date' => now()->addMonth()->toDateString(),
            ]);
        });

        return redirect()->route('admin.companies.index')->with('success', 'Empresa creada.');
    }

    public function edit(Company $company)
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $company->load(['subscriptions' => fn($q) => $q->active()->latest('start_date'), 'subscriptions.plan']);
        return view('admin.companies.edit', compact('company','plans'));
    }


    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'slug'           => ['required', 'string', 'max:255', 'unique:companies,slug,' . $company->id],
            'sector'         => ['nullable', 'string', 'max:255'],
            'email_contact'  => ['nullable', 'email', 'max:255'],
            'phone_contact'  => ['nullable', 'string', 'max:50'],
            'is_active'      => ['boolean'],
        ]);

        $company->update($data);

        return back()->with('success', 'Empresa actualizada.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return back()->with('success', 'Empresa eliminada.');
    }

    public function changePlan(Request $request, Company $company)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($data['plan_id']);

        DB::transaction(function () use ($company, $plan) {
            // expirar la suscripción activa actual
            Subscription::where('company_id', $company->id)
                ->where('status', 'active')
                ->update(['status' => 'expired', 'end_date' => now()->toDateString()]);

            // crear nueva suscripción
            Subscription::create([
                'company_id'        => $company->id,
                'plan_id'           => $plan->id,
                'is_trial'          => false,
                'status'            => 'active',
                'start_date'        => now()->toDateString(),
                'end_date'          => null,
                'price'             => $plan->price,
                'last_payment_date' => null,
                'next_billing_date' => now()->addMonth()->toDateString(),
            ]);
        });

        return back()->with('success', 'Plan cambiado correctamente.');
    }
}
