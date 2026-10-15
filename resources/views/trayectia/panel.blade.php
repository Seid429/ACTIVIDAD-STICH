@extends('trayectia.layouts.app')

@section('titulo', 'Panel de inicio')

@php
    $usuario = auth()->user();
    $maxSemana = max(1, $semanas->max());
    $semanaMax = $semanas->search($semanas->max());
    $totalPeriodo = $semanas->sum();
@endphp

@section('contenido')
{{-- Encabezado --}}
<div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
    <div>
        <p class="text-xs font-bold uppercase tracking-wider text-terra">Base operativa {{ \Illuminate\Support\Str::before($usuario->ciudad ?? 'Mérida', ',') }} centro <span class="text-muted">● Clima óptimo 31°C · Tráfico fluido Periférico</span></p>
        <h1 class="mt-2 font-display text-4xl font-extrabold tracking-tight text-pino">{{ now()->hour < 12 ? 'Buenos días' : (now()->hour < 19 ? 'Buenas tardes' : 'Buenas noches') }}, Don {{ $usuario->primer_nombre }}</h1>
        <p class="mt-1 text-muted">{{ $usuario->empresa }} · {{ $usuario->ciudad }} · <strong class="text-tinta">{{ $conteoActivos }} {{ $conteoActivos === 1 ? 'viaje activo' : 'viajes activos' }}</strong></p>
    </div>
    <div class="flex gap-3">
        <div class="flex items-center gap-3 rounded-card bg-crema-2 px-4 py-3">
            <span class="material-symbols-outlined">local_gas_station</span>
            <div class="text-xs font-bold leading-tight">Diésel Pemex Umán<p class="font-display text-base tabular">${{ number_format(config('trayectia.diesel_hoy'), 2) }}/L</p></div>
        </div>
        <a href="{{ route('viajes.index', ['estado' => 'en_ruta']) }}" class="flex items-center gap-3 rounded-card bg-crema-2 px-5 py-3 font-display font-bold hover:bg-crema-3">
            <span class="material-symbols-outlined">route</span>Ver monitoreo en vivo
        </a>
    </div>
</div>

