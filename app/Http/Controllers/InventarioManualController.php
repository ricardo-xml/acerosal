<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Familia;
use App\Models\Lote;
use App\Models\Pieza;
use App\Models\MovimientoInventario;

class InventarioManualController extends Controller
{
    public function index()
    {
        $familias = Familia::where('inactivo', 0)->get();
        return view('inventario.manual', compact('familias'));
    }

    public function productosPorFamilia($id)
    {
        return Producto::where('id_familia', $id)
            ->where('eliminado', 0)
            ->select('id_producto', 'descripcion', 'codigo')
            ->get();
    }

    public function ultimoLote()
    {
        return Lote::orderBy('id_lote', 'DESC')->first();
    }

/*     public function guardar(Request $request)
    {
        $request->validate([
            'id_producto' => 'required',
            'codigo_lote' => 'required',
            'peso_total_libras' => 'required|numeric',
            'cantidad_total_metros' => 'required|numeric',
            'piezas' => 'required|array|min:1',
        ]);

        // Crear lote
        $lote = Lote::create([
            'id_producto' => $request->id_producto,
            'codigo' => $request->codigo_lote,
            'fecha_ingreso' => now(),
            'peso_total_libras' => $request->peso_total_libras,
            'cantidad_total_metros' => $request->cantidad_total_metros,
            'relacion_cantidad_peso' => $request->peso_total_libras / $request->cantidad_total_metros,
            'total_piezas' => count($request->piezas)
        ]);

        foreach ($request->piezas as $p) {

            $pieza = Pieza::create([
                'id_producto' => $request->id_producto,
                'id_lote' => $lote->id_lote,
                'codigo' => $p['codigo'],
                'cantidad_metros_inicial' => $p['cantidad_metros'],
                'peso_libras_inicial' => $p['cantidad_metros'] * $lote->relacion_cantidad_peso,
                'cantidad_metros_actual' => $p['cantidad_metros'],
                'peso_libras_actual' => $p['cantidad_metros'] * $lote->relacion_cantidad_peso,
                'peso_libras_recortados' => 0,
                'cantidad_metros_recortados' => 0,
            ]);

            MovimientoInventario::create([
                'id_pieza' => $pieza->id_pieza,
                'origen' => 'Manual',
                'tipo' => 'entrada',
                'cantidad' => $p['cantidad_metros'],
                'peso' => $pieza->peso_libras_inicial,
                'id_usuario' => auth()->id(),
                'comentario' => "Ingreso manual, pieza {$pieza->codigo}"
            ]);
        }

        return response()->json(['mensaje' => "Inventario manual guardado correctamente"]);
    }

 */
    public function guardar(Request $request)
    {
        $idUsuario = session('idUsuario');
        try {
            $data = $request->all();

            DB::beginTransaction();

            // 1. Guardar lote
            $lote = Lote::create([
                'id_producto' => $data['lote']['id_producto'],
                'codigo' => $data['lote']['codigo_lote'],
                'fecha_ingreso' => $data['lote']['fecha_ingreso'],
                'peso_total_libras' => $data['lote']['peso_total'],
                'cantidad_total_metros' => $data['lote']['mts_total'],
                'relacion_cantidad_peso' => $data['lote']['relacion'],
                'total_piezas' => $data['lote']['total_piezas'],
            ]);

            // 2. Guardar piezas
            foreach ($data['piezas'] as $pz) {

                $pieza = Pieza::create([
                    'id_producto' => $data['lote']['id_producto'],
                    'id_lote' => $lote->id_lote,
                    'codigo' => $pz['codigo'],
                    'peso_libras_inicial' => $pz['lbs'],
                    'cantidad_metros_inicial' => $pz['mts'],
                    'peso_libras_actual' => $pz['lbs'],
                    'cantidad_metros_actual' => $pz['mts'],
                    'peso_libras_recortados' => 0,
                    'cantidad_metros_recortados' => 0,
                ]);

                // 3. Registrar movimiento
                MovimientoInventario::create([
                    'id_pieza' => $pieza->id_pieza,
                    'id_corte' => null,
                    'id_compra' => null,
                    'origen' => 'Manual',
                    'tipo' => 'entrada',
                    'cantidad' => $pz['mts'],
                    'peso' => $pz['lbs'],
                    'id_usuario' => $idUsuario,
                    'comentario' => "Ingreso manual de pieza {$pieza->codigo}"
                ]);
            }

            DB::commit();

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

}
