@extends('trayectia.layouts.app')

@section('titulo', 'Viaje '.$viaje->folio_texto)

@php
    $u = $viaje->unidad_datos;
    $c = $viaje->chofer_datos;
    $creado = $viaje->created_at;
    $salida = $viaje->fecha->copy()->setTimeFromTimeString($viaje->hora);
    $linkAprobacion = route('aprobacion.cliente', $viaje->token_aprobacion);
    $whatsapp = 'https://wa.me/?text='.rawurlencode("Hola, le comparto la recepción del viaje {$viaje->folio_texto} ({$viaje->origen} → {$viaje->destino}). Por favor confirme aquí: {$linkAprobacion}");

    // Línea de tiempo: [título, hora o null si está pendiente, descripción]
    $pasos = [
        ['Orden creada y cotizada', $creado, "Generada por Don ".auth()->user()->primer_nombre.". Tarifa aprobada $".number_format($viaje->precio)." MXN".($viaje->carga ? " para {$viaje->carga}." : '.')],
        ['Asignación automática WhatsApp', $creado->copy()->addMinutes(15), "Enviada al chofer {$c['nombre']}. Bot confirmó aceptación con placas {$u['placas']}."],
        ['Salida de patio', $viaje->estado === 'en_ruta' && $viaje->avance === 0 ? null : $salida, "Carga completada en {$viaje->origen}. Inicio de trayecto monitoreado por GPS."],
        ['Llegada a destino y descarga', $viaje->entregado_at, "Llegada reportada a {$viaje->destino}. Remisión y fotografías de descarga registradas."],
        ['Aprobación del cliente', $viaje->aprobado_at, $viaje->aprobado_por ? "{$viaje->aprobado_por} validó la recepción completa mediante link web sin contraseñas." : 'El cliente valida la recepción desde el link público, sin crear cuenta.'],
        ['Factura CFDI 4.0 emitida', $viaje->facturado_at, $viaje->fecha_pago ? 'Pago pactado para el '.$viaje->fecha_pago->format('d/m/Y').'.' : 'Timbrado con complemento Carta Porte.'],
        ['Flete cobrado', $viaje->cobrado_at, 'Pago recibido y conciliado.'],
    ];
    $actual = collect($pasos)->search(fn ($p) => $p[1] === null);
    $accion = match ($viaje->estado) {
        'en_ruta' => ['Marcar como entregado', 'where_to_vote'],
        'aprobado' => ['Generar CFDI ahora', 'receipt_long'],
        'facturado' => ['Registrar cobro', 'payments'],
        default => null,
    };
@endphp

