<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    /**
     * Listado con búsqueda. Pasa $plans para el modal de cambio de plan.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q', ''));

        $companies = Company::query()
            ->with(['activeSubscription'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                       ->orWhere('slug', 'like', "%{$search}%")
                       ->orWhere('email_contact', 'like', "%{$search}%")
                       ->orWhere('phone_contact', 'like', "%{$search}%")
                       ->orWhere('city', 'like', "%{$search}%")
                       ->orWhere('nit', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $plans = Plan::orderBy('price')->get();

        return view('admin.companies.index', compact('companies', 'plans'));
    }

    /**
     * Form de creación
     */
    public function create()
    {
        $company = new Company();
        $plans   = Plan::orderBy('price')->get(); // por si eliges plan al crear
        return view('admin.companies.create', compact('company', 'plans'));
    }

    /**
     * Guardar empresa + archivos + (opcional) suscripción
     */
    public function store(Request $request)
    {
        // normaliza color (acepta con/sin #)
        $request->merge([
            'color_primary' => $request->color_primary
                ? (str_starts_with($request->color_primary, '#') ? $request->color_primary : '#'.$request->color_primary)
                : null,
        ]);

        $data = $request->validate([
            'name'           => ['required','string','max:255'],
            'slug'           => ['nullable','string','max:255','unique:companies,slug'],
            'sector'         => ['nullable','string','max:255'],
            'email_contact'  => ['nullable','email','max:255'],
            'phone_contact'  => ['nullable','string','max:50'],
            'address'        => ['nullable','string','max:255'],
            'city'           => ['nullable','string','max:120'],
            'nit'            => ['nullable','string','max:30'],
            'color_primary'  => ['nullable','regex:/^#?[0-9a-fA-F]{6}$/'],
            'plan_id'        => ['nullable','exists:plans,id'],
            'is_active'      => ['nullable','boolean'],

            // archivos (puedes añadir dimensions más adelante si quieres)
            'logo'           => ['nullable','mimes:png,jpg,jpeg,webp','max:2048'],
            'banner'         => ['nullable','mimes:png,jpg,jpeg,webp','max:4096'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($request->name);
        }

        $data['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use (&$company, $data, $request) {
            // 1) Crear empresa sin rutas de archivos
            $company = Company::create(collect($data)->except(['logo','banner'])->toArray());

            // 2) Subidas (se guardan y se actualiza logo_url / banner_url)
            $this->handleUploads($company, $request);

            // 3) (Opcional) crear suscripción activa inicial si vino plan_id
            if ($request->filled('plan_id')) {
                $plan = Plan::find($request->plan_id);
                Subscription::create([
                    'company_id'        => $company->id,
                    'plan_id'           => $plan->id,
                    'status'            => 'active',
                    'start_date'        => now()->toDateString(),
                    'end_date'          => null,
                    'price'             => $plan->price,
                    'last_payment_date' => null,
                    'next_billing_date' => now()->addMonth()->toDateString(),
                ]);

                // (opcional) reflejar plan_id también en companies si quieres
                $company->update(['plan_id' => $plan->id]);
            }
        });

        return redirect()->route('admin.companies.index')->with('success', 'Empresa creada correctamente.');
    }

    /**
     * Form de edición
     */
    public function edit(Company $company)
    {
        $plans = Plan::orderBy('price')->get(); // para mostrar plan en el form si quieres
        return view('admin.companies.edit', compact('company', 'plans'));
    }

    /**
     * Actualizar empresa + archivos + (opcional) cambio de plan
     */
    public function update(Request $request, Company $company)
    {
        $request->merge([
            'color_primary' => $request->color_primary
                ? (str_starts_with($request->color_primary, '#') ? $request->color_primary : '#'.$request->color_primary)
                : null,
        ]);

        $data = $request->validate([
            'name'           => ['required','string','max:255'],
            'slug'           => ['nullable','string','max:255', Rule::unique('companies','slug')->ignore($company->id)],
            'sector'         => ['nullable','string','max:255'],
            'email_contact'  => ['nullable','email','max:255'],
            'phone_contact'  => ['nullable','string','max:50'],
            'address'        => ['nullable','string','max:255'],
            'city'           => ['nullable','string','max:120'],
            'nit'            => ['nullable','string','max:30'],
            'color_primary'  => ['nullable','regex:/^#?[0-9a-fA-F]{6}$/'],
            'plan_id'        => ['nullable','exists:plans,id'],
            'is_active'      => ['nullable','boolean'],

            // archivos
            'logo'           => ['nullable','mimes:png,jpg,jpeg,webp','max:2048'],
            'banner'         => ['nullable','mimes:png,jpg,jpeg,webp','max:4096'],
            'remove_logo'    => ['nullable','boolean'],
            'remove_banner'  => ['nullable','boolean'],
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($request->name, $company->id);
        }

        $data['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($request, $company, $data) {
            // 1) Eliminar archivos si el usuario lo pidió
            if ($request->boolean('remove_logo') && $company->logo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $company->logo_url));
                $company->logo_url = null;
            }
            if ($request->boolean('remove_banner') && $company->banner_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $company->banner_url));
                $company->banner_url = null;
            }

            // 2) Subir archivos nuevos (si vienen)
            $this->handleUploads($company, $request);

            // 3) Actualizar datos base
            $company->fill(collect($data)->except(['logo','banner','remove_logo','remove_banner'])->toArray());
            $company->save();

            // 4) Cambio de plan (opcional)
            if ($request->filled('plan_id')) {
                $newPlanId     = (int) $request->plan_id;
                $currentPlanId = (int) optional($company->activeSubscription)->plan_id;

                if (!$currentPlanId || $newPlanId !== $currentPlanId) {
                    // cerrar suscripción actual
                    if ($company->activeSubscription) {
                        $company->activeSubscription->update([
                            'status'   => 'expired',
                            'end_date' => now()->toDateString(),
                        ]);
                    }
                    // crear la nueva
                    $plan = Plan::findOrFail($newPlanId);
                    Subscription::create([
                        'company_id'        => $company->id,
                        'plan_id'           => $plan->id,
                        'status'            => 'active',
                        'start_date'        => now()->toDateString(),
                        'end_date'          => null,
                        'price'             => $plan->price,
                        'last_payment_date' => null,
                        'next_billing_date' => now()->addMonth()->toDateString(),
                    ]);

                    // (opcional) reflejar plan_id también en companies si quieres
                    $company->update(['plan_id' => $plan->id]);
                }
            }
        });

        return back()->with('success', 'Empresa actualizada correctamente.');
    }

    /**
     * Eliminar (borra archivos asociados)
     */
    public function destroy(Company $company)
    {
        // elimina archivos si existen
        foreach (['logo_url', 'banner_url'] as $col) {
            if ($company->{$col}) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $company->{$col}));
            }
        }
        $company->delete();

        return redirect()->route('admin.companies.index')->with('success', 'Empresa eliminada.');
    }

    // ===================== Helpers internos =====================

    /**
     * Genera un slug único a partir de un nombre o slug base.
     */
    private function generateUniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $i = 1;

        $exists = Company::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
            ->exists();

        while ($exists) {
            $slug = "{$original}-{$i}";
            $i++;
            $exists = Company::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '<>', $ignoreId))
                ->exists();
        }
        return $slug;
    }

    /**
     * Maneja subida/reemplazo de logo y banner.
     * Guarda en storage/public/companies/{id}/branding y setea logo_url/banner_url.
     */
    private function handleUploads(Company $company, Request $request): void
    {
        $disk = 'public';
        $dir  = "companies/{$company->id}/branding";
        Storage::disk($disk)->makeDirectory($dir);

        // LOGO
        if ($request->hasFile('logo')) {
            if ($company->logo_url) {
                Storage::disk($disk)->delete(str_replace('/storage/', '', $company->logo_url));
            }
            $path = $request->file('logo')->store($dir, $disk);
            $company->logo_url = Storage::url($path);
        }

        // BANNER
        if ($request->hasFile('banner')) {
            if ($company->banner_url) {
                Storage::disk($disk)->delete(str_replace('/storage/', '', $company->banner_url));
            }
            $path = $request->file('banner')->store($dir, $disk);
            $company->banner_url = Storage::url($path);
        }
    }
}
