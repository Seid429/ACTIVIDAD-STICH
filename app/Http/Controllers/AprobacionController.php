<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AprobacionController extends Controller
{
    /** Operador: viajes entregados cuyo link de aprobación puede compartir. */
    public function index(Request $request): View
    {
        return view('trayectia.aprobaciones.index', [
            'pendientes' => $request->user()->viajes()->where('estado', 'entregado')->orderByDesc('folio')->get(),
            'aprobados' => $request->user()->viajes()->whereNotNull('aprobado_at')->latest('aprobado_at')->limit(5)->get(),
        ]);
    }

    /** Cliente (público, sin cuenta): link de aprobación del viaje. */
    public function show(string $token): View
    {
        $viaje = Viaje::with('user')->where('token_aprobacion', $token)->firstOrFail();

        return view('trayectia.aprobaciones.cliente', [
            'viaje' => $viaje,
            'viajesValidados' => $viaje->user->viajes()->whereNotNull('aprobado_at')->count(),
        ]);
    }

    public function aprobar(Request $request, string $token): RedirectResponse
    {
        $viaje = Viaje::where('token_aprobacion', $token)->firstOrFail();

        $datos = $request->validate([
            'aprobado_por' => ['required', 'string', 'max:120'],
        ], [], ['aprobado_por' => 'nombre de quien recibe']);

        if ($viaje->estado !== 'entregado') {
            return back()->with('error', 'Este viaje ya no está pendiente de aprobación.');
        }

        $viaje->update([
            'estado' => 'aprobado',
            'aprobado_at' => now(),
            'aprobado_por' => $datos['aprobado_por'],
        ]);

        return back()->with('exito', 'Recepción aprobada. ¡Gracias! El transportista ya fue notificado.');
    }
}
