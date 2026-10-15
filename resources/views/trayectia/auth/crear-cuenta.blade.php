@extends('trayectia.layouts.base')

@section('titulo', 'Crear cuenta')

@php
    // Si el error está en un campo del paso 2, el formulario se reabre en ese paso.
    $paso = $errors->hasAny(['empresa', 'ciudad', 'unidad_principal']) && ! $errors->hasAny(['name', 'email', 'whatsapp', 'password']) ? 2 : 1;
    $beneficios = [
        ['calculate', 'Ve tu ganancia antes de aceptar un viaje', 'Calcula diésel, casetas SCT y viáticos en tiempo real con margen neto garantizado.'],
        ['add_a_photo', 'Evidencia de cada entrega en un solo lugar', 'Fotos satelitales, sellos y remisiones digitales directas al cliente sin perder papeles.'],
        ['send_time_extension', 'Menos cobros atrasados', 'Tu cliente aprueba al instante por link directo de WhatsApp y agilizas la facturación.'],
    ];
@endphp

@section('cuerpo')
<div class="grid min-h-screen lg:grid-cols-[5fr_7fr]">
    <section class="hidden flex-col bg-gradient-to-br from-pino-900 via-pino to-pino-700 px-12 py-12 text-white lg:flex">
        <div class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10"><span class="material-symbols-outlined text-mint">local_shipping</span></span>
            <span class="font-display text-3xl font-bold">Trayect<span class="text-terra-suave">IA</span></span>
        </div>
        <p class="mt-14 inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold uppercase tracking-wider"><span class="h-2 w-2 rounded-full bg-terra-suave"></span>Plataforma peninsular de carga</p>
        <h1 class="mt-4 font-display text-[2.4rem] font-extrabold leading-tight tracking-tight">Únete a la red de transportistas digitales de la Península</h1>
        <p class="mt-4 text-white/60">Diseñado especialmente para el hombre-camión y flotas regionales en el corredor Mérida – Progreso – Umán.</p>

        <div class="mt-12 space-y-4">
            @foreach ($beneficios as [$icono, $titulo, $texto])
                <div class="flex gap-4 rounded-card bg-white/10 p-5">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-white/10"><span class="material-symbols-outlined">{{ $icono }}</span></span>
                    <div><p class="font-display text-lg font-bold">{{ $titulo }}</p><p class="mt-1 text-sm text-white/60">{{ $texto }}</p></div>
                </div>
            @endforeach
        </div>

        <figure class="mt-auto border-t border-white/10 pt-8">
            <blockquote class="flex gap-3 text-lg italic"><span class="font-display text-2xl font-bold not-italic text-terra-suave">99</span>"Desde que uso TrayectIA sé exactamente cuánto le gano a cada viaje a Progreso."</blockquote>
            <figcaption class="mt-4 flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-full bg-terra font-bold">J</span>
                <span><span class="block font-bold">Don Jorge Villanueva</span><span class="text-xs font-bold text-white/50">Transportes Don Jorge · Mérida, Yuc.</span></span>
            </figcaption>
        </figure>
    </section>

    <section class="flex flex-col items-center justify-center px-4 py-10 sm:px-10">
        <form id="registro" method="POST" action="{{ route('registro.store') }}" class="w-full max-w-xl rounded-[1.25rem] bg-white p-8 shadow-panel sm:p-10" novalidate>
            @csrf
            <div class="flex items-center justify-between">
                <span id="paso-etiqueta" class="rounded-full bg-terra-fondo px-3 py-1 text-sm font-bold text-terra"></span>
                <span id="paso-porcentaje" class="text-sm font-bold"></span>
            </div>
            <div class="mt-4 h-1.5 rounded-full bg-crema-3"><div id="paso-barra" class="h-full rounded-full bg-terra-suave transition-all"></div></div>

            <div class="mt-6 grid grid-cols-2 gap-1 rounded-ctrl bg-crema-2 p-1">
                @foreach ([1 => 'Tus datos', 2 => 'Tu negocio'] as $n => $texto)
                    <button type="button" data-ir="{{ $n }}" class="pestana flex items-center justify-center gap-2 rounded-lg py-2.5 text-sm font-bold">
                        <span class="grid h-5 w-5 place-items-center rounded-full text-[11px]">{{ $n }}</span>{{ $texto }}
                    </button>
                @endforeach
            </div>

            {{-- Paso 1 --}}
            <div data-paso="1" class="mt-6 space-y-5">
                @include('trayectia.partials.campo', ['nombre' => 'name', 'etiqueta' => 'Nombre completo', 'icono' => 'person', 'placeholder' => 'Ej. Jorge Alberto Villanueva Pech', 'auto' => 'name'])
                @include('trayectia.partials.campo', ['nombre' => 'email', 'etiqueta' => 'Correo electrónico', 'icono' => 'mail', 'placeholder' => 'jorge@transportesdonjorge.mx', 'tipo' => 'email', 'auto' => 'email'])
                <div>
                    <label for="whatsapp" class="mb-2 block text-sm font-bold">Número de WhatsApp para avisos de fletes</label>
                    <div class="flex gap-2">
                        <span class="flex h-12 items-center gap-1 rounded-ctrl bg-crema-2 px-3 text-sm font-bold">🇲🇽 +52</span>
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">chat</span>
                            <input id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" inputmode="numeric" maxlength="12" placeholder="999 456 7890" autocomplete="tel-national" class="campo con-icono @error('whatsapp') invalido @enderror">
                        </div>
                    </div>
                    @error('whatsapp')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">lock</span>
                        <input id="password" name="password" type="password" placeholder="Mínimo 8 caracteres" autocomplete="new-password" class="campo con-icono pr-12 @error('password') invalido @enderror">
                        <button type="button" data-ver="password" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted" aria-label="Mostrar contraseña"><span class="material-symbols-outlined">visibility</span></button>
                    </div>
                    <div class="mt-2 flex justify-between text-xs font-bold"><span>Nivel de seguridad:</span><span id="fuerza-texto" class="text-muted">Introduce una clave</span></div>
                    <div class="mt-1.5 grid grid-cols-3 gap-1.5">@for ($i = 0; $i < 3; $i++)<span class="fuerza h-1 rounded-full bg-crema-3"></span>@endfor</div>
                    @error('password')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
                @include('trayectia.partials.campo', ['nombre' => 'password_confirmation', 'etiqueta' => 'Confirmar contraseña', 'icono' => 'lock_reset', 'placeholder' => 'Repite tu contraseña', 'tipo' => 'password', 'auto' => 'new-password'])

                <button type="button" data-ir="2" class="flex h-12 w-full items-center justify-center gap-2 rounded-ctrl bg-terra font-display text-lg font-bold text-white shadow-card hover:bg-[#AD4F30] active:scale-[.98]">
                    Continuar a datos del negocio <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>

            {{-- Paso 2 --}}
            <div data-paso="2" class="mt-6 hidden space-y-5">
                @include('trayectia.partials.campo', ['nombre' => 'empresa', 'etiqueta' => 'Nombre de tu negocio', 'icono' => 'storefront', 'placeholder' => 'Ej. Transportes Don Jorge'])
                @include('trayectia.partials.campo', ['nombre' => 'ciudad', 'etiqueta' => 'Ciudad base', 'icono' => 'location_on', 'placeholder' => 'Mérida, Yucatán'])
                <div>
                    <p class="mb-2 text-sm font-bold">Unidad principal</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach (['Torton', 'Rabón', "Trailer 53'"] as $tipo)
                            <label class="cursor-pointer">
                                <input type="radio" name="unidad_principal" value="{{ $tipo }}" class="peer sr-only" @checked(old('unidad_principal', 'Torton') === $tipo)>
                                <span class="block rounded-ctrl bg-crema-2 py-3 text-center font-bold peer-checked:bg-pino peer-checked:text-white">{{ $tipo }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('unidad_principal')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
                <div class="flex gap-3">
                    <button type="button" data-ir="1" class="h-12 rounded-ctrl border-[1.5px] border-pino px-5 font-display font-bold text-pino hover:bg-pino/5">Atrás</button>
                    <button class="flex h-12 flex-1 items-center justify-center gap-2 rounded-ctrl bg-terra font-display text-lg font-bold text-white shadow-card hover:bg-[#AD4F30] active:scale-[.98]">
                        Crear mi cuenta <span class="material-symbols-outlined">check_circle</span>
                    </button>
                </div>
            </div>

            <p class="mt-8 border-t border-crema-3 pt-6 text-center">¿Ya tienes cuenta activa? <a href="{{ route('login') }}" class="font-display text-lg font-bold text-terra hover:underline">Inicia sesión</a></p>
        </form>
        <p class="mt-6 flex items-center gap-2 text-center text-xs font-bold text-muted"><span class="material-symbols-outlined text-base">verified_user</span>Sin contratos forzosos. Activación gratuita para operadores de Yucatán y Quintana Roo.</p>
    </section>
</div>
@endsection

@push('scripts')
@include('trayectia.partials.ver-password')
<script>
    (function () {
        var form = document.getElementById('registro');

        function irA(paso) {
            form.querySelectorAll('[data-paso]').forEach(function (p) { p.classList.toggle('hidden', p.dataset.paso != paso); });
            form.querySelectorAll('.pestana').forEach(function (b) {
                var activo = b.dataset.ir == paso;
                b.classList.toggle('bg-pino', activo); b.classList.toggle('text-white', activo);
                b.querySelector('span').className = 'grid h-5 w-5 place-items-center rounded-full text-[11px] ' + (activo ? 'bg-terra-suave text-white' : 'bg-crema-3');
            });
            document.getElementById('paso-etiqueta').textContent = 'Paso ' + paso + ' de 2: ' + (paso == 1 ? 'Tus datos personales' : 'Datos de tu negocio');
            document.getElementById('paso-porcentaje').textContent = (paso * 50) + '%';
            document.getElementById('paso-barra').style.width = (paso * 50) + '%';
        }

        form.querySelectorAll('[data-ir]').forEach(function (b) {
            b.addEventListener('click', function () {
                // Antes de avanzar, valida en el navegador los campos del paso 1.
                if (b.dataset.ir == 2) {
                    var faltan = ['name', 'email', 'whatsapp', 'password', 'password_confirmation'].filter(function (n) { return !form.elements[n].value.trim(); });
                    faltan.forEach(function (n) { form.elements[n].classList.add('invalido'); });
                    if (faltan.length) { form.elements[faltan[0]].focus(); return; }
                }
                irA(b.dataset.ir);
            });
        });
        form.querySelectorAll('input').forEach(function (i) { i.addEventListener('input', function () { i.classList.remove('invalido'); }); });

        // Indicador de fuerza de la contraseña.
        document.getElementById('password').addEventListener('input', function (e) {
            var v = e.target.value, n = 0;
            if (v.length >= 8) n++;
            if (/[A-Z]/.test(v) && /[a-z]/.test(v)) n++;
            if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v) || v.length >= 12) n++;
            var colores = ['bg-bad', 'bg-warn', 'bg-ok'];
            document.querySelectorAll('.fuerza').forEach(function (s, i) { s.className = 'fuerza h-1 rounded-full ' + (i < n ? colores[n - 1] : 'bg-crema-3'); });
            document.getElementById('fuerza-texto').textContent = v ? ['Muy débil', 'Débil', 'Aceptable', 'Segura'][n] : 'Introduce una clave';
        });

        irA({{ $paso }});
    })();
</script>
@endpush
