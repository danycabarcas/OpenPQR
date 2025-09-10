<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

// Evitar conflicto con Illuminate\Http\Request
use App\Models\Request as Pqrs;
use App\Models\Department;

class PqrsController extends Controller
{
    public function dashboard(HttpRequest $request)
    {
        $companyId = $request->user()->company_id;

        $base = Pqrs::query()->where('company_id', $companyId);

        $today = Carbon::today();

        $total      = (clone $base)->count();
        $nuevas     = (clone $base)->where('status', 'nueva')->count();
        $enProceso  = (clone $base)->where('status', 'en_proceso')->count();
        $respondidas= (clone $base)->where('status', 'respondida')->count();
        $cerradas   = (clone $base)->where('status', 'cerrada')->count();

        $venceHoy   = (clone $base)->whereDate('response_due_date', $today)->count();
        $vencidas   = (clone $base)->whereNotNull('response_due_date')
                                   ->whereDate('response_due_date', '<', $today)
                                   ->whereNotIn('status', ['respondida','cerrada','rechazada'])
                                   ->count();

        // 10 más recientes
        $recent = (clone $base)->latest('created_at')->limit(10)->get();

        return view('app.dashboard', compact(
            'total','nuevas','enProceso','respondidas','cerradas','venceHoy','vencidas','recent'
        ));
    }

    public function index(HttpRequest $request)
    {
        $companyId = $request->user()->company_id;

        $q       = trim($request->get('q', ''));
        $status  = $request->get('status', '');
        $dept    = $request->get('department_id', '');
        $created = $request->get('created', ''); // ej. 2025-09

        $query = Pqrs::query()->where('company_id', $companyId);

        if ($q !== '') {
            $query->where(function($s) use ($q) {
                $s->where('subject', 'like', "%{$q}%")
                  ->orWhere('tracking_code', 'like', "%{$q}%")
                  ->orWhere('citizen_name', 'like', "%{$q}%")
                  ->orWhere('citizen_lastname', 'like', "%{$q}%")
                  ->orWhere('citizen_email', 'like', "%{$q}%");
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($dept !== '') {
            $query->where('department_id', $dept);
        }

        if ($created !== '') {
            // filtro YYYY-MM
            try {
                [$y,$m] = explode('-', $created);
                $query->whereYear('created_at', (int)$y)
                      ->whereMonth('created_at', (int)$m);
            } catch (\Throwable $e) {}
        }

        $requests = $query->latest('created_at')->paginate(15)->appends($request->query());

        // Para select de departamentos
        $departments = Department::where('company_id', $companyId)->orderBy('name')->get();

        // KPIs rápidos
        $today = Carbon::today();
        $venceHoy = (clone $query)->whereDate('response_due_date', $today)->count();
        $vencidas = (clone $query)->whereNotNull('response_due_date')
                                  ->whereDate('response_due_date', '<', $today)
                                  ->whereNotIn('status', ['respondida','cerrada','rechazada'])
                                  ->count();

        return view('app.requests.index', compact('requests','departments','venceHoy','vencidas','q','status','dept','created'));
    }

    public function show(HttpRequest $request, $id)
    {
        $companyId = $request->user()->company_id;

        $ticket = Pqrs::where('company_id', $companyId)->findOrFail($id);

        [$slaBadge, $slaText] = $this->slaInfo($ticket);

        $departments = Department::where('company_id', $companyId)->orderBy('name')->get();

        return view('app.requests.show', compact('ticket','slaBadge','slaText','departments'));
    }

    public function updateStatus(HttpRequest $request, $id)
    {
        $request->validate([
            'status' => 'required|in:nueva,en_proceso,respondida,cerrada,rechazada',
        ]);

        $companyId = $request->user()->company_id;

        $ticket = Pqrs::where('company_id', $companyId)->findOrFail($id);
        $ticket->status = $request->status;

        // Si se marca respondida y no hay fecha de vencimiento, puedes opcionalmente fijar endDate lógica
        $ticket->save();

        return back()->with('success', 'Estado actualizado.');
    }

    public function assign(HttpRequest $request, $id)
    {
        $companyId = $request->user()->company_id;

        $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $dept = Department::where('company_id', $companyId)->findOrFail($request->department_id);

        $ticket = Pqrs::where('company_id', $companyId)->findOrFail($id);
        $ticket->department_id = $dept->id;
        $ticket->save();

        return back()->with('success', 'Asignado correctamente.');
    }

    private function slaInfo(Pqrs $t): array
    {
        if (!$t->response_due_date) {
            return ['secondary', 'Sin fecha de vencimiento'];
        }

        $today = Carbon::today();
        $due   = Carbon::parse($t->response_due_date)->startOfDay();

        if (in_array($t->status, ['respondida','cerrada','rechazada'])) {
            return ['secondary', 'Finalizada'];
        }

        if ($due->isSameDay($today)) {
            return ['warning', 'Vence hoy'];
        }

        if ($due->lessThan($today)) {
            return ['danger', 'Vencida'];
        }

        $diff = $today->diffInDays($due);
        return ['success', "A tiempo (faltan {$diff} días)"];
    }
}
