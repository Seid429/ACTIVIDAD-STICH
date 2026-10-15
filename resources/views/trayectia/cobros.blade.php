@extends('trayectia.layouts.app')

@section('titulo', 'Cobros y métricas')

@php
    $pestanas = [
        'por_cobrar' => ['Por cobrar', $porCobrar->count(), 'bg-white text-pino'],
        'atrasados' => ['Atrasados', $atrasados->count(), 'bg-bad text-white'],
        'cobrados' => ['Cobrados en el mes', $cobradosMes->count(), 'bg-white text-pino'],
    ];
    // Comparativa "antes vs. con TrayectIA". Los valores "antes" son la línea base del piloto (diseño de Stitch).
    $metricas = [
        ['Captura & llamadas', '1.5', 'hrs/sem', '-70% tiempo', 'bg-mint', ['Antes (Manual)', '5.0 hrs', 100], ['Con TrayectIA', '1.5 hrs', 30], 'Ahorro de 3.5 horas cada fin de semana.'],
        ['Evidencia completa', '100%', 'viajes', '+38% certidumbre', 'bg-mint', ['Antes (Fotos sueltas)', '62%', 62], ['Con TrayectIA', '100%', 100], 'Cero remitos extraviados en caseta.'],
        ['Días de cobro', $diasCobro, 'días', 'Cobro ágil', 'bg-terra-fondo text-terra', ['Antes (Promedio)', '14 días', 100], ['Con aprobación TrayectIA', $diasCobro.' días', min(100, round($diasCobro / 14 * 100))], 'Aprobación instantánea por link.'],
        ['Ganancia neta prom.', '$'.number_format($gananciaPromedio), 'MXN', '+'.max(0, round(($gananciaPromedio / 2400 - 1) * 100)).'% utilidad', 'bg-mint', ['Antes (Sin diésel dinámico)', '$2,400', min(100, round(2400 / max($gananciaPromedio, 1) * 100))], ['Con Cotizador TrayectIA', '$'.number_format($gananciaPromedio), 100], 'Blindaje ante el diésel a $'.config('trayectia.diesel_hoy').' MXN.'],
    ];
@endphp

