@extends('trayectia.layouts.app')

@section('titulo', 'Editar perfil')

@php
    $valor = fn ($campo) => old($campo, $usuario->{$campo});
@endphp

@section('contenido')
<p class="text-xs font-bold uppercase tracking-wider text-terra">Cuenta del operador</p>
<h1 class="mt-1 font-display text-4xl font-extrabold tracking-tight text-pino">Editar perfil</h1>
<p class="mt-1 max-w-2xl text-muted">Estos datos aparecen en tus cotizaciones, en el link de aprobación que recibe tu cliente y en tus facturas.</p>

<div class="mt-8 grid gap-8 xl:grid-cols-[minmax(0,1fr)_24rem]">
    {{-- Datos del perfil --}}
    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-8 rounded-card bg-white p-6 shadow-card sm:p-8" novalidate>
        @csrf
        @method('PUT')

        <section class="flex flex-col gap-5 sm:flex-row sm:items-center">
            <div class="relative">
                <img id="avatar-vista" src="{{ $usuario->avatar_url }}" alt="" class="{{ $usuario->avatar_url ? '' : 'hidden' }} h-24 w-24 rounded-full object-cover ring-4 ring-crema-2">
                <span id="avatar-iniciales" class="{{ $usuario->avatar_url ? 'hidden' : 'grid' }} h-24 w-24 place-items-center rounded-full bg-pino font-display text-3xl font-bold text-white ring-4 ring-crema-2">{{ $usuario->iniciales }}</span>
            </div>
            <div>
                <p class="font-display text-xl font-bold">{{ $usuario->name }}</p>
                <p class="text-sm text-muted">Miembro desde {{ $usuario->created_at->translatedFormat('F Y') }} · {{ $usuario->viajes()->count() }} viajes registrados</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <label class="flex cursor-pointer items-center gap-1 rounded-ctrl bg-crema-2 px-4 py-2 text-sm font-bold hover:bg-crema-3">
                        <span class="material-symbols-outlined text-base">photo_camera</span>Cambiar foto
                        <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp" class="sr-only">
                    </label>
                    @if ($usuario->avatar)
                        <label class="flex cursor-pointer items-center gap-2 rounded-ctrl px-3 py-2 text-sm font-bold text-bad hover:bg-bad/5">
                            <input type="checkbox" name="quitar_avatar" value="1" class="accent-bad">Quitar foto
                        </label>
                    @endif
                </div>
                <p class="mt-1 text-xs text-muted">JPG, PNG o WEBP de hasta 2 MB.</p>
                @error('avatar')<p class="mt-1 text-sm text-bad">{{ $message }}</p>@enderror
            </div>
        </section>

        <section>
            <h2 class="flex items-center gap-2 font-display text-xl font-bold"><span class="material-symbols-outlined text-terra">person</span>Datos personales</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                @foreach ([['name', 'Nombre completo', 'person', 'text', 'name'], ['email', 'Correo electrónico', 'mail', 'email', 'email']] as [$campo, $etiqueta, $icono, $tipo, $auto])
                    <div>
                        <label for="{{ $campo }}" class="mb-2 block text-sm font-bold">{{ $etiqueta }}</label>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">{{ $icono }}</span>
                            <input id="{{ $campo }}" name="{{ $campo }}" type="{{ $tipo }}" value="{{ $valor($campo) }}" autocomplete="{{ $auto }}" class="campo con-icono @error($campo) invalido @enderror">
                        </div>
                        @error($campo)<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                @endforeach
                <div class="sm:col-span-2">
                    <label for="whatsapp" class="mb-2 block text-sm font-bold">Número de WhatsApp para avisos de fletes</label>
                    <div class="flex gap-2">
                        <span class="flex h-12 items-center rounded-ctrl bg-crema-2 px-3 text-sm font-bold">🇲🇽 +52</span>
                        <div class="relative flex-1">
                            <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">chat</span>
                            <input id="whatsapp" name="whatsapp" value="{{ $valor('whatsapp') }}" inputmode="numeric" maxlength="12" autocomplete="tel-national" class="campo con-icono @error('whatsapp') invalido @enderror">
                        </div>
                    </div>
                    @error('whatsapp')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@else<p class="mt-1.5 text-xs text-muted">También puedes usarlo para iniciar sesión.</p>@enderror
                </div>
            </div>
        </section>

        <section>
            <h2 class="flex items-center gap-2 font-display text-xl font-bold"><span class="material-symbols-outlined text-terra">storefront</span>Tu negocio</h2>
            <div class="mt-5 grid gap-5 sm:grid-cols-2">
                @foreach ([['empresa', 'Nombre de tu negocio', 'storefront'], ['ciudad', 'Ciudad base', 'location_on']] as [$campo, $etiqueta, $icono])
                    <div>
                        <label for="{{ $campo }}" class="mb-2 block text-sm font-bold">{{ $etiqueta }}</label>
                        <div class="relative">
                            <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">{{ $icono }}</span>
                            <input id="{{ $campo }}" name="{{ $campo }}" value="{{ $valor($campo) }}" class="campo con-icono @error($campo) invalido @enderror">
                        </div>
                        @error($campo)<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                    </div>
                @endforeach
                <div class="sm:col-span-2">
                    <label for="unidad_principal" class="mb-2 block text-sm font-bold">Unidad principal</label>
                    <div class="relative">
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">local_shipping</span>
                        <input id="unidad_principal" name="unidad_principal" list="tipos-unidad" value="{{ $valor('unidad_principal') }}" placeholder="Ej. Torton Kenworth T370" class="campo con-icono @error('unidad_principal') invalido @enderror">
                        <datalist id="tipos-unidad"><option value="Torton"><option value="Rabón"><option value="Trailer 53'"></datalist>
                    </div>
                    @error('unidad_principal')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end border-t border-crema-3 pt-6">
            <button class="flex h-12 items-center gap-2 rounded-ctrl bg-terra px-6 font-display text-lg font-bold text-white shadow-card hover:bg-[#AD4F30] active:scale-[.98]">
                <span class="material-symbols-outlined">save</span>Guardar cambios
            </button>
        </div>
    </form>

    {{-- Contraseña --}}
    <form method="POST" action="{{ route('perfil.password') }}" class="self-start rounded-card bg-crema-2 p-6 shadow-card" novalidate>
        @csrf
        @method('PUT')
        <h2 class="flex items-center gap-2 font-display text-xl font-bold"><span class="material-symbols-outlined text-terra">lock_reset</span>Cambiar contraseña</h2>
        <p class="mt-1 text-sm text-muted">Usa al menos 8 caracteres. Por seguridad te pedimos la actual.</p>
        <div class="mt-5 space-y-4">
            @foreach ([['password_actual', 'Contraseña actual', 'current-password'], ['password', 'Nueva contraseña', 'new-password'], ['password_confirmation', 'Confirmar nueva contraseña', 'new-password']] as [$campo, $etiqueta, $auto])
                <div>
                    <label for="{{ $campo }}" class="mb-2 block text-sm font-bold">{{ $etiqueta }}</label>
                    <div class="relative">
                        <input id="{{ $campo }}" name="{{ $campo }}" type="password" autocomplete="{{ $auto }}" class="campo bg-white pr-12 @error($campo, 'password') invalido @enderror">
                        <button type="button" data-ver="{{ $campo }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-muted" aria-label="Mostrar contraseña"><span class="material-symbols-outlined">visibility</span></button>
                    </div>
                    @error($campo, 'password')<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
        <button class="mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-ctrl bg-pino font-display font-bold text-white hover:bg-pino-700 active:scale-[.98]">
            <span class="material-symbols-outlined">key</span>Actualizar contraseña
        </button>
    </form>
</div>
@endsection

@push('scripts')
@include('trayectia.partials.ver-password')
<script>
    // Vista previa de la foto antes de guardar.
    document.getElementById('avatar').addEventListener('change', function (e) {
        var archivo = e.target.files[0];
        if (!archivo) return;
        var img = document.getElementById('avatar-vista');
        img.src = URL.createObjectURL(archivo);
        img.classList.remove('hidden');
        document.getElementById('avatar-iniciales').classList.add('hidden');
    });
</script>
@endpush
