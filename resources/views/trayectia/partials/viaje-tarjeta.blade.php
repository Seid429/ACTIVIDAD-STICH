{{-- Tarjeta de un viaje con su acción según el estado. Parámetro: $viaje --}}
@php
    $linkAprobacion = route('aprobacion.cliente', $viaje->token_aprobacion);
    $whatsapp = 'https://wa.me/?text='.rawurlencode("Hola, le comparto la recepción del viaje {$viaje->folio_texto} ({$viaje->origen} → {$viaje->destino}). Por favor confirme aquí: {$linkAprobacion}");
    $progreso = match ($viaje->estado) {
        'en_ruta' => ['Avance estimado', $viaje->avance.'%', $viaje->avance, 'bg-terra'],
        'entregado' => ['Firma digital cliente', 'Pendiente', 100, 'bg-pino'],
        'aprobado' => ['Facturación SAT', 'Por emitir', 100, 'bg-pino'],
        'facturado' => ['Pago del cliente', $viaje->dias_para_pago < 0 ? 'Atrasado '.abs($viaje->dias_para_pago).' d' : 'Vence en '.$viaje->dias_para_pago.' d', 100, $viaje->dias_para_pago < 0 ? 'bg-bad' : 'bg-warn'],
        default => ['Cobrado', $viaje->cobrado_at?->format('d/m'), 100, 'bg-ok'],
    };
@endphp
<article class="flex flex-col gap-5 rounded-card border border-muted/15 bg-white p-5 shadow-card xl:flex-row xl:items-center">
    <a href="{{ route('viajes.show', $viaje) }}" class="flex flex-1 gap-4">
        <span class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-xl {{ $viaje->estado === 'en_ruta' ? 'bg-pino text-white' : 'bg-crema-2 text-pino' }}">
            <span class="text-[10px] font-bold uppercase leading-none">Viaje</span>
            <span class="font-display text-lg font-extrabold leading-tight">{{ $viaje->folio_texto }}</span>
        </span>
        <span class="min-w-0 flex-1">
            <span class="flex flex-wrap items-center gap-2">
                <span class="font-display text-xl font-bold">{{ $viaje->origen }} <span class="material-symbols-outlined text-base">arrow_forward</span> {{ $viaje->destino }}</span>
                <span class="rounded bg-crema-2 px-2 py-0.5 text-xs font-medium text-muted">{{ $viaje->cliente }}</span>
                <span class="rounded-full px-2 py-0.5 text-xs font-bold {{ $viaje->estado_datos['clase'] }}">{{ $viaje->estado === 'en_ruta' ? '● En ruta ('.$viaje->avance.'%)' : $viaje->estado_datos['etiqueta'] }}</span>
            </span>
            <span class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base text-muted">person</span>{{ $viaje->chofer_datos['nombre'] }}</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base text-muted">local_shipping</span>{{ \Illuminate\Support\Str::before($viaje->unidad_datos['nombre'], ' ') }} (Placas {{ $viaje->unidad_datos['placas'] }})</span>
                @if ($viaje->carga)<span class="flex items-center gap-1"><span class="material-symbols-outlined text-base text-muted">inventory_2</span>{{ $viaje->carga }}</span>@endif
            </span>
        </span>
    </a>

    <div class="w-full xl:w-44">
        <div class="flex justify-between text-xs font-bold"><span>{{ $progreso[0] }}</span><span class="{{ $viaje->estado === 'entregado' ? 'text-terra' : '' }}">{{ $progreso[1] }}</span></div>
        <div class="mt-2 h-1.5 rounded-full bg-crema-3"><div class="h-full rounded-full {{ $progreso[3] }}" style="width: {{ $progreso[2] }}%"></div></div>
    </div>

    <div class="flex items-center justify-between gap-4 xl:w-64">
        <div class="text-center">
            <p class="text-xs font-bold text-muted">Tarifa flete</p>
            <p class="font-display text-2xl font-extrabold tabular">${{ number_format($viaje->precio) }}</p>
            <p class="text-xs text-muted">MXN</p>
        </div>
        @switch($viaje->estado)
            @case('entregado')
                <a href="{{ $whatsapp }}" target="_blank" class="flex items-center gap-1 rounded-ctrl bg-crema-2 px-4 py-3 text-sm font-bold hover:bg-crema-3"><span class="material-symbols-outlined text-base">ios_share</span>Pedir firma</a>
                @break
            @case('aprobado')
                <form method="POST" action="{{ route('viajes.avanzar', $viaje) }}">@csrf
                    <button class="flex items-center gap-1 rounded-ctrl bg-pino px-4 py-3 text-sm font-bold text-white hover:bg-pino-700"><span class="material-symbols-outlined text-base">receipt_long</span>Facturar</button>
                </form>
                @break
            @case('facturado')
                <form method="POST" action="{{ route('viajes.avanzar', $viaje) }}">@csrf
                    <button class="flex items-center gap-1 rounded-ctrl bg-pino px-4 py-3 text-sm font-bold text-white hover:bg-pino-700"><span class="material-symbols-outlined text-base">payments</span>Cobrar flete</button>
                </form>
                @break
            @default
                <a href="{{ route('viajes.show', $viaje) }}" class="flex items-center gap-1 rounded-ctrl bg-crema-2 px-4 py-3 text-sm font-bold hover:bg-crema-3">Ver hoja<span class="material-symbols-outlined text-base">chevron_right</span></a>
        @endswitch
    </div>
</article>
