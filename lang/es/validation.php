<?php

// Mensajes de validación en español para las reglas que usa TrayectIA.
return [
    'required' => 'El campo :attribute es obligatorio.',
    'string' => 'El campo :attribute debe ser texto.',
    'email' => 'El campo :attribute debe ser un correo válido.',
    'unique' => 'Ese :attribute ya está registrado.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'digits' => 'El campo :attribute debe tener :digits dígitos.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'date_format' => 'El campo :attribute no tiene el formato :format.',
    'in' => 'El :attribute seleccionado no es válido.',
    'image' => 'La :attribute debe ser una imagen.',
    'mimes' => 'La :attribute debe ser un archivo de tipo: :values.',
    'current_password' => 'La contraseña actual no es correcta.',
    'different' => 'La :attribute debe ser distinta de la :other.',
    'max' => [
        'file' => 'La :attribute no debe pesar más de :max kilobytes.',
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'password' => [
        'min' => 'La :attribute debe tener al menos :min caracteres.',
    ],
    'attributes' => [
        'fecha' => 'fecha estimada',
        'hora' => 'hora de carga',
        'origen' => 'origen',
        'destino' => 'destino',
        'unidad' => 'unidad',
        'chofer' => 'chofer',
        'casetas' => 'casetas',
    ],
];
