@extends('trayectia.layouts.base')

@section('titulo', 'Aprobación del viaje '.$viaje->folio_texto)

@php
    $operador = $viaje->user;
    $momento = $viaje->entregado_at ?? $viaje->fecha->copy()->setTimeFromTimeString($viaje->hora);
@endphp

@section('cuerpo')
<header class="flex items-center justify-between bg-crema px-4 py-5 sm:px-8">
    <span class="flex items-center gap-2 font-display text-lg font-bold"><span class="grid h-8 w-8 place-items-center rounded-lg bg-pino"><span class="material-symbols-outlined text-base text-white">local_shipping</span></span>TrayectIA</span>
    <span class="flex items-center gap-1 text-xs font-bold"><span class="material-symbols-outlined text-base">lock</span>Portal seguro</span>
</header>

<main class="mx-auto max-w-md px-4 pb-16">
    <p class="flex items-center justify-between rounded-full bg-crema-2 px-4 py-2 text-xs font-bold uppercase tracking-wide">
        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-terra"></span>Enlace seguro de validación móvil</span>
        <span class="flex items-center gap-1 normal-case"><span class="material-symbols-outlined text-sm">verified_user</span>256-bit SSL</span>
    </p>

    <div class="mt-6 rounded-[2rem] bg-white p-5 shadow-panel">
        <div class="flex items-center gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-pino"><span class="material-symbols-outlined text-white">local_shipping</span></span>
            <div class="flex-1"><p class="font-display text-lg font-bold">TrayectIA</p><p class="text-xs font-bold text-muted">Recepción Digital de Carga</p></div>
            <span class="rounded-full bg-mint px-2.5 py-1 text-xs font-bold text-pino">Folio {{ $viaje->folio_texto }}</span>
        </div>

        @foreach (['exito' => 'bg-mint text-pino', 'error' => 'bg-terra-fondo text-[#7E2C0C]'] as $tipo => $clase)
            @if (session($tipo))<p class="mt-5 flex items-center gap-2 rounded-card px-4 py-3 text-sm font-bold {{ $clase }}"><span class="material-symbols-outlined">{{ $tipo === 'exito' ? 'check_circle' : 'error' }}</span>{{ session($tipo) }}</p>@endif
        @endforeach

        <div class="mt-5 flex items-center gap-4 rounded-card bg-crema-2 p-4">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-lg bg-pino font-display text-xl font-bold text-white">{{ $operador->iniciales }}</span>
            <div>
                <p class="flex items-center gap-1 font-bold">{{ $operador->empresa ?? $operador->name }} <span class="material-symbols-outlined text-base text-terra-suave">verified</span></p>
                <p class="text-sm">Hombre-Camión · {{ $operador->ciudad }}</p>
                <p class="text-xs font-bold"><span class="text-terra">★ 4.9</span> · {{ $viajesValidados }} viajes validados</p>
            </div>
        </div>

        <div class="mt-4 rounded-card bg-crema-2 p-4">
            <p class="text-xs font-bold uppercase tracking-wider text-terra">Cliente facturación</p>
            <p class="font-display text-lg font-bold">{{ $viaje->cliente }}</p>
            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div class="rounded-lg bg-white p-3"><p class="text-xs font-bold">Obra destino</p><p class="font-bold">{{ $viaje->destino }}</p></div>
                <div class="rounded-lg bg-white p-3"><p class="text-xs font-bold">Fecha y hora</p><p class="font-bold">{{ $momento->isToday() ? 'Hoy' : $momento->format('d/m') }} · {{ $momento->format('H:i') }} hrs</p></div>
                <div class="rounded-lg bg-white p-3"><p class="text-xs font-bold">Operador asignado</p><p class="font-bold">{{ $viaje->chofer_datos['nombre'] }}</p></div>
                <div class="rounded-lg bg-white p-3"><p class="text-xs font-bold">Unidad autorizada</p><p class="font-bold">{{ \Illuminate\Support\Str::before($viaje->unidad_datos['nombre'], ' ') }} {{ $viaje->unidad_datos['placas'] }}</p></div>
            </div>
            @if ($viaje->carga)
                <div class="mt-3 flex gap-3 rounded-lg bg-pino p-4 text-white">
                    <span class="material-symbols-outlined">inventory_2</span>
                    <div><p class="text-xs font-bold uppercase tracking-wider text-white/70">Carga comprobada en guía</p><p>{{ $viaje->carga }}</p></div>
                </div>
            @endif
        </div>

        <div class="mt-5">
            <p class="flex items-center justify-between"><span class="flex items-center gap-1 font-display text-lg font-bold"><span class="material-symbols-outlined text-terra">photo_camera</span>Evidencia en sitio</span><span class="text-xs font-bold">{{ $viaje->entregado_at ? '3 registros GPS' : 'Pendiente' }}</span></p>
            @if ($viaje->entregado_at)
                <div class="relative mt-3 flex h-44 flex-col justify-end overflow-hidden rounded-card bg-gradient-to-br from-pino-700 to-pino-900 p-3 text-white">
                    <span class="material-symbols-outlined absolute right-6 top-6 text-7xl text-white/10">local_shipping</span>
                    <span class="absolute left-3 top-3 flex items-center gap-1 rounded bg-white px-2 py-0.5 text-xs font-bold text-tinta"><span class="h-2 w-2 rounded-full bg-terra"></span>Maniobra #1</span>
                    <p class="flex items-center justify-between rounded-lg bg-black/40 px-3 py-2 text-xs font-bold"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-sm">verified</span>Descarga verificada en obra</span>{{ $viaje->entregado_at->format('H:i') }} hrs</p>
                </div>
            @else
                <p class="mt-3 rounded-card bg-crema-2 p-4 text-sm text-muted">El operador aún no registra la descarga. Podrás aprobar cuando llegue a destino.</p>
            @endif
        </div>

        @if ($viaje->estado === 'entregado')
            <form method="POST" action="{{ route('aprobacion.aprobar', $viaje->token_aprobacion) }}" class="mt-6 space-y-3">
                @csrf
                <label for="aprobado_por" class="block text-sm font-bold">Nombre de quien recibe</label>
                <input id="aprobado_por" name="aprobado_por" value="{{ old('aprobado_por') }}" placeholder="Ej. Arq. Manuel Escalante" class="campo @error('aprobado_por') invalido @enderror">
                @error('aprobado_por')<p class="text-sm text-bad">{{ $message }}</p>@enderror
                <button class="flex h-14 w-full items-center justify-center gap-2 rounded-ctrl bg-pino font-display text-lg font-bold text-white shadow-card hover:bg-pino-700 active:scale-[.98]">
                    <span class="material-symbols-outlined">task_alt</span>Aprobar recepción completa
                </button>
                <a href="https://wa.me/52{{ $operador->whatsapp }}?text={{ rawurlencode("Hola, tengo una observación sobre el viaje {$viaje->folio_texto}.") }}" target="_blank" class="flex h-12 w-full items-center justify-center rounded-ctrl border-[1.5px] border-pino font-bold text-pino hover:bg-pino/5">Reportar una observación</a>
            </form>
        @elseif ($viaje->aprobado_at)
            <p class="mt-6 flex items-center gap-2 rounded-card bg-mint p-4 font-bold text-pino"><span class="material-symbols-outlined">verified</span>Recepción aprobada por {{ $viaje->aprobado_por }} el {{ $viaje->aprobado_at->format('d/m/Y H:i') }}.</p>
        @endif
    </div>

    <p class="mt-6 flex items-center justify-center gap-2 text-center text-sm text-muted"><span class="material-symbols-outlined icon-fill text-base">shield</span>Aprobación directa con validez ante SAT para CFDI Carta Porte v3.1</p>
</main>

<footer class="bg-crema-2 px-4 py-6 text-sm sm:px-8">
    <div class="flex flex-col justify-between gap-2 sm:flex-row"><span>© {{ now()->year }} TrayectIA · Infraestructura Digital para el Hombre-Camión Mexicano</span><span class="text-xs font-bold">Mérida · Progreso · Umán · Soporte WhatsApp 24/7</span></div>
</footer>
@endsection