@section('contenido')
<div class="flex flex-col gap-6 xl:flex-row xl:items-start xl:justify-between">
    <div class="max-w-xl">
        <p class="flex items-center gap-2 text-sm uppercase tracking-wide"><span class="h-2 w-2 rounded-full bg-terra"></span>Módulo financiero peninsular <span class="text-muted">/</span> {{ ucfirst(now()->translatedFormat('F Y')) }}</p>
        <h1 class="mt-2 font-display text-4xl font-extrabold leading-tight tracking-tight text-pino">Gestión de Cobros y Rendimiento del Negocio</h1>
        <p class="mt-2 text-muted">Monitoreo de flujo de efectivo, recuperación de cartera vencida y retorno de inversión en ruta.</p>
    </div>
    <div class="grid gap-3 sm:grid-cols-2">
        <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card"><span class="grid h-10 w-10 place-items-center rounded-lg bg-crema-2"><span class="material-symbols-outlined">pending_actions</span></span><div><p class="text-xs font-bold uppercase">Por cobrar</p><p class="font-display text-xl font-bold tabular">${{ number_format($porCobrar->sum('precio')) }} <span class="text-xs">MXN</span></p></div></div>
        <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card"><span class="grid h-10 w-10 place-items-center rounded-lg bg-terra-fondo text-bad"><span class="material-symbols-outlined">warning</span></span><div><p class="text-xs font-bold uppercase text-bad">Atrasados ({{ $atrasados->count() }} {{ $atrasados->count() === 1 ? 'viaje' : 'viajes' }})</p><p class="font-display text-xl font-bold text-bad tabular">${{ number_format($atrasados->sum('precio')) }} <span class="text-xs">MXN</span></p></div></div>
        <div class="flex items-center gap-3 rounded-card bg-pino p-4 text-white shadow-card"><span class="grid h-10 w-10 place-items-center rounded-lg bg-white/10"><span class="material-symbols-outlined">check_circle</span></span><div><p class="text-xs font-bold uppercase text-white/70">Cobrado este mes</p><p class="font-display text-xl font-bold tabular">${{ number_format($cobradosMes->sum('precio')) }} <span class="text-xs">MXN</span></p></div></div>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[2fr_1fr]">
    <section class="rounded-card bg-white p-6 shadow-card">
        <h2 class="font-display text-xl font-bold">Bandeja de Liquidación de Fletes</h2>
        <p class="text-sm text-muted">Filtra facturas y folios con soporte de carta porte y comprobante firmado.</p>
        <form method="GET" class="mt-5 flex flex-wrap gap-3">
            <input type="hidden" name="ver" value="{{ $pestana }}">
            <span class="flex items-center gap-2 rounded-ctrl bg-crema-2 px-4 py-3 text-sm font-bold"><span class="material-symbols-outlined text-base">calendar_month</span>{{ ucfirst(now()->translatedFormat('F Y')) }}</span>
            <label class="flex items-center gap-2 rounded-ctrl bg-crema-2 px-4 text-sm font-bold">
                <span class="material-symbols-outlined text-base">apartment</span>
                <select name="cliente" onchange="this.form.submit()" class="h-11 bg-transparent font-bold outline-none" aria-label="Empresa">
                    <option value="">Todas las empresas</option>
                    @foreach ($clientes as $c)<option value="{{ $c }}" @selected($cliente === $c)>{{ $c }}</option>@endforeach
                </select>
            </label>
        </form>
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($pestanas as $clave => [$texto, $n, $insignia])
                <a href="{{ route('cobros', array_filter(['ver' => $clave, 'cliente' => $cliente])) }}" class="flex items-center gap-2 rounded-ctrl px-5 py-2.5 font-display font-bold {{ $pestana === $clave ? 'bg-pino text-white' : 'bg-crema-2 hover:bg-crema-3' }}">
                    {{ $texto }} <span class="rounded-full px-2 text-xs {{ $insignia }}">{{ $n }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="relative overflow-hidden rounded-card bg-pino p-6 text-white shadow-card">
        <span class="material-symbols-outlined absolute right-5 top-5 text-terra-suave">chat</span>
        <p class="text-xs font-bold uppercase tracking-wider text-white/60">Estrategia de cobro WhatsApp</p>
        <h2 class="mt-1 font-display text-xl font-bold">Cobranza Amable y Respaldada</h2>
        <p class="mt-3 text-sm text-white/60">El 84% de los pagos demorados en Yucatán se resuelven enviando la foto del remito firmado con sello del almacén en un formato de audio/texto respetuoso.</p>
        <p class="mt-5 text-xs font-bold text-white/50">Don {{ auth()->user()->primer_nombre }} + Asistente TrayectIA</p>
        <span class="absolute -bottom-10 -right-10 h-36 w-36 rounded-full bg-white/5"></span>
    </section>
</div>

{{-- Tabla --}}
<section class="mt-6 overflow-x-auto rounded-card bg-white shadow-card">
    <table class="w-full min-w-[64rem] text-left">
        <thead class="bg-crema-2 text-xs font-bold uppercase tracking-wide">
            <tr><th class="px-6 py-4">Folio viaje</th><th class="px-4">Empresa cliente</th><th class="px-4">Chofer y ruta</th><th class="px-4">Monto</th><th class="px-4">Fecha acordada</th><th class="px-4">Estado y días</th><th class="px-6 text-right">Acción</th></tr>
        </thead>
        <tbody class="divide-y divide-crema-3">
            @forelse ($lista as $viaje)
                @php $dias = $viaje->dias_para_pago; @endphp
                <tr>
                    <td class="px-6 py-5 font-display text-lg font-bold">{{ $viaje->folio_texto }}</td>
                    <td class="px-4"><p class="font-bold">{{ $viaje->cliente }}</p><p class="text-xs text-muted">{{ $viaje->carga }}</p></td>
                    <td class="min-w-[12rem] px-4 text-sm"><p class="font-bold">{{ $viaje->chofer_datos['nombre'] }} ({{ \Illuminate\Support\Str::before($viaje->unidad_datos['nombre'], ' ') }})</p><p class="text-xs text-muted">{{ $viaje->origen }} → {{ $viaje->destino }} ({{ $viaje->distancia_km }} km)</p></td>
                    <td class="px-4 font-display text-lg font-bold tabular">${{ number_format($viaje->precio) }} <span class="text-xs">MXN</span></td>
                    <td class="px-4 text-sm">
                        @if ($viaje->estado === 'cobrado') Cobrado {{ $viaje->cobrado_at->format('d/m') }}
                        @elseif ($dias < 0) <span class="font-bold text-bad">Venció hace {{ abs($dias) }} {{ abs($dias) === 1 ? 'día' : 'días' }}</span>
                        @else Vence en {{ $dias }} {{ $dias === 1 ? 'día' : 'días' }} @endif
                    </td>
                    <td class="px-4">
                        @if ($viaje->estado === 'cobrado')
                            <span class="inline-flex items-center gap-1 rounded-full bg-ok/15 px-3 py-1 text-xs font-bold text-ok"><span class="material-symbols-outlined text-sm">paid</span>Cobrado</span>
                        @elseif ($dias < 0)
                            <span class="inline-flex items-center gap-1 rounded-full bg-bad/10 px-3 py-1 text-xs font-bold text-bad"><span class="material-symbols-outlined text-sm">priority_high</span>Atraso crítico</span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-mint px-3 py-1 text-xs font-bold text-pino"><span class="material-symbols-outlined text-sm">verified</span>Aprobado por cliente</span>
                        @endif
                    </td>
                    <td class="px-6 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('viajes.show', $viaje) }}" class="flex items-center gap-1 rounded-ctrl bg-crema-2 px-3 py-2 text-xs font-bold hover:bg-crema-3"><span class="material-symbols-outlined text-base">receipt</span>Ver evidencia</a>
                            @if ($viaje->estado === 'facturado')
                                <form method="POST" action="{{ route('viajes.avanzar', $viaje) }}">@csrf
                                    <button class="rounded-ctrl bg-pino px-3 py-2 text-xs font-bold text-white hover:bg-pino-700">Registrar cobro</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-muted">Sin fletes en esta bandeja.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>

{{-- Métricas del piloto --}}
<section class="mt-12">
    <p class="flex items-center gap-2 text-sm uppercase tracking-wide text-terra"><span class="material-symbols-outlined text-base">monitoring</span>Resultados reales del piloto</p>
    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h2 class="mt-1 font-display text-3xl font-extrabold tracking-tight text-pino">Métricas de impacto del piloto TrayectIA ({{ auth()->user()->empresa }})</h2>
            <p class="text-muted">Comparativa mensual de rentabilidad, tiempo y cobro con el modelo de Hombre-Camión Digital.</p>
        </div>
        <span class="flex w-fit items-center gap-2 rounded-full bg-mint px-4 py-2 text-xs font-bold text-pino"><span class="h-2 w-2 rounded-full bg-pino"></span>Evaluación al 100% de la Península</span>
    </div>
    <div class="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($metricas as [$titulo, $valor, $unidad, $chip, $chipClase, $antes, $con, $nota])
            <div class="flex flex-col rounded-card bg-white p-6 shadow-card">
                <div class="flex items-start justify-between gap-2"><p class="text-xs font-bold uppercase">{{ $titulo }}</p><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $chipClase }}">{{ $chip }}</span></div>
                <p class="mt-1 font-display text-2xl font-bold tabular">{{ $valor }} <span class="text-lg">{{ $unidad }}</span></p>
                <div class="mt-5 space-y-3 text-xs font-bold">
                    <div><p class="flex justify-between"><span>{{ $antes[0] }}</span><span>{{ $antes[1] }}</span></p><div class="mt-1 h-1.5 rounded-full bg-crema-3"><div class="h-full rounded-full bg-muted" style="width: {{ $antes[2] }}%"></div></div></div>
                    <div><p class="flex justify-between"><span>{{ $con[0] }}</span><span>{{ $con[1] }}</span></p><div class="mt-1 h-1.5 rounded-full bg-mint-claro"><div class="h-full rounded-full {{ $loop->index % 2 ? 'bg-terra' : 'bg-pino' }}" style="width: {{ $con[2] }}%"></div></div></div>
                </div>
                <p class="mt-auto flex gap-2 pt-6 text-sm"><span class="material-symbols-outlined text-base text-terra">check_circle</span>{{ $nota }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="mt-8 flex flex-col gap-4 rounded-card bg-crema-2 p-6 sm:flex-row sm:items-center">
    <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-pino font-display text-lg font-bold text-white">{{ auth()->user()->iniciales }}</span>
    <div class="flex-1"><p class="font-display text-xl font-bold">"Ahora no me jinetean la lana de las vueltas."</p><p class="text-muted">— Don {{ auth()->user()->primer_nombre }}, operador en el Corredor Peninsular Mérida – Progreso</p></div>
    <a href="{{ route('viajes.create') }}" class="rounded-ctrl bg-terra px-5 py-3 text-center font-display font-bold text-white shadow-card hover:bg-[#AD4F30]">Cotizar siguiente viaje</a>
</section>
@endsection
