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

    public function guardar(Request $request)
    {
        $idUsuario = session('idUsuario');

        try {
            $data = $request->all();

            DB::beginTransaction();

            // 1. Guardar lote
            $lote = Lote::create([
                'id_producto'               => $data['lote']['id_producto'],
                'codigo'                    => $data['lote']['codigo_lote'],
                'fecha_ingreso'             => $data['lote']['fecha_ingreso'],
                'peso_total_libras'         => $data['lote']['peso_total'],
                'unidad_medida_peso'        => 'lb', // automático
                'cantidad_total_metros'     => $data['lote']['mts_total'],
                'unidad_medida_longitud'    => 'm',  // automático
                'relacion_cantidad_peso'    => $data['lote']['relacion'],
                'total_piezas'              => $data['lote']['total_piezas'],
                'eliminado'                 => 0,
            ]);

            // 2. Guardar piezas
            foreach ($data['piezas'] as $pz) {

                $pieza = Pieza::create([
                    'id_producto'               => $data['lote']['id_producto'],
                    'id_lote'                   => $lote->id_lote,
                    'codigo'                    => $pz['codigo'],
                    'peso_libras_inicial'       => $pz['lbs'],
                    'cantidad_metros_inicial'   => $pz['mts'],
                    'peso_libras_actual'        => $pz['lbs'],
                    'cantidad_metros_actual'    => $pz['mts'],
                    'peso_libras_recortados'    => 0,
                    'cantidad_metros_recortados'=> 0,
                    'retirado'                  => 0,
                    'finalizado'                => 0,
                    'eliminado'                 => 0,
                ]);

                // 3. Registrar movimiento
                MovimientoInventario::create([
                    'id_pieza'       => $pieza->id_pieza,
                    'id_corte'       => null,
                    'id_compra'      => null,
                    'origen'         => 'Manual',
                    'tipo'           => 'entrada',
                    'cantidad'       => $pz['mts'],
                    'peso'           => $pz['lbs'],
                    'saldo_metros'   => $pz['mts'], // mismo valor
                    'saldo_libras'   => $pz['lbs'], // mismo valor
                    'fecha'          => now(),
                    'id_usuario'     => $idUsuario,
                    'comentario'     => "Ingreso manual de pieza {$pieza->codigo}",
                    'eliminado'      => 0,
                ]);
            }

            DB::commit();

            return response()->json(['ok' => true]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage()
            ]);
        }
    }


}
