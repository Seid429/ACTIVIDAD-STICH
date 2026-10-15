<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function edit(Request $request): View
    {
        return view('trayectia.perfil', ['usuario' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $usuario = $request->user();
        $request->merge(['whatsapp' => preg_replace('/\D/', '', (string) $request->input('whatsapp'))]);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users')->ignore($usuario->id)],
            'whatsapp' => ['required', 'digits:10', Rule::unique('users')->ignore($usuario->id)],
            'empresa' => ['required', 'string', 'max:120'],
            'ciudad' => ['required', 'string', 'max:80'],
            'unidad_principal' => ['required', 'string', 'max:80'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [], [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'whatsapp' => 'número de WhatsApp',
            'empresa' => 'nombre del negocio',
            'ciudad' => 'ciudad base',
            'unidad_principal' => 'unidad principal',
            'avatar' => 'foto de perfil',
        ]);

        // La foto anterior se borra al subir una nueva o al quitarla.
        if ($request->hasFile('avatar') || $request->boolean('quitar_avatar')) {
            if ($usuario->avatar) {
                Storage::disk('public')->delete($usuario->avatar);
            }
            $datos['avatar'] = $request->hasFile('avatar')
                ? $request->file('avatar')->store('avatars', 'public')
                : null;
        } else {
            unset($datos['avatar']);
        }

        $usuario->update($datos);

        return back()->with('exito', 'Tu perfil se actualizó correctamente.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $datos = $request->validateWithBag('password', [
            'password_actual' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8), 'different:password_actual'],
        ], [], [
            'password_actual' => 'contraseña actual',
            'password' => 'nueva contraseña',
        ]);

        $request->user()->update(['password' => $datos['password']]);

        return back()->with('exito', 'Contraseña actualizada. Úsala la próxima vez que inicies sesión.');
    }
}
