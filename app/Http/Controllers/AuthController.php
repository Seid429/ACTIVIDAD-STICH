<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('trayectia.auth.iniciar-sesion');
    }

    public function autenticar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], ['usuario' => 'correo o WhatsApp', 'password' => 'contraseña']);

        // Se acepta correo electrónico o celular a 10 dígitos.
        $usuario = trim($datos['usuario']);
        $campo = filter_var($usuario, FILTER_VALIDATE_EMAIL) ? 'email' : 'whatsapp';
        if ($campo === 'whatsapp') {
            $usuario = preg_replace('/\D/', '', $usuario);
        }

        if (! Auth::attempt([$campo => $usuario, 'password' => $datos['password']], $request->boolean('recordar'))) {
            return back()
                ->withErrors(['password' => 'El correo/WhatsApp o la contraseña no coinciden.'])
                ->onlyInput('usuario');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('panel'));
    }

    public function demo(Request $request): RedirectResponse
    {
        $demo = User::where('email', 'donjorge@transportes.mx')->first();

        if (! $demo) {
            return back()->withErrors(['usuario' => 'La cuenta demo no existe. Ejecuta: php artisan db:seed --class=TrayectiaSeeder']);
        }

        Auth::login($demo);
        $request->session()->regenerate();

        return redirect()->route('panel');
    }

    public function registro(): View
    {
        return view('trayectia.auth.crear-cuenta');
    }

    public function registrar(Request $request): RedirectResponse
    {
        $request->merge(['whatsapp' => preg_replace('/\D/', '', (string) $request->input('whatsapp'))]);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'whatsapp' => ['required', 'digits:10', 'unique:users,whatsapp'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'empresa' => ['required', 'string', 'max:120'],
            'ciudad' => ['required', 'string', 'max:80'],
            'unidad_principal' => ['required', 'string', 'max:80'],
        ], [], [
            'name' => 'nombre completo',
            'email' => 'correo electrónico',
            'whatsapp' => 'número de WhatsApp',
            'password' => 'contraseña',
            'empresa' => 'nombre del negocio',
            'ciudad' => 'ciudad base',
            'unidad_principal' => 'unidad principal',
        ]);

        $usuario = User::create($datos);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('panel')->with('exito', '¡Bienvenido a TrayectIA! Tu cuenta está activa.');
    }

    public function salir(): View
    {
        return view('trayectia.auth.cerrar-sesion', ['cerrada' => false]);
    }

    public function cerrar(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('salida.lista');
    }

    public function salidaLista(): View
    {
        return view('trayectia.auth.cerrar-sesion', ['cerrada' => true]);
    }
}
