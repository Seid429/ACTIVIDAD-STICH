<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo') — TrayectIA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,300..500,0..1,0" rel="stylesheet">

    {{-- Tailwind por CDN: Practica_2 no tiene node_modules, así las vistas funcionan sin compilar. --}}
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        // Tokens del design system "Logística Peninsular" (stitch/DESIGN.md).
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pino: { DEFAULT: '#173F35', 700: '#1F4A3F', 800: '#12352C', 900: '#0E2A23' },
                        terra: { DEFAULT: '#9D4222', claro: '#C45F3C', suave: '#FE8C65', fondo: '#FFDBD0' },
                        crema: { DEFAULT: '#FEF9ED', 2: '#F4EFE3', 3: '#EDE8DC', 4: '#E7E2D6' },
                        tinta: '#1D1C15',
                        muted: '#6D766F',
                        ok: '#2E7D5B',
                        warn: '#D9A23B',
                        bad: '#B3412F',
                        mint: { DEFAULT: '#C1ECDD', claro: '#E7F3EC' },
                    },
                    fontFamily: {
                        display: ['Outfit', 'sans-serif'],
                        sans: ['"DM Sans"', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 4px 16px -2px rgba(23,63,55,.06), 0 2px 6px -1px rgba(196,95,60,.04)',
                        panel: '0 12px 32px -4px rgba(23,63,55,.12), 0 4px 12px -2px rgba(0,0,0,.04)',
                        modal: '0 24px 48px -8px rgba(23,63,55,.22)',
                    },
                    borderRadius: { card: '1rem', ctrl: '0.75rem' },
                },
            },
        };
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 1.25rem; line-height: 1; vertical-align: middle; }
        .icon-fill { font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 24; }
        .tabular { font-variant-numeric: tabular-nums; }
        .campo { width: 100%; height: 3rem; border-radius: .75rem; background: #F4EFE3; border: 1.5px solid transparent; padding: 0 1rem; color: #1D1C15; transition: border-color .15s, box-shadow .15s; }
        .campo:focus { outline: none; background: #fff; border-color: #173F35; box-shadow: 0 0 0 3px rgba(23,63,55,.15); }
        .campo::placeholder { color: #8b928d; }
        .campo.con-icono { padding-left: 2.75rem; }
        .campo.invalido { border-color: #B3412F; background: #fff; }
        input[type=range] { accent-color: #173F35; }
        @media print { aside, header, .no-print { display: none !important; } main { margin: 0 !important; padding: 0 !important; } }
    </style>
    @stack('head')
</head>
<body class="bg-crema font-sans text-tinta antialiased">
    @yield('cuerpo')
    @stack('scripts')
</body>
</html>
