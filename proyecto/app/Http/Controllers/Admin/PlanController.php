<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::query()
            ->when($request->filled('q'), function ($q) use ($request) {
                $s = trim($request->q);
                $q->where(function ($qq) use ($s) {
                    $qq->where('name', 'like', "%{$s}%")
                       ->orWhere('slug', 'like', "%{$s}%")
                       ->orWhere('description', 'like', "%{$s}%");
                });
            })
            ->when($request->filled('active'), function ($q) use ($request) {
                $q->where('is_active', filter_var($request->active, FILTER_VALIDATE_BOOL));
            })
            ->orderBy('price') // gratis arriba si price=0
            ->paginate(12)
            ->withQueryString();

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        $plan = new Plan();

        return view('admin.plans.create', [
            'plan' => $plan,
            'supportOptions' => [
                'tickets_email' => 'Tickets + Email',
                'whatsapp'      => 'WhatsApp',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // slug automático si no se envía
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data = $this->normalize($data);

        Plan::create($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan creado con éxito.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', [
            'plan' => $plan,
            'supportOptions' => [
                'tickets_email' => 'Tickets + Email',
                'whatsapp'      => 'WhatsApp',
            ],
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validateData($request, $plan);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $data = $this->normalize($data);

        $plan->update($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plan eliminado.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Valida el request. Si se pasa $plan, ignora su id en el unique de slug.
     */
    private function validateData(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'name'                   => ['required', 'string', 'max:100'],
            'slug'                   => [
                'nullable', 'string', 'max:120',
                Rule::unique('plans', 'slug')->ignore($plan?->id),
            ],
            'description'            => ['nullable', 'string', 'max:1000'],

            'price'                  => ['nullable', 'numeric', 'min:0'],

            // límites (0 = ilimitado)
            'max_requests_per_month' => ['nullable', 'integer', 'min:0'],
            'max_departments'        => ['nullable', 'integer', 'min:0'],
            'max_users'              => ['nullable', 'integer', 'min:0'],

            // flags de branding/funcionalidades
            'custom_colors'          => ['nullable', 'boolean'],
            'custom_banner'          => ['nullable', 'boolean'],
            'enable_sign'            => ['nullable', 'boolean'],
            'enable_qr'              => ['nullable', 'boolean'],

            // nuevos campos
            'storage_gb'             => ['nullable', 'integer', 'min:0'],
            'support_channel'        => ['required', Rule::in(['tickets_email', 'whatsapp'])],
            'allow_custom_domain'    => ['nullable', 'boolean'],
            'is_active'              => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Normaliza los datos (checkboxes, enteros vacíos, defaults).
     */
    private function normalize(array $data): array
    {
        // Checkboxes -> boolean
        foreach ([
            'custom_colors',
            'custom_banner',
            'enable_sign',
            'enable_qr',
            'allow_custom_domain',
            'is_active',
        ] as $flag) {
            $data[$flag] = !empty($data[$flag]);
        }

        // Numéricos (si vienen vacíos => 0 para límites/price)
        foreach ([
            'price',
            'max_requests_per_month',
            'max_departments',
            'max_users',
            'storage_gb',
        ] as $num) {
            if (!isset($data[$num]) || $data[$num] === '') {
                // price/limits vacíos => 0 (en límites significa “ilimitado”)
                $data[$num] = 0;
            } else {
                $data[$num] = (int) $data[$num];
            }
        }

        // Canal de soporte por defecto
        if (empty($data['support_channel']) || !in_array($data['support_channel'], ['tickets_email', 'whatsapp'])) {
            $data['support_channel'] = 'tickets_email';
        }

        return $data;
    }
}
