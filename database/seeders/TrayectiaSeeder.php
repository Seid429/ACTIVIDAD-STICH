<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Viaje;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Datos de demostración de TrayectIA: el operador Don Jorge y los viajes del mes.
 * Ejecutar con: php artisan db:seed --class=TrayectiaSeeder
 */
class TrayectiaSeeder extends Seeder
{
    public function run(): void
    {
        $jorge = User::updateOrCreate(
            ['email' => 'donjorge@transportes.mx'],
            [
                'name' => 'Jorge Peniche',
                'whatsapp' => '9991234567',
                'empresa' => 'Transportes Don Jorge',
                'ciudad' => 'Mérida, Yucatán',
                'unidad_principal' => 'Torton Kenworth T370',
                'password' => 'trayectia2026',
            ],
        );

        $jorge->viajes()->delete();

        $inicio = now()->startOfMonth();

        // [folio, cliente, origen, destino, carga, unidad, chofer, km, precio, estado, día del mes]
        $viajes = [
            [1, 'Constructora del Sureste', 'Mérida', 'Progreso', 'Varilla corrugada 10T', 'torton', 'luis', 36, 5200, 'cobrado', 0],
            [2, 'Materiales Itzimná', 'Umán', 'Mérida', 'Block hueco 12x20', 'rabon', 'mario', 24, 4300, 'cobrado', 0],
            [3, 'Distribuidora Maya Norte', 'Mérida', 'Progreso', 'Consolidado puerto', 'rabon', 'mario', 36, 5600, 'cobrado', 1],
            [4, 'Cementos Peninsulares', 'Mérida', 'Caucel', 'Cemento gris 14T', 'torton', 'luis', 22, 4800, 'cobrado', 1],
            [5, 'Constructora del Sureste', 'Mérida', 'Umán', 'Varilla y alambrón', 'torton', 'luis', 25, 5100, 'cobrado', 2],
            [6, 'Materiales Itzimná', 'Progreso', 'Mérida', 'Arena de mar', 'rabon', 'mario', 36, 5900, 'cobrado', 3],
            [7, 'Distribuidora Maya Norte', 'Mérida', 'Caucel', 'Abarrotes', 'rabon', 'mario', 22, 5400, 'cobrado', 4],
            [8, 'Cementos Peninsulares', 'Umán', 'Caucel', 'Cemento gris 14T', 'torton', 'luis', 18, 6300, 'cobrado', 5],
            [9, 'Constructora del Sureste', 'Mérida', 'Progreso', 'Varilla corrugada 12T', 'torton', 'luis', 36, 5900, 'facturado', 1],
            [10, 'Materiales Itzimná', 'Mérida', 'Umán', 'Block y mortero', 'rabon', 'mario', 24, 5500, 'facturado', 2],
            [11, 'Distribuidora Maya Norte', 'Mérida', 'Progreso', 'Consolidado puerto', 'rabon', 'mario', 36, 4200, 'facturado', 8],
            [12, 'Cementos Peninsulares', 'Mérida', 'Caucel', 'Cemento gris 14T', 'torton', 'luis', 22, 3100, 'facturado', 9],
            [13, 'Materiales Itzimná', 'Umán', 'Caucel', 'Material pétreo', 'rabon', 'mario', 18, 2800, 'aprobado', 10],
            [14, 'Distribuidora Maya Norte', 'Mérida', 'Progreso', 'Consolidado puerto de altura', 'rabon', 'mario', 36, 3100, 'entregado', 11],
            [15, 'Constructora del Sureste', 'Mérida', 'Caucel', '14 Ton Cemento Cruz Azul', 'torton', 'luis', 38, 4200, 'en_ruta', 12],
        ];

        foreach ($viajes as [$folio, $cliente, $origen, $destino, $carga, $unidad, $chofer, $km, $precio, $estado, $dia]) {
            $fecha = $inicio->copy()->addDays(min($dia, now()->day - 1))->setTime(7, 30);
            $costos = Viaje::cotizar($unidad, $chofer, $km, $precio);
            $avanzado = $estado !== 'en_ruta';

            Viaje::create([
                'user_id' => $jorge->id,
                'folio' => $folio,
                'cliente' => $cliente,
                'origen' => $origen,
                'destino' => $destino,
                'carga' => $carga,
                'fecha' => $fecha->toDateString(),
                'hora' => '07:30',
                'espera_hrs' => 1.5,
                'unidad' => $unidad,
                'chofer' => $chofer,
                'distancia_km' => $km,
                'precio' => $precio,
                'costo_diesel' => $costos['costo_diesel'],
                'casetas' => $costos['casetas'],
                'viatico' => $costos['viatico'],
                'desgaste' => $costos['desgaste'],
                'estado' => $estado,
                'avance' => $avanzado ? 100 : 85,
                'token_aprobacion' => Str::random(40),
                'entregado_at' => $avanzado ? $fecha->copy()->setTime(9, 20) : null,
                'aprobado_at' => in_array($estado, ['aprobado', 'facturado', 'cobrado']) ? $fecha->copy()->setTime(9, 40) : null,
                'aprobado_por' => in_array($estado, ['aprobado', 'facturado', 'cobrado']) ? 'Arq. Manuel E.' : null,
                'facturado_at' => in_array($estado, ['facturado', 'cobrado']) ? $fecha->copy()->setTime(12, 0) : null,
                'fecha_pago' => in_array($estado, ['facturado', 'cobrado']) ? $fecha->copy()->addDays(config('trayectia.dias_credito'))->toDateString() : null,
                'cobrado_at' => $estado === 'cobrado' ? $fecha->copy()->addDays(6)->min(now()) : null,
                'created_at' => $fecha->copy()->setTime(6, 45),
            ]);
        }
    }
}
