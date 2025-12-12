<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kardex Global</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 4px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>Kardex Global de Inventario</h2>

<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th>Pieza</th>
            <th>Lote</th>
            <th>Origen</th>
            <th>Tipo</th>
            <th>Mts</th>
            <th>Lbs</th>
            <th>Usuario</th>
            <th>Comentario</th>
        </tr>
    </thead>
    <tbody>
    @foreach($movimientos as $m)
        <tr>
            <td>{{ $m['fecha'] }}</td>
            <td>{{ $m['producto'] }}</td>
            <td>{{ $m['codigo'] }}</td>
            <td>{{ $m['lote'] }}</td>
            <td>{{ $m['origen'] }}</td>
            <td>{{ $m['tipo'] }}</td>
            <td>{{ number_format($m['mts'], 2) }}</td>
            <td>{{ number_format($m['lbs'], 2) }}</td>
            <td>{{ $m['usuario'] }}</td>
            <td>{{ $m['comentario'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
