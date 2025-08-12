<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// Si instalaste Intervention Image:
use Intervention\Image\Laravel\Facades\Image;

class CompanyController extends Controller
{
    // Listado
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));

        $companies = Company::query()
            ->with('activeSubscription')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('slug', 'like', "%{$q}%")
                       ->orWhere('email_contact', 'like', "%{$q}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Para el modal "cambiar plan" del index
        $plans = Plan::orderBy('price')->get();

        return view('admin.companies.index', compact('companies', 'plans'));
    }

    // Formulario de creación
    public function create()
    {
        $company = new Company();
        $plans   = Plan::orderBy('price')->get(); // por si deseas permitir elegir plan inicial
        return view('admin.companies.create', compact('company', 'plans'));
    }

    // Guardar
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $company = Company::create($data);

        $this->saveBrandingImages($company, $request);
        $company->save();

        // Asignar plan inicial (por defecto Startup = id 1, ajusta si deseas)
        $initialPlanId = $request->input('initial_plan_id', 1);
        $plan = Plan::find($initialPlanId) ?? Plan::first();

        if ($plan) {
            Subscription::create([
                'company_id'        => $company->id,
                'plan_id'           => $plan->id,
                'start_date'        => now()->toDateString(),
                'end_date'          => null,
                'status'            => 'active',
                'price'             => $plan->price ?? null,
                'last_payment_date' => null,
                'next_billing_date' => null, // si manejas cobro mensual, puedes setear now()->addMonth()
            ]);
        }

        return redirect()
            ->route('admin.companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    // Formulario de edición
    public function edit(Company $company)
    {
        $plans = Plan::orderBy('price')->get(); // por si quieres mostrar plan en el form
        return view('admin.companies.edit', compact('company', 'plans'));
    }

    // Actualizar
    public function update(Request $request, Company $company)
    {
        $data = $this->validatedData($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $company->fill($data);

        $this->saveBrandingImages($company, $request);
        $company->save();

        return back()->with('success', 'Empresa actualizada correctamente.');
    }

    // (Opcional) Eliminar
    public function destroy(Company $company)
    {
        // eliminar archivos
        $disk = 'public';
        foreach (['logo_url','banner_url'] as $col) {
            if ($company->{$col}) {
                $old = str_replace('/storage/', '', $company->{$col});
                Storage::disk($disk)->delete($old);
            }
        }

        $company->delete();

        return back()->with('success', 'Empresa eliminada.');
    }

    // ----------------- Helpers -----------------

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name'           => ['required','string','max:255'],
            'slug'           => ['nullable','string','max:255'],
            'sector'         => ['nullable','string','max:50'],
            'email_contact'  => ['nullable','email','max:255'],
            'phone_contact'  => ['nullable','string','max:30'],
            'color_primary'  => ['nullable','string','max:20'],
            'is_active'      => ['sometimes','boolean'],

            // Archivos (logo/banner)
            // Nota: dimensions no aplica a SVG, por eso separamos
            'logo_file'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:1024','dimensions:min_width=300,min_height=100'],
            'logo_svg'       => ['nullable','mimetypes:image/svg+xml','max:512'],
            'banner_file'    => ['nullable','image','mimes:jpg,jpeg,png,webp','max:3072','dimensions:min_width=1200,min_height=300'],
        ],[
            'logo_file.dimensions'   => 'El logo debe ser horizontal (mínimo 300x100).',
            'banner_file.dimensions' => 'El banner debe ser horizontal (mínimo 1200x300).',
        ]);
    }

    private function saveBrandingImages(Company $company, Request $request): void
    {
        $disk = 'public';
        $dir  = "companies/{$company->id}/branding";
        Storage::disk($disk)->makeDirectory($dir);

        // Logo (PNG/JPG/WebP)
        if ($request->hasFile('logo_file')) {
            if ($company->logo_url) {
                $old = str_replace('/storage/', '', $company->logo_url);
                Storage::disk($disk)->delete($old);
            }

            // Si tienes Intervention instalada, optimiza/convierte a webp
            if (class_exists(Image::class)) {
                $img = Image::read($request->file('logo_file'));
                $web = (clone $img)->cover(600, 150)->toWebp(90); // 600x150 recomendado
                Storage::disk($disk)->put("{$dir}/logo.webp", (string) $web);
                $company->logo_url = Storage::url("{$dir}/logo.webp");
            } else {
                // fallback sin optimización
                $path = $request->file('logo_file')->store($dir, $disk);
                $company->logo_url = Storage::url($path);
            }
        }

        // Logo SVG (opcional)
        if ($request->hasFile('logo_svg')) {
            if ($company->logo_url) {
                $old = str_replace('/storage/', '', $company->logo_url);
                Storage::disk($disk)->delete($old);
            }
            $request->file('logo_svg')->storeAs($dir, 'logo.svg', $disk);
            $company->logo_url = Storage::url("{$dir}/logo.svg");
        }

        // Banner
        if ($request->hasFile('banner_file')) {
            if ($company->banner_url) {
                $old = str_replace('/storage/', '', $company->banner_url);
                Storage::disk($disk)->delete($old);
            }

            if (class_exists(Image::class)) {
                $img = Image::read($request->file('banner_file'));
                $web = (clone $img)->cover(1600, 400)->toWebp(88); // 1600x400 recomendado
                Storage::disk($disk)->put("{$dir}/banner.webp", (string) $web);
                $company->banner_url = Storage::url("{$dir}/banner.webp");
            } else {
                $path = $request->file('banner_file')->store($dir, $disk);
                $company->banner_url = Storage::url($path);
            }
        }
    }
}
