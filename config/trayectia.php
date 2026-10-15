<?php

/*
|--------------------------------------------------------------------------
| TrayectIA — catálogos y parámetros del cotizador
|--------------------------------------------------------------------------
| Valores tomados del diseño de Stitch (proyecto "TrayectIA Logistics Web App").
*/

return [

    // Precio del diésel en Mérida (MXN por litro) usado por el cotizador.
    'precio_diesel' => 25.50,

    // Precio mostrado en el widget "Diésel Mérida hoy" del sidebar.
    'diesel_hoy' => 24.85,

    // Costo de desgaste/mantenimiento por kilómetro (llantas + aceite + amortización).
    'desgaste_km' => 5.79,

    // Días de crédito pactados con el cliente al facturar.
    'dias_credito' => 7,

    'unidades' => [
        'torton' => ['nombre' => 'Torton Freightliner', 'capacidad' => '14 Ton', 'rendimiento' => 3.2, 'placas' => 'YU-82-41'],
        'rabon' => ['nombre' => 'Rabón Kenworth', 'capacidad' => '8 Ton', 'rendimiento' => 4.2, 'placas' => 'YU-19-02'],
    ],

    'choferes' => [
        'luis' => ['nombre' => 'Luis Canché', 'iniciales' => 'LC', 'tarifa' => 650.00, 'tipo' => 'Tarifa local fija'],
        'mario' => ['nombre' => 'Mario Pech', 'iniciales' => 'MP', 'tarifa' => 700.00, 'tipo' => 'Tarifa foránea/local'],
    ],

    'clientes' => [
        'Constructora del Sureste',
        'Distribuidora Maya Norte',
        'Materiales Itzimná',
        'Cementos Peninsulares',
    ],

];
