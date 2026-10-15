@extends('trayectia.layouts.base')

@section('titulo', 'Cerrar sesión')

@if ($cerrada)
    @push('head')<meta http-equiv="refresh" content="3;url={{ route('login') }}">@endpush
@endif

@section('cuerpo')
<div class="min-h-screen bg-[radial-gradient(circle_at_10%_10%,#eef5e8_0,transparent_35%),radial-gradient(circle_at_95%_95%,#fde9dc_0,transparent_30%)]">
    <div class="flex items-center gap-3 bg-crema-2 px-4 py-3 text-sm sm:px-8">
        <span class="h-2.5 w-2.5 rounded-full bg-terra-suave"></span>
        <span class="font-bold uppercase tracking-wide">Flujo de salida de operador</span>
        @auth
            <span class="hidden text-muted sm:inline">|</span>
            <span class="hidden sm:inline">Unidad: {{ auth()->user()->unidad_principal ?? 'Sin unidad' }} • Mérida – Progreso</span>
            <span class="ml-auto flex items-center gap-2 rounded-ctrl bg-white px-3 py-1.5 text-sm font-bold shadow-card">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-pino text-xs text-white">{{ auth()->user()->iniciales }}</span>Don {{ auth()->user()->primer_nombre }}
            </span>
        @endauth
    </div>

    <div class="mx-auto flex max-w-xl flex-col items-center px-4 pt-16">
        <div class="flex items-center gap-3 rounded-full bg-white px-5 py-3 shadow-card">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-pino"><span class="material-symbols-outlined text-mint">conversion_path</span></span>
            <span class="font-display text-3xl font-bold text-pino">Trayect<span class="text-terra">IA</span></span>
            <span class="text-muted">•</span><span class="text-xs font-bold tracking-wider">PENÍNSULA</span>
        </div>
        <p class="mt-4 text-sm">Plataforma Logística y Rutas de Yucatán</p>

        <div class="mt-10 w-full rounded-[1.25rem] bg-white px-8 py-14 text-center shadow-panel">
            @if ($cerrada)
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-pino"><span class="material-symbols-outlined text-3xl text-mint">key</span></span>
                <h1 class="mt-6 font-display text-2xl font-bold text-pino">Redirigiendo al Acceso…</h1>
                <p class="mt-2 text-lg">Conectando de nuevo con el portal de operadores TrayectIA.</p>
                <span class="mx-auto mt-8 block h-8 w-8 animate-spin rounded-full border-[3px] border-terra border-t-transparent" role="status" aria-label="Cargando"></span>
                <p class="mt-6 text-sm text-muted">Tu sesión se cerró de forma segura. <a href="{{ route('login') }}" class="font-bold text-terra underline">Ir ahora</a></p>
            @else
                <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-terra-fondo"><span class="material-symbols-outlined text-3xl text-terra">logout</span></span>
                <h1 class="mt-6 font-display text-2xl font-bold text-pino">¿Cerrar sesión en este equipo?</h1>
                <p class="mt-2 text-lg">Tus fletes, cartas porte y gastos de casetas quedan guardados en el servidor.</p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('panel') }}" class="flex h-12 items-center justify-center rounded-ctrl border-[1.5px] border-pino px-6 font-display font-bold text-pino hover:bg-pino/5">Seguir trabajando</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex h-12 w-full items-center justify-center gap-2 rounded-ctrl bg-terra px-6 font-display font-bold text-white shadow-card hover:bg-[#AD4F30] active:scale-[.98]">
                            <span class="material-symbols-outlined">logout</span>Sí, cerrar sesión
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <p class="mt-6 flex items-center gap-2 text-center text-sm text-muted"><span class="material-symbols-outlined text-base">verified_user</span>Cifrado de datos SAT Carta Porte v3.0 verificado. Terminal Umán, Yuc.</p>
    </div>
</div>
@endsection
