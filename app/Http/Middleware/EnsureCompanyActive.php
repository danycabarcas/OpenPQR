<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario o no hay empresa, deja pasar (ajústalo a tu gusto)
        if (!$user || !$user->company) {
            return $next($request);
        }

        // Si la empresa está suspendida/cancelada o desactivada, bloquea
        $company = $user->company;
        $isInactive = (isset($company->is_active) && !$company->is_active)
                      || in_array($company->status ?? '', ['suspended','cancelled']);

        if ($isInactive) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu empresa no está activa. Contacta soporte.');
        }

        return $next($request);
    }
}
