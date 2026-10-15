<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CobroController extends Controller
{
    public function __invoke(Request $request): View
    {
        $viajes = $request->user()->viajes();
        $pestana = in_array($request->query('ver'), ['atrasados', 'cobrados']) ? $request->query('ver') : 'por_cobrar';
        $cliente = $request->query('cliente');

        $porCobrar = (clone $viajes)->porCobrar()->get();
        $atrasados = (clone $viajes)->atrasados()->get();
        $cobradosMes = (clone $viajes)->where('estado', 'cobrado')
            ->whereBetween('cobrado_at', [now()->startOfMonth(), now()->endOfMonth()])->get();

        $lista = match ($pestana) {
            'atrasados' => $atrasados,
            'cobrados' => $cobradosMes,
            default => $porCobrar,
        };
        if ($cliente) {
            $lista = $lista->where('cliente', $cliente);
        }

        // Promedio real de días entre entrega y cobro, para la tarjeta de "días de atraso".
        $cobrados = (clone $viajes)->where('estado', 'cobrado')->whereNotNull('entregado_at')->get();
        $diasCobro = $cobrados->count()
            ? round($cobrados->avg(fn ($v) => $v->entregado_at->diffInDays($v->cobrado_at)))
            : 0;

        return view('trayectia.cobros', [
            'pestana' => $pestana,
            'cliente' => $cliente,
            'clientes' => (clone $viajes)->distinct()->orderBy('cliente')->pluck('cliente'),
            'lista' => $lista->sortBy('fecha_pago')->values(),
            'porCobrar' => $porCobrar,
            'atrasados' => $atrasados,
            'cobradosMes' => $cobradosMes,
            'diasCobro' => $diasCobro,
            'gananciaPromedio' => (clone $viajes)->get()->avg->ganancia ?? 0,
        ]);
    }
}