{{-- KPIs --}}
<div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between"><p class="font-bold">Viajes este mes</p><span class="grid h-10 w-10 place-items-center rounded-full bg-crema-2"><span class="material-symbols-outlined">local_shipping</span></span></div>
        <p class="mt-4 font-display text-4xl font-extrabold tabular">{{ $viajesMes }} <span class="font-sans text-xs font-bold text-muted">fletes registrados</span></p>
        <span class="mt-4 inline-flex items-center gap-1 rounded-full bg-mint px-2.5 py-1 text-xs font-bold text-pino"><span class="material-symbols-outlined text-sm">trending_up</span>{{ $conteoActivos }} en operación</span>
    </div>
    <div class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between"><p class="font-bold">Ganancia estimada neta</p><span class="grid h-10 w-10 place-items-center rounded-full bg-terra-fondo text-terra"><span class="material-symbols-outlined">account_balance_wallet</span></span></div>
        <p class="mt-4 font-display text-4xl font-extrabold text-terra tabular">${{ number_format($gananciaMes) }} <span class="font-sans text-xs font-bold text-muted">MXN</span></p>
        <p class="mt-4 flex justify-between text-xs font-bold"><span>Margen promedio ruta</span><span>{{ $margenPromedio }}% útil</span></p>
    </div>
    <div class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between"><p class="font-bold">Evidencias completas</p><span class="grid h-10 w-10 place-items-center rounded-full bg-mint"><span class="material-symbols-outlined">fact_check</span></span></div>
        <p class="mt-4 font-display text-4xl font-extrabold tabular">{{ $porcentajeEvidencias }}%</p>
        <p class="text-xs font-bold text-muted">{{ $evidencias }} de {{ $viajesMes }} cartas porte</p>
        <div class="mt-3 h-1.5 rounded-full bg-crema-3"><div class="h-full rounded-full bg-pino" style="width: {{ $porcentajeEvidencias }}%"></div></div>
    </div>
    <div class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between"><p class="font-bold">Cobros por gestionar</p><span class="grid h-10 w-10 place-items-center rounded-full bg-terra-suave/40 text-terra"><span class="material-symbols-outlined">request_quote</span></span></div>
        <p class="mt-4 font-display text-4xl font-extrabold text-terra tabular">${{ number_format($porCobrar) }} <span class="font-sans text-xs font-bold text-muted">MXN</span></p>
        <p class="mt-4 flex justify-between text-xs font-bold">
            <span class="{{ $atrasados->count() ? 'text-bad' : 'text-ok' }}">● {{ $atrasados->count() }} {{ $atrasados->count() === 1 ? 'viaje atrasado' : 'viajes atrasados' }}</span>
            <a href="{{ route('cobros') }}" class="hover:underline">Ver cobranza</a>
        </p>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[3fr_2fr]">
    {{-- Copiloto --}}
    <section class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-pino text-white"><span class="material-symbols-outlined">auto_awesome</span></span>
                <div><p class="text-xs font-bold uppercase tracking-wider text-terra">Copiloto inteligente TrayectIA</p><p class="font-display font-bold">Sugerencia de sincronización en vivo</p></div>
            </div>
            <span class="rounded-full bg-crema-2 px-2.5 py-1 text-xs font-bold text-muted"><span class="text-terra-suave">●</span> Hace 4 min</span>
        </div>
        @if ($sugerencia)
            <div class="mt-5 rounded-card bg-crema-2 p-5">
                <div class="flex gap-4">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-white font-bold">{{ $sugerencia->chofer_datos['iniciales'] }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="flex justify-between font-bold">{{ $sugerencia->chofer_datos['nombre'] }} (Operador {{ \Illuminate\Support\Str::before($sugerencia->unidad_datos['nombre'], ' ') }}) <span class="text-xs">WhatsApp Audio · 0:38</span></p>
                        <p class="mt-1 italic">"Don {{ $usuario->primer_nombre }}, ya llegué a {{ $sugerencia->destino }} con {{ $sugerencia->cliente }}, ya me están descargando. Todo en orden con el albarán."</p>
                        <div class="mt-3 flex items-center gap-3 rounded-lg bg-white px-3 py-2">
                            <span class="grid h-8 w-8 place-items-center rounded-full bg-terra text-white"><span class="material-symbols-outlined icon-fill text-base">play_arrow</span></span>
                            <span class="flex flex-1 items-center gap-0.5">@foreach ([10, 18, 12, 22, 14, 20, 8, 16, 12, 6, 10, 6, 4] as $i => $h)<span class="w-1 rounded-full {{ $i < 9 ? 'bg-terra' : 'bg-muted/40' }}" style="height: {{ $h }}px"></span>@endforeach</span>
                            <span class="text-xs font-bold">0:38</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center">
                <p class="flex-1">¿Deseas registrar la llegada y pasar el <strong>Viaje {{ $sugerencia->folio_texto }}</strong> a estado <strong class="text-terra">"Llegó a destino"</strong>?</p>
                <a href="{{ route('viajes.show', $sugerencia) }}" class="rounded-ctrl bg-crema-2 px-4 py-2.5 text-sm font-bold hover:bg-crema-3">Ajustar</a>
                <form method="POST" action="{{ route('viajes.avanzar', $sugerencia) }}">@csrf
                    <button class="flex items-center gap-1 rounded-ctrl bg-terra px-4 py-2.5 text-sm font-bold text-white hover:bg-[#AD4F30]"><span class="material-symbols-outlined text-base">check</span>Confirmar</button>
                </form>
            </div>
        @else
            <p class="mt-6 rounded-card bg-crema-2 p-5 text-muted">Sin viajes en ruta por ahora. Cuando un chofer reporte su llegada, aquí aparecerá la sugerencia para actualizar el estado.</p>
        @endif
    </section>

    {{-- Rendimiento semanal --}}
    <section class="rounded-card bg-white p-6 shadow-card">
        <div class="flex items-start justify-between">
            <div><p class="text-xs font-bold uppercase tracking-wider">Rendimiento semanal</p><p class="font-display font-bold">Ingresos por fletes cobrados</p></div>
            <span class="rounded-full bg-crema-2 px-3 py-1 text-xs font-bold">{{ ucfirst(now()->translatedFormat('F Y')) }}</span>
        </div>
        <p class="mt-6 text-center">Mejor semana: <strong class="font-display text-lg text-terra">${{ number_format($semanas->max()) }} MXN</strong> <span class="text-xs font-bold">(Semana {{ $semanaMax }})</span></p>
        <div class="mt-4 flex h-36 items-end gap-4" role="img" aria-label="Ingresos cobrados por semana">
            @foreach ($semanas as $n => $monto)
                <div class="flex flex-1 flex-col items-center gap-2">
                    @if ($n === $semanaMax && $monto)<span class="text-xs font-bold text-terra">${{ number_format($monto / 1000, 1) }}k</span>@endif
                    <div class="w-full rounded-t-md {{ $n === $semanaMax ? 'bg-terra' : ($n === 4 ? 'bg-pino' : 'bg-crema-3') }}" style="height: {{ max(4, round($monto / $maxSemana * 100)) }}px" title="${{ number_format($monto) }} MXN"></div>
                    <span class="text-xs font-bold {{ $n === $semanaMax ? 'text-terra' : '' }}">Sem {{ $n }}</span>
                </div>
            @endforeach
        </div>
        <p class="mt-6 flex justify-between text-xs font-bold"><span>Total periodo: ${{ number_format($totalPeriodo) }} MXN</span><a href="{{ route('cobros', ['ver' => 'cobrados']) }}" class="text-terra hover:underline">Ver cobrados</a></p>
    </section>
