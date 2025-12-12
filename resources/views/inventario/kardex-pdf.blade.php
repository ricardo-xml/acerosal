<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kardex {{ $pieza->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2, h3 { margin: 0 0 8px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #444; padding: 4px; text-align: center; }
        th { background-color: #eee; }
        .resumen { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Kardex de pieza</h2>

    <div class="resumen">
        <p><strong>Código pieza:</strong> {{ $pieza->codigo }}</p>
        <p><strong>Producto:</strong> {{ $pieza->producto->descripcion ?? '' }}</p>
        <p><strong>Código producto:</strong> {{ $pieza->producto->codigo ?? '' }}</p>
        <p><strong>Lote:</strong> {{ $pieza->lote->codigo ?? '' }}</p>
        <p><strong>Metros iniciales:</strong> {{ number_format($pieza->cantidad_metros_inicial, 2) }}</p>
        <p><strong>Libras iniciales:</strong> {{ number_format($pieza->peso_libras_inicial, 2) }}</p>
        <p><strong>Metros actuales:</strong> {{ number_format($pieza->cantidad_metros_actual, 2) }}</p>
        <p><strong>Libras actuales:</strong> {{ number_format($pieza->peso_libras_actual, 2) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Origen</th>
                <th>Tipo</th>
                <th>Metros</th>
                <th>Libras</th>
                <th>Usuario</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
        @forelse($movimientos as $mov)
            <tr>
                <td>{{ $mov->fecha }}</td>
                <td>{{ $mov->origen }}</td>
                <td>{{ $mov->tipo }}</td>
                <td>{{ number_format($mov->cantidad, 2) }}</td>
                <td>{{ number_format($mov->peso, 2) }}</td>
                <td>{{ $mov->usuario->nombre ?? 'N/D' }}</td>
                <td>{{ $mov->comentario }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Sin movimientos registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</body>
</html>
