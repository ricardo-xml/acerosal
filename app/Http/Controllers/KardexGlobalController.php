<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Pieza;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class KardexGlobalController extends Controller
{
    /**
     * Vista del kardex global
     */
    public function index()
    {
        $productos = Producto::orderBy('descripcion')->get();
        $usuarios = Usuario::orderBy('nombre')->get();

        return view('inventario.kardex-global', compact('productos', 'usuarios'));
    }

    /**
     * Devuelve datos filtrados para la tabla
     */
    public function datos(Request $request)
    {
        $query = MovimientoInventario::with(['pieza.producto', 'pieza.lote', 'usuario'])
            ->where('eliminado', 0);

        // FILTROS
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        if ($request->filled('producto')) {
            $query->whereHas('pieza', function($q) use ($request) {
                $q->where('id_producto', $request->producto);
            });
        }

        if ($request->filled('codigo_pieza')) {
            $query->whereHas('pieza', function($q) use ($request) {
                $q->where('codigo', 'LIKE', '%' . $request->codigo_pieza . '%');
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('origen')) {
            $query->where('origen', $request->origen);
        }

        if ($request->filled('usuario')) {
            $query->where('id_usuario', $request->usuario);
        }

        $query->orderBy('fecha')->orderBy('id_movimiento');

        $mov = $query->get()->map(function ($m) {
            return [
                'fecha'     => $m->fecha,
                'producto'  => $m->pieza->producto->descripcion ?? '',
                'codigo'    => $m->pieza->codigo,
                'lote'      => $m->pieza->lote->codigo ?? '',
                'origen'    => $m->origen,
                'tipo'      => $m->tipo,
                'mts'       => $m->cantidad,
                'lbs'       => $m->peso,
                'usuario'   => $m->usuario->nombre ?? '',
                'comentario'=> $m->comentario,
                'id_pieza'  => $m->id_pieza,
            ];
        });

        return response()->json($mov);
    }

    /**
     * Exportar PDF del listado filtrado
     */
    public function exportarPdf(Request $request)
    {
        // Reutilizamos la misma consulta que datos()
        $req = $request;
        $data = $this->datos($req)->getData(true);

        $pdf = \PDF::loadView('inventario.kardex-global-pdf', ['movimientos' => $data]);

        return $pdf->download('kardex_global.pdf');
    }
}