@section('contenido')
{{-- Encabezado del expediente --}}
<section class="rounded-card bg-white p-6 shadow-card sm:p-8">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-base text-terra">folder_open</span>Expediente digital operativo <span class="text-muted">●</span> Flete local peninsular</p>
            <h1 class="mt-2 font-display text-4xl font-extrabold tracking-tight text-pino">Viaje {{ $viaje->folio_texto }} · {{ $viaje->origen }} <span class="material-symbols-outlined text-3xl">arrow_forward</span> {{ $viaje->destino }}</h1>
        </div>
        <div class="no-print flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 rounded-ctrl bg-crema-2 px-5 py-3 font-display font-bold hover:bg-crema-3"><span class="material-symbols-outlined">print</span>Descargar PDF</button>
            @if ($viaje->estado === 'aprobado')
                <form method="POST" action="{{ route('viajes.avanzar', $viaje) }}">@csrf
                    <button class="flex items-center gap-2 rounded-ctrl bg-terra px-5 py-3 font-display font-bold text-white shadow-card hover:bg-[#AD4F30]"><span class="material-symbols-outlined">receipt_long</span>Generar Factura</button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-5 flex flex-wrap gap-2 text-sm font-bold">
        <span class="flex items-center gap-1 rounded-full bg-crema-2 px-3 py-1.5"><span class="material-symbols-outlined text-base">apartment</span>{{ $viaje->cliente }}</span>
        <span class="flex items-center gap-1 rounded-full bg-mint px-3 py-1.5 text-pino"><span class="material-symbols-outlined text-base">attach_money</span>Flete: ${{ number_format($viaje->precio) }} MXN</span>
        <span class="flex items-center gap-1 rounded-full bg-crema-2 px-3 py-1.5"><span class="material-symbols-outlined text-base text-terra">badge</span>Chofer: {{ $c['nombre'] }} ({{ \Illuminate\Support\Str::before($u['nombre'], ' ') }} {{ $u['capacidad'] }})</span>
        <span class="flex items-center gap-1 rounded-full bg-crema-2 px-3 py-1.5"><span class="material-symbols-outlined text-base">location_on</span>Ruta: {{ $viaje->origen }} → {{ $viaje->destino }} ({{ $viaje->distancia_km }} km)</span>
        <span class="rounded-full px-3 py-1.5 {{ $viaje->estado_datos['clase'] }}">{{ $viaje->estado_datos['etiqueta'] }}</span>
    </div>

    <div class="mt-5 flex flex-col gap-4 rounded-card bg-crema-2 p-5 sm:flex-row sm:items-center">
        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-pino text-white"><span class="material-symbols-outlined">verified_user</span></span>
        <div class="flex-1"><p class="font-bold">Rentabilidad del flete</p><p class="text-sm text-muted">Costo operativo ${{ number_format($viaje->costo_total, 2) }} MXN (diésel ${{ number_format($viaje->costo_diesel, 2) }} · casetas ${{ number_format($viaje->casetas, 2) }} · chofer ${{ number_format($viaje->viatico, 2) }} · desgaste ${{ number_format($viaje->desgaste, 2) }}).</p></div>
        <span class="rounded-lg bg-white px-4 py-2 text-sm font-bold {{ $viaje->margen > 21 ? 'text-ok' : ($viaje->margen >= 10 ? 'text-[#7a5a12]' : 'text-bad') }}">Ganancia ${{ number_format($viaje->ganancia, 2) }} · {{ $viaje->margen }}%</span>
    </div>
</section>

