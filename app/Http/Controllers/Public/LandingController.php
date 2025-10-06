<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Request as PqrsRequest; // si tu modelo se llama Request (tabla request)
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LandingController extends Controller
{
    public function show(string $slug)
    {
        $company = Company::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        // Opcional: últimos avisos o info pública
        return view('public.landing', compact('company', 'slug'));
    }

    public function store(Request $request, string $slug)
    {
        $company = Company::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $data = $request->validate([
            'type'        => ['required', Rule::in(['P','Q','R','S','D'])], // Petición/Queja/Reclamo/Sugerencia/Denuncia (ajusta a tus códigos)
            'subject'     => ['required','string','max:180'],
            'description' => ['required','string','max:5000'],
            'full_name'   => ['required','string','max:120'],
            'id_type'     => ['required','string','max:10'],
            'id_number'   => ['required','string','max:40'],
            'email'       => ['nullable','email','max:150'],
            'phone'       => ['nullable','string','max:30'],
            'accept'      => ['accepted'],
            'attachments.*' => ['nullable','file','max:4096'], // 4MB c/u
        ],[
            'accept.accepted' => 'Debes aceptar el tratamiento de datos.'
        ]);

        // Generar código de radicado simple (ajusta a tu formato)
        $tracking = strtoupper(Str::random(3)).'-'.now()->format('ymd').'-'.Str::random(4);

        $pqrs = new PqrsRequest();
        $pqrs->company_id = $company->id;
        $pqrs->type = $data['type'];
        $pqrs->subject = $data['subject'];
        $pqrs->description = $data['description'];
        $pqrs->citizen_fullname = $data['full_name'];
        $pqrs->citizen_id_type = $data['id_type'];
        $pqrs->citizen_id_number = $data['id_number'];
        $pqrs->citizen_email = $data['email'] ?? null;
        $pqrs->citizen_phone = $data['phone'] ?? null;
        $pqrs->tracking_code = $tracking;
        $pqrs->status = 'new';
        // Calcula vencimiento según norma (ej. 15 días hábiles -> placeholder 15 días calendario)
        $pqrs->response_due_date = now()->addDays(15);
        $pqrs->save();

        // Adjuntos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("pqrsd/{$company->id}/{$pqrs->id}", 'public');
                // Si tienes tabla attachments, aquí la creas. Si no, guarda en un JSON de la request.
                // $pqrs->attachments()->create(['path'=>$path, 'filename'=>$file->getClientOriginalName()]);
            }
        }

        return back()->with('ok', "¡Tu PQRSD fue radicada! Código: {$tracking}")->with('tracking', $tracking);
    }

    public function track(Request $request, string $slug)
    {
        $company = Company::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $request->validate([
            'tracking_code' => ['nullable','string','max:50'],
            'id_number'     => ['nullable','string','max:40'],
            'phone'         => ['nullable','string','max:30'],
        ]);

        // Regla: o viene tracking_code, o viene combo documento+teléfono
        if (!$request->filled('tracking_code') && !($request->filled('id_number') && $request->filled('phone'))) {
            return back()->withErrors(['tracking_code' => 'Ingresa el código de radicado o documento+celular.'])->withInput();
        }

        $query = PqrsRequest::where('company_id',$company->id);

        if ($request->filled('tracking_code')) {
            $query->where('tracking_code', trim($request->tracking_code));
        } else {
            $query->where('citizen_id_number', trim($request->id_number))
                  ->where('citizen_phone', trim($request->phone));
        }

        $result = $query->latest()->first();

        if (!$result) {
            return back()->withErrors(['tracking_code' => 'No encontramos registros con los datos ingresados.'])->withInput();
        }

        return back()->with('track_result', [
            'tracking_code' => $result->tracking_code,
            'status'        => $result->status,
            'subject'       => $result->subject,
            'created_at'    => optional($result->created_at)->format('Y-m-d H:i'),
            'due_date'      => optional($result->response_due_date)->format('Y-m-d'),
        ]);
    }
}
