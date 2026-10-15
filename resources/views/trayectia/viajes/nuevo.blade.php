@extends('trayectia.layouts.app')

@section('titulo', 'Nuevo viaje y cotizador')

@php
    $cfgCotizador = [
        'diesel' => config('trayectia.precio_diesel'),
        'desgaste' => config('trayectia.desgaste_km'),
        'unidades' => config('trayectia.unidades'),
        'choferes' => config('trayectia.choferes'),
    ];
@endphp

@section('contenido')
<form method="POST" action="{{ route('viajes.store') }}" id="cotizador" class="grid gap-8 xl:grid-cols-[minmax(0,1fr)_26rem]" novalidate>
    @csrf
    <div>
        <p class="flex items-center gap-3 text-xs">
            <span class="flex items-center gap-1 rounded-full bg-crema-3 px-3 py-1 font-bold uppercase tracking-wider"><span class="material-symbols-outlined text-sm">local_shipping</span>Despacho inteligente</span>
            <span class="text-muted">Corredor Peninsular Mérida · Folio #{{ str_pad($siguienteFolio, 3, '0', STR_PAD_LEFT) }}</span>
        </p>
        <h1 class="mt-3 font-display text-4xl font-extrabold leading-tight tracking-tight text-pino">Registrar nuevo viaje y cotizar rentabilidad</h1>
        <p class="mt-2 max-w-2xl text-muted">Calcula en tiempo real el consumo de diésel peninsular, viáticos y desgaste antes de comprometer tu camión.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-card border border-bad/30 bg-terra-fondo px-5 py-4 text-[#7E2C0C]">
                <p class="font-bold">Revisa los datos del viaje:</p>
                <ul class="mt-1 list-inside list-disc text-sm">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="mt-8 space-y-6 rounded-card bg-white p-6 shadow-card sm:p-8">
            <div>
                <label for="cliente" class="mb-2 flex justify-between text-sm font-bold">Empresa cliente <span class="text-xs text-terra">Cliente recurrente</span></label>
                <input id="cliente" name="cliente" list="clientes" value="{{ old('cliente', config('trayectia.clientes')[0]) }}" class="campo @error('cliente') invalido @enderror">
                <datalist id="clientes">@foreach (config('trayectia.clientes') as $c)<option value="{{ $c }}">@endforeach</datalist>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="origen" class="mb-2 flex items-center gap-1 text-sm font-bold"><span class="material-symbols-outlined text-base text-terra">radio_button_checked</span>Origen de carga</label>
                    <input id="origen" name="origen" value="{{ old('origen', 'Mérida - Periférico Oriente') }}" class="campo @error('origen') invalido @enderror">
                </div>
                <div>
                    <label for="destino" class="mb-2 flex items-center gap-1 text-sm font-bold"><span class="material-symbols-outlined text-base">location_on</span>Destino de entrega</label>
                    <input id="destino" name="destino" value="{{ old('destino', 'Caucel - Obra Fracc. Gran Santa Fe') }}" class="campo @error('destino') invalido @enderror">
                </div>
            </div>

            <div>
                <label for="carga" class="mb-2 block text-sm font-bold">Carga</label>
                <input id="carga" name="carga" value="{{ old('carga') }}" placeholder="Ej. 14 Ton Cemento Cruz Azul" class="campo">
            </div>

            <div class="grid gap-5 sm:grid-cols-3">
                <div><label for="fecha" class="mb-2 block text-sm font-bold">Fecha estimada</label><input id="fecha" type="date" name="fecha" value="{{ old('fecha', today()->toDateString()) }}" class="campo @error('fecha') invalido @enderror"></div>
                <div><label for="hora" class="mb-2 block text-sm font-bold">Hora de carga</label><input id="hora" type="time" name="hora" value="{{ old('hora', '07:30') }}" class="campo @error('hora') invalido @enderror"></div>
                <div>
                    <label for="espera_hrs" class="mb-2 flex justify-between text-sm font-bold">Espera obra <span class="text-xs text-muted"><span data-espera>1.5</span> hrs</span></label>
                    <input id="espera_hrs" type="number" step="0.5" min="0" name="espera_hrs" value="{{ old('espera_hrs', 1.5) }}" class="campo">
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <fieldset class="min-w-0">
                    <legend class="mb-2 text-sm font-bold">Unidad asignada</legend>
                    <div class="space-y-3">
                        @foreach (config('trayectia.unidades') as $clave => $u)
                            <label class="block cursor-pointer">
                                <input type="radio" name="unidad" value="{{ $clave }}" class="peer sr-only" @checked(old('unidad', 'torton') === $clave)>
                                <span class="flex items-center gap-3 rounded-card bg-crema-2 p-4 peer-checked:bg-pino peer-checked:text-white [&_.check]:opacity-30 peer-checked:[&_.check]:opacity-100">
                                    <span class="min-w-0 flex-1"><span class="block truncate font-display text-lg font-bold">{{ $u['nombre'] }}</span><span class="block text-xs font-bold opacity-70">{{ $u['capacidad'] }} · Rendimiento {{ $u['rendimiento'] }} km/L</span></span>
                                    <span class="check material-symbols-outlined">check_circle</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <fieldset class="min-w-0">
                    <legend class="mb-2 text-sm font-bold">Chofer asignado</legend>
                    <div class="space-y-3">
                        @foreach (config('trayectia.choferes') as $clave => $c)
                            <label class="block cursor-pointer">
                                <input type="radio" name="chofer" value="{{ $clave }}" class="peer sr-only" @checked(old('chofer', 'luis') === $clave)>
                                <span class="flex items-center gap-3 rounded-card bg-crema-2 p-4 peer-checked:bg-pino peer-checked:text-white [&_.check]:opacity-30 peer-checked:[&_.check]:opacity-100">
                                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-mint text-sm font-bold text-pino">{{ $c['iniciales'] }}</span>
                                    <span class="min-w-0 flex-1"><span class="block font-display text-lg font-bold">{{ $c['nombre'] }}</span><span class="block text-xs font-bold opacity-70">{{ $c['tipo'] }} · ${{ number_format($c['tarifa'], 2) }}</span></span>
                                    <span class="check material-symbols-outlined">check_circle</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="rounded-card bg-crema-2 p-5">
                    <label for="distancia_km" class="flex flex-wrap items-baseline justify-between gap-x-2 text-sm font-bold"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">straighten</span>Distancia estimada (km)</span><span class="font-display text-lg"><span data-km></span> km</span></label>
                    <div class="mt-3 flex items-center rounded-ctrl bg-white px-4"><input id="distancia_km" type="number" min="1" step="0.1" name="distancia_km" value="{{ old('distancia_km', 38) }}" class="h-12 w-full bg-transparent font-display text-lg font-bold outline-none"><span class="text-sm font-bold">km viaje</span></div>
                    <input type="range" min="1" max="400" step="1" data-espejo="distancia_km" class="mt-4 w-full" aria-label="Distancia">
                </div>
                <div class="rounded-card bg-crema-2 p-5">
                    <label for="precio" class="flex flex-wrap items-baseline justify-between gap-x-2 text-sm font-bold"><span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">payments</span>Precio ofrecido</span><span class="font-display text-lg text-terra">$<span data-precio></span> MXN</span></label>
                    <div class="mt-3 flex items-center rounded-ctrl bg-white px-4"><span class="font-display text-lg font-bold text-terra">$</span><input id="precio" type="number" min="1" step="50" name="precio" value="{{ old('precio', 4500) }}" class="h-12 w-full bg-transparent px-2 font-display text-lg font-bold text-terra outline-none"><span class="text-sm font-bold">MXN</span></div>
                    <input type="range" min="500" max="30000" step="50" data-espejo="precio" class="mt-4 w-full" aria-label="Precio">
                </div>
            </div>

            <div class="flex flex-col gap-4 rounded-card bg-crema-3 p-5 sm:flex-row sm:items-center">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/70"><span class="material-symbols-outlined">alt_route</span></span>
                <div class="flex-1">
                    <p class="font-display text-lg font-bold">Casetas en la ruta</p>
                    <p class="text-xs font-bold text-muted">Vía Periférico Poniente sin casetas SCT. Captura el monto si tu ruta usa autopista.</p>
                </div>
                <div class="flex items-center rounded-ctrl bg-white px-4"><span class="font-bold">$</span><input type="number" min="0" step="1" name="casetas" id="casetas" value="{{ old('casetas', 0) }}" class="h-11 w-24 bg-transparent px-2 font-bold outline-none" aria-label="Casetas"></div>
            </div>
        </div>
    </div>

    {{-- Simulación en vivo --}}
    <aside class="self-start rounded-[1.25rem] bg-crema-2 p-6 shadow-panel xl:sticky xl:top-24">
        <div class="flex items-start justify-between">
            <div><p class="text-xs font-bold uppercase tracking-wider">Simulación en vivo</p><p class="font-display text-xl font-bold">Desglose de costos operativos</p></div>
            <span class="grid h-9 w-9 place-items-center rounded-full bg-white"><span class="material-symbols-outlined">analytics</span></span>
        </div>

        <dl class="mt-5 divide-y divide-crema-3 rounded-card bg-white px-5">
            <div class="flex justify-between gap-3 py-4"><dt><span class="flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">local_gas_station</span>Diésel estimado</span><span class="text-xs text-muted" data-diesel-detalle></span></dt><dd class="font-display font-bold tabular" data-diesel></dd></div>
            <div class="flex justify-between gap-3 py-4"><dt><span class="flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">toll</span>Casetas</span><span class="text-xs text-muted" data-casetas-detalle></span></dt><dd class="font-display font-bold tabular" data-casetas></dd></div>
            <div class="flex justify-between gap-3 py-4"><dt><span class="flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">badge</span>Pago y viático del chofer</span><span class="text-xs text-muted" data-chofer-detalle></span></dt><dd class="font-display font-bold tabular" data-viatico></dd></div>
            <div class="flex justify-between gap-3 py-4"><dt><span class="flex items-center gap-1 font-bold"><span class="material-symbols-outlined text-base">build</span>Desgaste / mantenimiento</span><span class="text-xs text-muted">Llantas + aceite + amortización</span></dt><dd class="font-display font-bold tabular" data-desgaste></dd></div>
            <div class="flex justify-between gap-3 py-4 font-bold"><dt class="uppercase">Costo operativo total</dt><dd class="font-display tabular" data-total></dd></div>
        </dl>

        <div class="mt-5 rounded-card border-l-4 border-mint bg-white p-5">
            <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider">Ganancia líquida estimada <span class="rounded-full bg-crema-2 px-2.5 py-1 normal-case tracking-normal" data-margen></span></div>
            <p class="mt-3 font-display text-5xl font-extrabold tracking-tight tabular" data-ganancia></p>
            <p class="mt-4 flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-bold" data-veredicto></p>
        </div>

        <div class="mt-5">
            <div class="flex justify-between text-xs font-bold"><span data-barra-costo></span><span data-barra-ganancia></span></div>
            <div class="mt-2 flex h-3 overflow-hidden rounded-full bg-crema-3"><div class="bg-pino" data-barra-ancho></div><div class="flex-1 bg-terra-suave"></div></div>
            <div class="mt-2 flex gap-4 text-xs font-bold"><span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-pino"></span>Gastos fijos/diésel</span><span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-terra-suave"></span>Dinero libre</span></div>
        </div>

        <button class="mt-6 flex h-14 w-full items-center justify-center gap-2 rounded-ctrl bg-terra font-display text-lg font-bold text-white shadow-card hover:bg-[#AD4F30] active:scale-[.98]">
            <span class="material-symbols-outlined">check_circle</span>Aceptar y asignar viaje
        </button>
        <p class="mt-4 flex gap-2 text-xs font-bold text-muted"><span class="material-symbols-outlined text-base">info</span>Basado en tus costos configurados: diésel ${{ number_format(config('trayectia.precio_diesel'), 2) }}/L y desgaste ${{ config('trayectia.desgaste_km') }}/km.</p>
    </aside>
</form>
@endsection

@push('scripts')
<script>
    (function () {
        // Misma fórmula que App\Models\Viaje::cotizar().
        var cfg = @json($cfgCotizador);
        var form = document.getElementById('cotizador');
        var $ = function (s) { return form.querySelector(s); };
        var dinero = function (n) { return '$' + n.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); };

        function recalcular() {
            var u = cfg.unidades[form.unidad.value], c = cfg.choferes[form.chofer.value];
            var km = parseFloat(form.distancia_km.value) || 0, precio = parseFloat(form.precio.value) || 0, casetas = parseFloat(form.casetas.value) || 0;
            var diesel = Math.round(km / u.rendimiento * cfg.diesel * 100) / 100;
            var desgaste = Math.round(km * cfg.desgaste * 100) / 100;
            var total = diesel + casetas + c.tarifa + desgaste, ganancia = precio - total;
            var margen = precio > 0 ? Math.round(ganancia / precio * 100) : 0;

            $('[data-km]').textContent = km; $('[data-precio]').textContent = precio.toLocaleString('es-MX'); $('[data-espera]').textContent = form.espera_hrs.value;
            $('[data-diesel]').textContent = dinero(diesel) + ' MXN';
            $('[data-diesel-detalle]').textContent = km + ' km / ' + u.rendimiento + ' km/L × $' + cfg.diesel.toFixed(2) + '/L';
            $('[data-casetas]').textContent = dinero(casetas) + ' MXN';
            $('[data-casetas-detalle]').textContent = casetas ? 'Autopista / casetas SCT' : 'Vía libre periférico';
            $('[data-viatico]').textContent = dinero(c.tarifa) + ' MXN';
            $('[data-chofer-detalle]').textContent = c.nombre + ' (' + c.tipo + ')';
            $('[data-desgaste]').textContent = dinero(desgaste) + ' MXN';
            $('[data-total]').textContent = dinero(total) + ' MXN';
            $('[data-ganancia]').innerHTML = dinero(ganancia) + ' <span class="font-sans text-sm font-bold">MXN</span>';
            $('[data-ganancia]').className = 'mt-3 font-display text-5xl font-extrabold tracking-tight tabular ' + (ganancia < 0 ? 'text-bad' : 'text-pino');
            $('[data-margen]').textContent = margen + '% de margen';

            var v = margen > 21 ? ['bg-mint-claro text-ok', 'check_circle', '¡Conviene! Excelente margen operativo']
                  : margen >= 10 ? ['bg-warn/20 text-[#7a5a12]', 'warning', 'Margen ajustado (' + margen + '%). Negocia un poco más.']
                  : ['bg-bad/10 text-bad', 'error', 'Pierdes dinero (' + margen + '%). No conviene este flete.'];
            $('[data-veredicto]').className = 'mt-4 flex items-center gap-2 rounded-lg px-4 py-3 text-sm font-bold ' + v[0];
            $('[data-veredicto]').innerHTML = '<span class="material-symbols-outlined">' + v[1] + '</span>' + v[2];

            var pc = precio > 0 ? Math.min(100, total / precio * 100) : 100;
            $('[data-barra-ancho]').style.width = pc + '%';
            $('[data-barra-costo]').textContent = 'Costo ($' + Math.round(total).toLocaleString('es-MX') + ')';
            $('[data-barra-ganancia]').textContent = 'Ganancia neta ($' + Math.round(ganancia).toLocaleString('es-MX') + ')';
        }

        // Los sliders reflejan y controlan los campos numéricos.
        form.querySelectorAll('[data-espejo]').forEach(function (r) {
            var campo = form.elements[r.dataset.espejo];
            r.value = campo.value;
            r.addEventListener('input', function () { campo.value = r.value; recalcular(); });
            campo.addEventListener('input', function () { r.value = campo.value; });
        });
        form.addEventListener('input', recalcular);
        form.addEventListener('change', recalcular);
        recalcular();
    })();
</script>
@endpush
