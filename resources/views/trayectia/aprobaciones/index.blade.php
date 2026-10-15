@extends('trayectia.layouts.app')

@section('titulo', 'Aprobación cliente')

@section('contenido')
<p class="text-xs font-bold uppercase tracking-wider text-terra">Recepción digital de carga</p>
<h1 class="mt-1 font-display text-4xl font-extrabold tracking-tight text-pino">Aprobación del cliente</h1>
<p class="mt-1 max-w-2xl text-muted">Comparte el link por WhatsApp: tu cliente revisa la evidencia y aprueba la recepción sin crear cuenta. Al aprobar, el viaje queda listo para facturar.</p>

<section class="mt-8">
    <h2 class="font-display text-xl font-bold">Pendientes de firma ({{ $pendientes->count() }})</h2>
    <div class="mt-4 space-y-4">
        @forelse ($pendientes as $viaje)
            @php
                $link = route('aprobacion.cliente', $viaje->token_aprobacion);
                $whatsapp = 'https://wa.me/?text='.rawurlencode("Hola, le comparto la recepción del viaje {$viaje->folio_texto} ({$viaje->origen} → {$viaje->destino}). Por favor confirme aquí: {$link}");
            @endphp
            <article class="flex flex-col gap-4 rounded-card bg-white p-5 shadow-card lg:flex-row lg:items-center">
                <div class="flex-1">
                    <p class="font-display text-lg font-bold">Viaje {{ $viaje->folio_texto }} · {{ $viaje->origen }} → {{ $viaje->destino }}</p>
                    <p class="text-sm text-muted">{{ $viaje->cliente }} · entregado {{ $viaje->entregado_at?->diffForHumans() }}</p>
                    <p class="mt-2 truncate rounded-lg bg-crema-2 px-3 py-2 font-mono text-xs">{{ $link }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $link }}'); this.textContent = '¡Copiado!'" class="rounded-ctrl bg-crema-2 px-4 py-3 text-sm font-bold hover:bg-crema-3">Copiar link</button>
                    <a href="{{ $link }}" target="_blank" class="rounded-ctrl bg-crema-2 px-4 py-3 text-sm font-bold hover:bg-crema-3">Vista del cliente</a>
                    <a href="{{ $whatsapp }}" target="_blank" class="flex items-center gap-1 rounded-ctrl bg-pino px-4 py-3 text-sm font-bold text-white hover:bg-pino-700"><span class="material-symbols-outlined text-base">share</span>Enviar por WhatsApp</a>
                </div>
            </article>
        @empty
            <p class="rounded-card bg-white p-8 text-center text-muted shadow-card">No hay viajes esperando firma. Cuando marques un viaje como entregado aparecerá aquí.</p>
        @endforelse
    </div>
</section>

@if ($aprobados->isNotEmpty())
    <section class="mt-10">
        <h2 class="font-display text-xl font-bold">Aprobaciones recientes</h2>
        <ul class="mt-4 divide-y divide-crema-3 rounded-card bg-white px-5 shadow-card">
            @foreach ($aprobados as $viaje)
                <li class="flex items-center justify-between gap-3 py-4">
                    <a href="{{ route('viajes.show', $viaje) }}" class="font-bold hover:underline">Viaje {{ $viaje->folio_texto }} · {{ $viaje->cliente }}</a>
                    <span class="flex items-center gap-1 text-sm text-ok"><span class="material-symbols-outlined text-base">verified</span>{{ $viaje->aprobado_por }} · {{ $viaje->aprobado_at->format('d/m H:i') }}</span>
                </li>
            @endforeach
        </ul>
    </section>
@endif
@endsection
