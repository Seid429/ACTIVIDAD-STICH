@extends('trayectia.layouts.base')

@section('titulo', 'Iniciar sesión')

@section('cuerpo')
<div class="grid min-h-screen lg:grid-cols-2">
    {{-- Panel de marca --}}
    <section class="relative hidden flex-col overflow-hidden bg-gradient-to-br from-pino-900 via-pino to-[#2c3a2e] px-16 py-14 text-white lg:flex">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10"><span class="material-symbols-outlined text-mint">alt_route</span></span>
                <span class="font-display text-3xl font-bold">Trayect<span class="text-terra-suave">IA</span></span>
            </div>
            <span class="flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-bold"><span class="h-2 w-2 rounded-full bg-mint"></span>Yucatán v2.4</span>
        </div>
        <p class="mt-8 inline-flex w-fit items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-xs font-medium uppercase tracking-wide text-white/80">
            <span class="material-symbols-outlined text-base text-terra-suave">verified</span>Acceso exclusivo para transportistas y administradores de flota
        </p>

        <h1 class="mt-16 font-display text-[2.6rem] font-extrabold leading-[1.15] tracking-tight">
            Comprueba el servicio, calcula rentabilidad y <span class="text-terra-suave">evita atrasos</span> en el cobro.
        </h1>
        <p class="mt-5 max-w-lg text-lg text-white/60">Plataforma diseñada para el hombre-camión en el circuito Mérida – Progreso – Umán – Caucel. Cotizaciones rápidas vía WhatsApp y evidencias de flete al instante.</p>

        <div class="mt-10 rounded-card bg-white/5 p-6">
            <div class="flex items-center justify-between text-sm">
                <span class="flex items-center gap-2 font-bold uppercase tracking-wide"><span class="material-symbols-outlined text-base">hub</span>Corredor logístico activo</span>
                <span class="text-xs font-bold text-white/50">Progreso – Caucel (36.4 km)</span>
            </div>
            <svg viewBox="0 0 440 110" class="mt-4 w-full" aria-hidden="true">
                <path d="M30 60 C 120 30, 170 80, 220 85 S 330 40, 410 60" fill="none" stroke="#7fa497" stroke-width="3" stroke-dasharray="6 6" opacity=".6"/>
                <path d="M30 60 C 120 30, 170 80, 220 85" fill="none" stroke="#FE8C65" stroke-width="3"/>
                <circle cx="30" cy="60" r="7" fill="#C1ECDD"/><circle cx="290" cy="58" r="5" fill="#7fa497"/><circle cx="410" cy="60" r="7" fill="#FE8C65"/>
                <circle cx="220" cy="85" r="12" fill="#FE8C65"/><text x="220" y="89" text-anchor="middle" font-size="12" fill="#fff">🚚</text>
                <text x="25" y="88" fill="#fff" font-size="10" font-weight="700">Progreso</text>
                <text x="268" y="44" fill="#fff" font-size="9">Periférico Poniente</text>
                <text x="372" y="88" fill="#fff" font-size="10" font-weight="700">Caucel Indust.</text>
            </svg>
            <div class="mt-4 grid grid-cols-3 gap-2">
                @foreach (['Diésel Est.' => '14.2 L', 'Casetas' => '$0.00', 'Margen Neto' => '+31.8%'] as $k => $v)
                    <div class="rounded-lg bg-white/10 px-3 py-2"><p class="text-xs font-bold text-white/60">{{ $k }}</p><p class="font-display text-lg font-bold tabular">{{ $v }}</p></div>
                @endforeach
            </div>
        </div>

        <p class="mt-auto flex items-center gap-3 pt-10 text-sm text-white/60">
            <span class="grid h-8 w-8 place-items-center rounded-full bg-white/10"><span class="material-symbols-outlined text-base text-terra-suave">local_shipping</span></span>
            Más de <strong class="text-white">180 viajes semanales</strong> registrados en el tramo Umán–Mérida.
        </p>
    </section>

    {{-- Formulario --}}
    <section class="flex items-center justify-center px-4 py-10 sm:px-10">
        <div class="w-full max-w-lg rounded-[1.25rem] bg-white p-8 shadow-panel sm:p-10">
            <p class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-terra"><span class="material-symbols-outlined text-base">shield_lock</span>Acceso seguro de operadores</p>
            <h2 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-pino">Bienvenido de nuevo</h2>
            <p class="mt-2 text-muted">Ingresa a tu cuenta de TrayectIA para gestionar tus fletes, calcular utilidades y registrar evidencias.</p>

            <form method="POST" action="{{ route('login.demo') }}" class="mt-7 flex items-center gap-3 rounded-card bg-crema-2 p-4">
                @csrf
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-pino font-display font-bold text-white">DJ</span>
                <div class="min-w-0 flex-1">
                    <p class="font-bold">Don Jorge Peniche</p>
                    <p class="truncate text-xs text-muted">Torton Kenworth T370 · Mérida</p>
                </div>
                <button class="flex items-center gap-1 rounded-ctrl bg-pino px-4 py-2.5 text-sm font-bold text-white hover:bg-pino-700">
                    <span class="material-symbols-outlined text-base">bolt</span>Entrar como demo
                </button>
            </form>

            <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-5" novalidate>
                @csrf
                <div>
                    <label for="usuario" class="mb-2 block text-sm font-bold">Correo o número de WhatsApp</label>
                    <div class="relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">badge</span>
                        <input id="usuario" name="usuario" value="{{ old('usuario') }}" required autofocus autocomplete="username"
                               placeholder="donjorge@transportes.mx o 999 123 4567" class="campo con-icono @error('usuario') invalido @enderror">
                    </div>
                    @error('usuario')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@else<p class="mt-1.5 text-xs text-muted">Acepta celular a 10 dígitos o correo electrónico.</p>@enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-bold">Contraseña</label>
                    <div class="relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">lock</span>
                        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••••••"
                               class="campo con-icono pr-12 @error('password') invalido @enderror">
                        <button type="button" data-ver="password" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-pino" aria-label="Mostrar contraseña">
                            <span class="material-symbols-outlined">visibility</span>
                        </button>
                    </div>
                    @error('password')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 font-medium"><input type="checkbox" name="recordar" class="h-4 w-4 accent-pino" checked>¿Recordarme en este equipo?</label>
                    <a href="https://wa.me/529991234567?text=Olvid%C3%A9%20mi%20contrase%C3%B1a%20de%20TrayectIA" target="_blank" class="font-bold text-terra hover:underline">¿Olvidaste tu contraseña?</a>
                </div>
                <button class="flex h-12 w-full items-center justify-center gap-2 rounded-ctrl bg-terra font-display text-lg font-bold text-white shadow-card transition hover:bg-[#AD4F30] active:scale-[.98]">
                    Iniciar sesión <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </form>

            <div class="mt-7 rounded-card bg-crema-2 px-6 py-5 text-center">
                <p>¿Aún no tienes cuenta? <a href="{{ route('registro') }}" class="font-bold text-pino hover:underline">Regístrate gratis como transportista</a></p>
                <p class="mt-1 text-xs text-muted">Incluye 14 días de estimaciones de diésel y recibos de flete ilimitados.</p>
            </div>

            <div class="mt-6 flex justify-center gap-6 text-xs font-bold text-muted">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">verified_user</span>CFDI 4.0 Carta Porte</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-base">lock</span>Cifrado SAT 256-bit</span>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
@include('trayectia.partials.ver-password')
@endpush
