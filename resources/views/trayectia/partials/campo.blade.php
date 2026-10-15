{{-- Campo de texto con icono. Parámetros: nombre, etiqueta, icono, placeholder, tipo?, auto? --}}
<div>
    <label for="{{ $nombre }}" class="mb-2 block text-sm font-bold">{{ $etiqueta }}</label>
    <div class="relative">
        <span class="material-symbols-outlined pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-muted">{{ $icono }}</span>
        <input id="{{ $nombre }}" name="{{ $nombre }}" type="{{ $tipo ?? 'text' }}" placeholder="{{ $placeholder }}"
               @if (($tipo ?? 'text') !== 'password') value="{{ old($nombre) }}" @endif
               @isset($auto) autocomplete="{{ $auto }}" @endisset
               class="campo con-icono @error($nombre) invalido @enderror">
    </div>
    @error($nombre)<p class="mt-1.5 text-sm text-bad">{{ $message }}</p>@enderror
</div>
