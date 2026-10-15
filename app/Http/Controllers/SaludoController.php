<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class SaludoController extends Controller
{
    public function saludo(): JsonResponse
    {
        return response()->json([
            'mensaje' => 'hola esta es la 2da practica',
            'origen' => 'SaludoController',
        ]);
    }

    public function vista()
    {
        $datos = $this->saludo()->getData(true);
        return view('saludo', $datos);
    }
}