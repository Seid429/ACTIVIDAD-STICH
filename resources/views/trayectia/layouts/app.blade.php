@extends('trayectia.layouts.base')

@php
    $usuario = auth()->user();
    $hora = (int) now()->format('G');
    $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');
    $enRuta = $usuario->viajes()->where('estado', 'en_ruta')->exists();
    $avisos = $usuario->viajes()->whereIn('estado', ['entregado', 'aprobado'])->count()
        + $usuario->viajes()->atrasados()->count();
    $menu = [
        ['ruta' => 'panel', 'activa' => 'panel', 'icono' => 'space_dashboard', 'texto' => 'Inicio'],
        ['ruta' => 'viajes.create', 'activa' => 'viajes.create', 'icono' => 'calculate', 'texto' => 'Nuevo viaje'],
        ['ruta' => 'viajes.index', 'activa' => ['viajes.index', 'viajes.show'], 'icono' => 'local_shipping', 'texto' => 'Viajes'],
        ['ruta' => 'aprobaciones.index', 'activa' => 'aprobaciones.index', 'icono' => 'approval_delegation', 'texto' => 'Aprobación cliente'],
        ['ruta' => 'cobros', 'activa' => 'cobros', 'icono' => 'payments', 'texto' => 'Cobros y métricas'],
    ];
@endphp

@section('cuerpo')
<div class="min-h-screen lg:pl-72">
    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col bg-pino text-white transition-transform lg:translate-x-0">
        <div class="flex items-center gap-3 bg-pino-900 px-6 py-5">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/10"><span class="material-symbols-outlined text-mint">alt_route</span></span>
            <div>
                <p class="font-display text-xl font-bold leading-none">Trayect<span class="text-terra-suave">IA</span></p>
                <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-white/60">Hombre-camión</p>
            </div>
        </div>

        <a href="{{ route('perfil.edit') }}" class="mx-3 mt-4 flex items-center gap-3 rounded-card p-4 transition {{ request()->routeIs('perfil.*') ? 'bg-white/20' : 'bg-white/10 hover:bg-white/15' }}" title="Editar perfil">
            @include('trayectia.partials.avatar', ['clase' => 'h-11 w-11 shrink-0 bg-terra-suave text-pino-900'])
            <div class="min-w-0 flex-1">
                <p class="truncate font-display font-bold">Don {{ $usuario->primer_nombre }}</p>
                <p class="truncate text-xs font-bold tracking-wide text-white/60">{{ $usuario->empresa ?? 'Transportista' }} · {{ \Illuminate\Support\Str::before($usuario->ciudad ?? 'Mérida', ',') }}</p>
            </div>
            <span class="material-symbols-outlined text-base text-white/50">edit</span>
        </a>

        <nav class="mt-8 flex-1 px-3">
            <p class="mb-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/50">Operaciones</p>
            @foreach ($menu as $item)
                @php $activo = request()->routeIs(...(array) $item['activa']); @endphp
                <a href="{{ route($item['ruta']) }}"
                   class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 font-medium transition {{ $activo ? 'bg-terra text-white shadow-card' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">{{ $item['icono'] }}</span>{{ $item['texto'] }}
                </a>
            @endforeach
        </nav>

        <div class="mx-3 mb-3 rounded-card bg-pino-900 p-4">
            <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-white/60">
                Diésel Mérida hoy <span class="material-symbols-outlined text-base">local_gas_station</span>
            </div>
            <p class="mt-2 font-display text-xl font-bold tabular">${{ number_format(config('trayectia.diesel_hoy'), 2) }} <span class="text-xs font-bold text-white/60">MXN/L</span></p>
        </div>
        <a href="{{ route('logout.confirm') }}" class="mx-3 mb-4 flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white">
            <span class="material-symbols-outlined">logout</span>Cerrar sesión
        </a>
    </aside>
    <div id="sidebar-fondo" class="fixed inset-0 z-30 hidden bg-black/40 lg:hidden" onclick="alternarMenu()"></div>

    {{-- Encabezado --}}
    <header class="sticky top-0 z-20 flex items-center gap-4 border-b border-crema-3 bg-crema/90 px-4 py-3 backdrop-blur sm:px-8">
        <button class="grid h-10 w-10 place-items-center rounded-full bg-crema-2 lg:hidden" onclick="alternarMenu()" aria-label="Abrir menú">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="min-w-0 flex-1">
            <p class="flex items-center gap-2 font-display text-lg font-bold">
                <span class="truncate">{{ $saludo }}, Don {{ $usuario->primer_nombre }}</span>
                @if ($enRuta)<span class="rounded-full bg-mint px-2 py-0.5 font-sans text-[11px] font-bold text-pino">En ruta</span>@endif
            </p>
            <p class="hidden truncate text-sm text-muted sm:block">Corredor Peninsular · Mérida – Progreso – Umán</p>
        </div>
        <a href="{{ route('viajes.create') }}" class="hidden items-center gap-2 rounded-xl bg-terra px-5 py-3 font-display font-bold text-white shadow-card transition hover:bg-[#AD4F30] active:scale-[.98] sm:flex">
            <span class="material-symbols-outlined">add_circle</span>Nuevo viaje
        </a>
        <a href="{{ route('aprobaciones.index') }}" class="relative grid h-10 w-10 place-items-center rounded-full bg-crema-3" aria-label="Avisos">
            <span class="material-symbols-outlined">notifications</span>
            @if ($avisos)<span class="absolute -right-1 -top-1 grid h-5 min-w-5 place-items-center rounded-full bg-terra px-1 text-[11px] font-bold text-white">{{ $avisos }}</span>@endif
        </a>
        <a href="{{ route('perfil.edit') }}" title="Editar perfil">
            @include('trayectia.partials.avatar', ['clase' => 'h-10 w-10 bg-pino text-sm text-white'])
        </a>
    </header>

    <main class="px-4 pb-16 pt-6 sm:px-8">
        @foreach (['exito' => 'bg-mint text-pino border-ok/30', 'error' => 'bg-terra-fondo text-[#7E2C0C] border-bad/30'] as $tipo => $clase)
            @if (session($tipo))
                <div class="mb-6 flex items-center gap-3 rounded-card border px-5 py-4 font-medium {{ $clase }}">
                    <span class="material-symbols-outlined">{{ $tipo === 'exito' ? 'check_circle' : 'error' }}</span>{{ session($tipo) }}
                </div>
            @endif
        @endforeach

        @yield('contenido')
    </main>
</div>
@endsection

@push('scripts')
<script>
    function alternarMenu() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebar-fondo').classList.toggle('hidden');
    }
</script>
@endpush