</div>

{{-- Viajes activos --}}
<section class="mt-10">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><h2 class="font-display text-3xl font-extrabold tracking-tight text-pino">Viajes activos en ruta</h2><p class="text-muted">Monitoreo de carga, choferes y estados de liquidación en tiempo real</p></div>
        <div class="flex items-center gap-2 text-xs font-bold">
            <span class="mr-1">Filtrar por:</span>
            @foreach ([null => "Todos ($conteoActivos)", 'torton' => 'Solo Torton', 'rabon' => 'Solo Rabón'] as $clave => $texto)
                <a href="{{ route('panel', array_filter(['unidad' => $clave])) }}" class="rounded-lg px-3 py-1.5 {{ $filtro == $clave ? 'bg-pino text-white' : 'bg-crema-2 hover:bg-crema-3' }}">{{ $texto }}</a>
            @endforeach
        </div>
    </div>
    <div class="mt-6 space-y-4">
        @forelse ($activos as $viaje)
            @include('trayectia.partials.viaje-tarjeta', ['viaje' => $viaje])
        @empty
            <p class="rounded-card bg-white p-8 text-center text-muted shadow-card">No hay viajes activos con este filtro. <a href="{{ route('viajes.create') }}" class="font-bold text-terra">Registra uno nuevo</a>.</p>
        @endforelse
    </div>
</section>

{{-- Accesos rápidos --}}
<div class="mt-10 grid gap-5 md:grid-cols-3">
    <a href="{{ route('viajes.create') }}" class="flex gap-4 rounded-card bg-white p-6 shadow-card hover:shadow-panel">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-terra-fondo text-terra"><span class="material-symbols-outlined">calculate</span></span>
        <span><span class="block font-display text-xl font-bold">Cotizador Rápido de Flete</span><span class="mt-1 block text-muted">Calcula casetas SCT, diésel por kilómetro y margen neto en 30 segundos.</span><span class="mt-3 block text-sm font-bold text-terra">Abrir calculadora ›</span></span>
    </a>
    <a href="{{ route('aprobaciones.index') }}" class="flex gap-4 rounded-card bg-white p-6 shadow-card hover:shadow-panel">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-mint"><span class="material-symbols-outlined">chat</span></span>
        <span><span class="block font-display text-xl font-bold">Compartir por WhatsApp</span><span class="mt-1 block text-muted">Envía presupuesto formateado al cliente con botón de aprobación directa.</span><span class="mt-3 block text-sm font-bold">Ver enlace público ›</span></span>
    </a>
    <div class="flex gap-4 rounded-card bg-white p-6 shadow-card">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-crema-2"><span class="material-symbols-outlined">local_gas_station</span></span>
        <div class="text-sm">
            <p class="font-display text-xl font-bold">Precios de Combustible</p>
            <p class="mt-1">Estación Periférico Pte km 39: <strong>$24.60</strong></p>
            <p>Carretera Progreso km 14: <strong>$24.95</strong></p>
            <p class="mt-2 text-xs font-bold text-muted">Actualizado hoy a las 06:00 AM</p>
        </div>
    </div>
</div>
@endsection
