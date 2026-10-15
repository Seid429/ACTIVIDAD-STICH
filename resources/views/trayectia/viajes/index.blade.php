@extends('trayectia.layouts.app')

@section('titulo', 'Viajes')

@section('contenido')
<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        <p class="text-xs font-bold uppercase tracking-wider text-terra">Bitácora de fletes</p>
        <h1 class="mt-1 font-display text-4xl font-extrabold tracking-tight text-pino">Viajes</h1>
        <p class="text-muted">{{ $viajes->count() }} {{ $viajes->count() === 1 ? 'viaje' : 'viajes' }}{{ $estado ? ' · '.\App\Models\Viaje::ESTADOS[$estado]['etiqueta'] : '' }}</p>
    </div>
    <a href="{{ route('viajes.create') }}" class="flex items-center justify-center gap-2 rounded-ctrl bg-terra px-5 py-3 font-display font-bold text-white shadow-card hover:bg-[#AD4F30] sm:hidden"><span class="material-symbols-outlined">add_circle</span>Nuevo viaje</a>
</div>

<div class="mt-6 flex flex-wrap gap-2 text-sm font-bold">
    <a href="{{ route('viajes.index') }}" class="rounded-lg px-3 py-1.5 {{ ! $estado ? 'bg-pino text-white' : 'bg-crema-2 hover:bg-crema-3' }}">Todos</a>
    @foreach (\App\Models\Viaje::ESTADOS as $clave => $datos)
        <a href="{{ route('viajes.index', ['estado' => $clave]) }}" class="rounded-lg px-3 py-1.5 {{ $estado === $clave ? 'bg-pino text-white' : 'bg-crema-2 hover:bg-crema-3' }}">{{ \Illuminate\Support\Str::before($datos['etiqueta'], ' ·') }}</a>
    @endforeach
</div>

<div class="mt-6 space-y-4">
    @forelse ($viajes as $viaje)
        @include('trayectia.partials.viaje-tarjeta', ['viaje' => $viaje])
    @empty
        <p class="rounded-card bg-white p-8 text-center text-muted shadow-card">No hay viajes en este estado. <a href="{{ route('viajes.create') }}" class="font-bold text-terra">Registra uno nuevo</a>.</p>
    @endforelse
</div>
@endsection
