<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $viajes = $request->user()->viajes();

        $delMes = (clone $viajes)->delMes()->get();
        $conEvidencia = $delMes->whereNotNull('entregado_at')->count();
        $porCobrar = (clone $viajes)->porCobrar()->get();
        $atrasados = (clone $viajes)->atrasados()->get();

        $filtro = $request->query('unidad');
        $activos = (clone $viajes)->activos()
            ->when(in_array($filtro, ['torton', 'rabon']), fn ($q) => $q->where('unidad', $filtro))
            ->orderByDesc('folio')->get();

        // Ingresos cobrados por semana del mes en curso (semanas de 7 días desde el día 1).
        $semanas = collect(range(1, 4))->mapWithKeys(fn ($s) => [$s => 0.0]);
        (clone $viajes)->where('estado', 'cobrado')
            ->whereBetween('cobrado_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->each(function ($v) use (&$semanas) {
                $s = min(4, intdiv($v->cobrado_at->day - 1, 7) + 1);
                $semanas[$s] += $v->precio;
            });

        return view('trayectia.panel', [
            'viajesMes' => $delMes->count(),
            'gananciaMes' => $delMes->sum->ganancia,
            'margenPromedio' => $delMes->count() ? round($delMes->avg->margen, 1) : 0,
            'evidencias' => $conEvidencia,
            'porcentajeEvidencias' => $delMes->count() ? (int) round($conEvidencia / $delMes->count() * 100) : 0,
            'porCobrar' => $porCobrar->sum('precio'),
            'atrasados' => $atrasados,
            'activos' => $activos,
            'filtro' => $filtro,
            'conteoActivos' => (clone $viajes)->activos()->count(),
            'semanas' => $semanas,
            'sugerencia' => (clone $viajes)->where('estado', 'en_ruta')->orderByDesc('folio')->first(),
        ]);
    }
}
