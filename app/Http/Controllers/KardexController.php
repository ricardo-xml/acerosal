<?php

namespace App\Http\Controllers;

use App\Models\Pieza;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    /**
     * Pantalla principal del Kardex
     */
    public function index()
    {
        return view('inventario.kardex');
    }

    /**
     * Autocomplete para buscar piezas por código
     * GET /inventario/kardex/buscar-pieza?q=...
     */
    public function buscarPieza(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json([]);
        }

        $piezas = Pieza::with(['producto', 'lote'])
            ->where('eliminado', 0)
            ->where('codigo', 'LIKE', '%' . $q . '%')
            ->orderBy('codigo')
            ->limit(10)
            ->get();

        $resultado = $piezas->map(function (Pieza $pieza) {
            return [
                'id_pieza' => $pieza->id_pieza,
                'codigo'   => $pieza->codigo,
                'producto' => $pieza->producto->descripcion ?? '',
                'lote'     => $pieza->lote->codigo ?? '',
            ];
        });

        return response()->json($resultado);
    }

    /**
     * Devuelve datos de pieza + movimientos para mostrar el Kardex (JSON)
     * GET /inventario/kardex/pieza/{id}
     */
    public function obtenerKardex($id)
    {
        $pieza = Pieza::with(['producto', 'lote'])->findOrFail($id);

        $movimientos = MovimientoInventario::with('usuario')
            ->where('id_pieza', $pieza->id_pieza)
            ->where('eliminado', 0)
            ->orderBy('fecha', 'asc')
            ->orderBy('id_movimiento', 'asc')
            ->get()
            ->map(function (MovimientoInventario $mov) {
                return [
                    'fecha'     => $mov->fecha,
                    'origen'    => $mov->origen,
                    'tipo'      => $mov->tipo,
                    'cantidad'  => (float) $mov->cantidad,
                    'peso'      => (float) $mov->peso,
                    'usuario'   => $mov->usuario->nombre ?? 'N/D',
                    'comentario'=> $mov->comentario,
                ];
            });

        return response()->json([
            'success' => true,
            'pieza' => [
                'id_pieza'              => $pieza->id_pieza,
                'codigo'                => $pieza->codigo,
                'producto'              => $pieza->producto->descripcion ?? '',
                'codigo_producto'       => $pieza->producto->codigo ?? '',
                'lote'                  => $pieza->lote->codigo ?? '',
                'metros_iniciales'      => $pieza->cantidad_metros_inicial,
                'libras_iniciales'      => $pieza->peso_libras_inicial,
                'metros_actuales'       => $pieza->cantidad_metros_actual,
                'libras_actuales'       => $pieza->peso_libras_actual,
            ],
            'movimientos' => $movimientos,
        ]);
    }

    /**
     * Exportar Kardex a PDF
     * Requiere tener instalado barryvdh/laravel-dompdf o similar (facade PDF)
     */
    public function exportarPdf($id)
    {
        $pieza = Pieza::with(['producto', 'lote'])->findOrFail($id);

        $movimientos = MovimientoInventario::with('usuario')
            ->where('id_pieza', $pieza->id_pieza)
            ->where('eliminado', 0)
            ->orderBy('fecha', 'asc')
            ->orderBy('id_movimiento', 'asc')
            ->get();

        // Asumiendo que tienes configurado \PDF (barryvdh/laravel-dompdf)
        $pdf = \PDF::loadView('inventario.kardex-pdf', compact('pieza', 'movimientos'));

        $nombre = 'kardex_' . $pieza->codigo . '.pdf';

        return $pdf->download($nombre);
    }
}
