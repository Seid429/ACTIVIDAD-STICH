<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f7fa;
        }
        .saludo {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        h1 {
            color: #333;
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        p {
            color: #666;
            font-size: 1.1rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="saludo">
        <h1>{{ $mensaje }}</h1>
        <p>Enviado por {{ $origen }}</p>
    </div>
</body>
</html>