<div class="mt-8 grid gap-8 xl:grid-cols-[minmax(0,1fr)_23rem]">
    <div class="space-y-8">
        {{-- Línea de tiempo --}}
        <section class="rounded-card bg-white p-6 shadow-card sm:p-8">
            <div class="flex items-center justify-between">
                <h2 class="flex items-center gap-2 font-display text-2xl font-bold"><span class="material-symbols-outlined text-terra">timeline</span>Línea de Tiempo Operativa</h2>
                <span class="rounded-full bg-terra-fondo px-2.5 py-1 text-xs font-bold text-terra">En Tiempo Real</span>
            </div>
            <ol class="relative mt-6 space-y-5 border-l-2 border-crema-3 pl-8">
                @foreach ($pasos as $i => [$titulo, $momento, $texto])
                    @continue($actual !== false && $i > $actual)
                    @php $esActual = $i === $actual; @endphp
                    <li class="relative">
                        <span class="absolute -left-[2.6rem] top-4 grid h-6 w-6 place-items-center rounded-full {{ $esActual ? 'bg-terra-fondo ring-4 ring-terra/20' : 'bg-pino' }}">
                            <span class="{{ $esActual ? 'h-2.5 w-2.5 rounded-full bg-terra' : 'material-symbols-outlined text-sm text-white' }}">{{ $esActual ? '' : 'check' }}</span>
                        </span>
                        <div class="rounded-card {{ $esActual ? 'bg-crema-3' : 'bg-crema-2' }} p-4">
                            <p class="flex justify-between gap-3 font-display text-lg font-bold {{ $esActual ? 'text-terra' : '' }}">{{ $esActual ? 'Siguiente: '.$titulo : $titulo }}
                                @if ($esActual)<span class="h-fit shrink-0 whitespace-nowrap rounded bg-terra px-2 py-0.5 font-sans text-[11px] font-bold uppercase text-white">Paso actual</span>
                                @else<span class="shrink-0 font-sans text-xs font-bold text-muted">{{ $momento->format('d/m H:i') }} hrs</span>@endif
                            </p>
                            <p class="mt-1">{{ $texto }}</p>
                            @if ($esActual)
                                <div class="no-print mt-3 flex flex-wrap items-center gap-3">
                                    @if ($accion)
                                        <form method="POST" action="{{ route('viajes.avanzar', $viaje) }}">@csrf
                                            <button class="flex items-center gap-1 rounded-lg bg-pino px-4 py-2 text-sm font-bold text-white hover:bg-pino-700"><span class="material-symbols-outlined text-base">{{ $accion[1] }}</span>{{ $accion[0] }}</button>
                                        </form>
                                    @elseif ($viaje->estado === 'entregado')
                                        <a href="{{ $whatsapp }}" target="_blank" class="flex items-center gap-1 rounded-lg bg-pino px-4 py-2 text-sm font-bold text-white hover:bg-pino-700"><span class="material-symbols-outlined text-base">share</span>Enviar link por WhatsApp</a>
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $linkAprobacion }}'); this.textContent = '¡Link copiado!'" class="rounded-lg bg-white px-4 py-2 text-sm font-bold">Copiar link</button>
                                    @endif
                                    @if ($viaje->fecha_pago)<span class="text-xs font-bold">Plazo pactado: pago a {{ config('trayectia.dias_credito') }} días</span>@endif
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Evidencias --}}
        <section class="rounded-card bg-white p-6 shadow-card sm:p-8">
            <div class="flex items-start justify-between">
                <h2 class="flex items-center gap-2 font-display text-2xl font-bold"><span class="material-symbols-outlined">photo_library</span>Galería de Evidencias Verificadas</h2>
                <span class="text-xs font-bold">{{ $viaje->entregado_at ? '3 archivos satelitales' : 'Sin evidencias aún' }}</span>
            </div>
            @if ($viaje->entregado_at)
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ([['location_on', 'Geocertificado', 'Descarga de '.($viaje->carga ?? 'material'), 'LAT 20.998° N · LONG 89.704° W · '.$viaje->destino, 0], ['verified', 'Sello legible', 'Remisión física #'.(8400 + $viaje->folio).'-B', 'Recibido almacén'.($viaje->aprobado_por ? ' · Firma: '.$viaje->aprobado_por : ''), 2]] as [$icono, $sello, $titulo, $detalle, $min])
                        <figure class="relative flex h-52 flex-col justify-end overflow-hidden rounded-card bg-gradient-to-br from-pino-700 to-pino-900 p-4 text-white">
                            <span class="material-symbols-outlined absolute right-6 top-14 text-7xl text-white/10">{{ $min ? 'description' : 'local_shipping' }}</span>
                            <div class="absolute inset-x-4 top-4 flex justify-between text-xs font-bold">
                                <span class="flex items-center gap-1 rounded bg-black/40 px-2 py-1"><span class="material-symbols-outlined text-sm">{{ $icono }}</span>{{ $sello }}</span>
                                <span class="rounded bg-black/40 px-2 py-1">{{ $viaje->entregado_at->copy()->subMinutes(2 - $min)->format('h:i A') }}</span>
                            </div>
                            <figcaption><p class="font-display font-bold">{{ $titulo }}</p><p class="font-mono text-xs text-white/60">{{ $detalle }}</p></figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif
            <div class="mt-4 rounded-card bg-crema-2 p-4">
                <div class="flex items-center gap-3 rounded-lg bg-white p-4 shadow-card">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-terra-fondo text-terra"><span class="material-symbols-outlined">route</span></span>
                    <div class="flex-1"><p class="font-bold">Ruta: {{ $viaje->origen }} → {{ $viaje->destino }}</p><p class="text-xs font-bold text-muted">{{ $viaje->distancia_km }} km · espera en obra {{ $viaje->espera_hrs }} hrs</p></div>
                    <span class="flex items-center gap-1 text-xs font-bold"><span class="h-2 w-2 rounded-full {{ $viaje->entregado_at ? 'bg-pino' : 'bg-warn' }}"></span>{{ $viaje->entregado_at ? 'Entregado' : 'En tránsito' }}</span>
                </div>
            </div>
        </section>
    </div>

    {{-- Conversación del bot --}}
    <aside class="self-start xl:sticky xl:top-24">
        <p class="mb-3 flex items-center justify-between font-display font-bold"><span class="flex items-center gap-2"><span class="material-symbols-outlined">forum</span>Auditoría de Conversación Bot</span><span class="font-sans text-xs">WhatsApp Hombre-Camión</span></p>
        <div class="rounded-[2.5rem] bg-crema-3 p-3 shadow-panel">
            <div class="overflow-hidden rounded-[2rem] bg-[#0b141a] text-sm text-white">
                <div class="flex items-center gap-3 bg-[#1f2c34] px-4 py-3">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-pino text-xs font-bold">TI</span>
                    <div class="flex-1"><p class="font-bold">TrayectIA Bot 🤖</p><p class="text-xs text-white/60">en línea · Asistente logístico</p></div>
                </div>
                <div class="max-h-[34rem] space-y-3 overflow-y-auto p-4">
                    <p class="mx-auto w-fit rounded bg-[#1f2c34] px-2 py-1 text-[11px] uppercase text-white/60">{{ $creado->translatedFormat('d \d\e F') }}</p>
                    <div class="w-[88%] rounded-lg bg-[#1f2c34] p-3">
                        <p>Hola {{ \Illuminate\Support\Str::before($c['nombre'], ' ') }} 🚛 Tienes nuevo <strong>viaje {{ $viaje->folio_texto }}</strong>.</p>
                        <div class="mt-2 space-y-0.5 rounded bg-black/30 p-2 text-xs">
                            <p>📦 <strong>Carga:</strong> {{ $viaje->carga ?? 'Por confirmar' }}</p>
                            <p>📍 <strong>Origen:</strong> {{ $viaje->origen }}</p>
                            <p>🏁 <strong>Destino:</strong> {{ $viaje->destino }}</p>
                            <p>💵 <strong>Flete:</strong> ${{ number_format($viaje->precio) }} MXN</p>
                        </div>
                        <p class="mt-2">¿Confirmas asignación?</p>
                        <p class="text-right text-[11px] text-white/50">{{ $creado->format('H:i') }}</p>
                    </div>
                    <div class="ml-auto w-[80%] rounded-lg bg-[#005c4b] p-3"><p>✅ Acepto viaje {{ $viaje->folio_texto }}</p><p>Voy al patio a cargar con el {{ \Illuminate\Support\Str::before($u['nombre'], ' ') }}.</p><p class="text-right text-[11px] text-white/60">{{ $creado->copy()->addMinutes(15)->format('H:i') }} ✓✓</p></div>
                    @if ($pasos[2][1])
                        <div class="ml-auto w-[80%] rounded-lg bg-[#005c4b] p-3"><p>Salí de patio {{ $salida->format('H:i') }} con remisión {{ 8400 + $viaje->folio }}-B en mano.</p><p class="text-right text-[11px] text-white/60">{{ $salida->format('H:i') }} ✓✓</p></div>
                        <div class="w-[88%] rounded-lg bg-[#1f2c34] p-3"><p>Registrado en sistema ⏱️. Al llegar a la obra envíanos foto o nota de voz rápida.</p><p class="text-right text-[11px] text-white/50">{{ $salida->copy()->addMinute()->format('H:i') }}</p></div>
                    @endif
                    @if ($viaje->entregado_at)
                        <div class="ml-auto w-[80%] rounded-lg bg-[#005c4b] p-3"><p>🎙️ Nota de voz (0:38): "Ya llegué a {{ $viaje->destino }}, me están descargando."</p><p class="text-right text-[11px] text-white/60">{{ $viaje->entregado_at->format('H:i') }} ✓✓</p></div>
                        <div class="w-[88%] rounded-lg bg-[#1f2c34] p-3"><p>¡Recibido! Expediente completo. Esperando firma del cliente.</p><p class="text-right text-[11px] text-white/50">{{ $viaje->entregado_at->copy()->addMinute()->format('H:i') }}</p></div>
                    @endif
                </div>
                <div class="flex items-center gap-2 bg-[#1f2c34] px-3 py-2"><span class="flex-1 truncate rounded-full bg-[#2a3942] px-4 py-2 text-white/60">Expediente {{ $viaje->entregado_at ? 'completo' : 'en curso' }}…</span><span class="grid h-9 w-9 place-items-center rounded-full bg-terra"><span class="material-symbols-outlined text-base">mic</span></span></div>
            </div>
        </div>
    </aside>
</div>
@endsection
