{{-- Foto de perfil o iniciales. Parámetros: $usuario, $clase (tamaño y colores del círculo) --}}
@if ($usuario->avatar_url)
    <img src="{{ $usuario->avatar_url }}" alt="Foto de {{ $usuario->name }}" class="{{ $clase }} rounded-full object-cover">
@else
    <span class="{{ $clase }} grid place-items-center rounded-full font-display font-bold">{{ $usuario->iniciales }}</span>
@endif
