<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        .container {
            margin-top: 80px;
            text-align: center;
        }
        .codigo {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Barcode de Pieza</h2>

    {!! DNS1D::getBarcodeHTML($pieza->codigo, 'C128', 3, 80) !!}

    <div class="codigo">{{ $pieza->codigo }}</div>
</div>

</body>
</html>
