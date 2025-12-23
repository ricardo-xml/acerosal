<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventarioAjustesController extends Controller
{
    private const ORIGEN = 'ajustes de inventario';

    /* =========================================================
     * LISTA LOTES (desde vw_lotes_stock) + filtros
     * ========================================================= */
    public function lotesIndex(Request $request)
    {
        $q      = trim((string)$request->query('q', ''));
        $estado = $request->query('estado'); // Disponible|Parcial|Agotado
        $idProd = $request->query('id_producto');
        $idFam  = $request->query('id_familia');
        $desde  = $request->query('desde'); // YYYY-MM-DD
        $hasta  = $request->query('hasta'); // YYYY-MM-DD

        $rows = DB::table('vw_lotes_stock');

        if ($q !== '') {
            $rows->where(function($w) use ($q){
                $w->where('codigo_lote', 'like', "%{$q}%")
                  ->orWhere('codigo_producto', 'like', "%{$q}%")
                  ->orWhere('descripcion_producto', 'like', "%{$q}%");
            });
        }
        if ($estado) {
            $rows->where('estado_lote', $estado);
        }
        if ($idProd) {
            $rows->where('id_producto', (int)$idProd);
        }
        if ($idFam) {
            // id_familia no está en la vista; se filtra vía productos
            $rows->whereIn('id_producto', function($sub) use ($idFam){
                $sub->select('id_producto')->from('productos')->where('id_familia', (int)$idFam)->where('eliminado', 0);
            });
        }
        if ($desde) $rows->whereDate('fecha_ingreso', '>=', $desde);
        if ($hasta) $rows->whereDate('fecha_ingreso', '<=', $hasta);

        $lotes = $rows->orderByDesc('fecha_ingreso')->paginate(20)->withQueryString();

        $familias = DB::table('familias')->where('inactivo', 0)->orderBy('descripcion')->get();
        $productos = DB::table('productos')->where('eliminado', 0)->orderBy('codigo')->get();

        return view('inventario.lotes_lista', compact('lotes','familias','productos'));
    }

    /* =========================================================
     * LISTA PIEZAS (tabla piezas) + filtros + barcode visible
     * ========================================================= */
    public function piezasIndex(Request $request)
    {
        $q      = trim((string)$request->query('q', ''));
        $idProd = $request->query('id_producto');
        $idLote = $request->query('id_lote');
        $estado = $request->query('estado'); // Disponible|Retirada|Finalizada

        $rows = DB::table('piezas as pc')
            ->join('lotes as l', 'l.id_lote', '=', 'pc.id_lote')
            ->join('productos as p', 'p.id_producto', '=', 'pc.id_producto')
            ->where('pc.eliminado', 0)
            ->where('l.eliminado', 0)
            ->where('p.eliminado', 0)
            ->select([
                'pc.id_pieza','pc.id_lote','pc.id_producto',
                'pc.codigo',
                'pc.peso_libras_inicial','pc.cantidad_metros_inicial',
                'pc.peso_libras_actual','pc.cantidad_metros_actual',
                'pc.peso_libras_recortados','pc.cantidad_metros_recortados',
                'pc.retirado','pc.finalizado',
                'l.codigo as codigo_lote','l.fecha_ingreso',
                'p.codigo as codigo_producto','p.descripcion as descripcion_producto',
            ]);

        if ($q !== '') {
            $rows->where(function($w) use ($q){
                $w->where('pc.codigo', 'like', "%{$q}%")
                  ->orWhere('l.codigo', 'like', "%{$q}%")
                  ->orWhere('p.codigo', 'like', "%{$q}%")
                  ->orWhere('p.descripcion', 'like', "%{$q}%");
            });
        }
        if ($idProd) $rows->where('pc.id_producto', (int)$idProd);
        if ($idLote) $rows->where('pc.id_lote', (int)$idLote);

        if ($estado) {
            if ($estado === 'Disponible') {
                $rows->where('pc.retirado', 0)->where('pc.finalizado', 0);
            } elseif ($estado === 'Retirada') {
                $rows->where('pc.retirado', 1);
            } elseif ($estado === 'Finalizada') {
                $rows->where('pc.finalizado', 1);
            }
        }

        $piezas = $rows->orderByDesc('pc.id_pieza')->paginate(20)->withQueryString();

        $productos = DB::table('productos')->where('eliminado', 0)->orderBy('codigo')->get();
        $lotes = DB::table('lotes')->where('eliminado', 0)->orderByDesc('fecha_ingreso')->get();

        return view('inventario.piezas_lista', compact('piezas','productos','lotes'));
    }

    /* =========================================================
     * PANTALLA AJUSTES (carga lote + piezas)
     * Si llega pieza_id => resaltar
     * ========================================================= */
    public function show(Request $request, int $id_lote)
    {
        $id_pieza_resaltar = $request->query('pieza'); // opcional

        $lote = DB::table('lotes as l')
            ->join('productos as p', 'p.id_producto', '=', 'l.id_producto')
            ->where('l.id_lote', $id_lote)
            ->where('l.eliminado', 0)
            ->where('p.eliminado', 0)
            ->select([
                'l.*',
                'p.codigo as codigo_producto',
                'p.descripcion as descripcion_producto',
            ])
            ->first();

        abort_if(!$lote, 404);

        $piezas = DB::table('piezas')
            ->where('id_lote', $id_lote)
            ->where('eliminado', 0)
            ->orderBy('id_pieza')
            ->get();

        return view('inventario.ajustes', compact('lote','piezas','id_pieza_resaltar'));
    }

    /* =========================================================
     * UPDATE LOTE (totales editables)
     * No genera movimiento (según tu regla)
     * ========================================================= */
    public function updateLote(Request $request, int $id_lote)
    {
        $data = $request->only([
            'codigo','fecha_ingreso',
            'peso_total_libras','cantidad_total_metros',
            'relacion_cantidad_peso','total_piezas',
            'unidad_medida_peso','unidad_medida_longitud',
        ]);

        $v = Validator::make($data, [
            'codigo' => 'required|string|max:10',
            'fecha_ingreso' => 'required|date',
            'peso_total_libras' => 'required|numeric',
            'cantidad_total_metros' => 'required|numeric',
            'relacion_cantidad_peso' => 'required|numeric',
            'total_piezas' => 'required|integer',
            'unidad_medida_peso' => 'required|string|max:20',
            'unidad_medida_longitud' => 'required|string|max:20',
        ]);

        if ($v->fails()) {
            return response()->json(['ok'=>false,'errors'=>$v->errors()], 422);
        }

        $updated = DB::table('lotes')
            ->where('id_lote', $id_lote)
            ->where('eliminado', 0)
            ->update($data);

        return response()->json(['ok'=>true,'updated'=>$updated]);
    }

    /* =========================================================
     * UPDATE PIEZA (valida inicial = actual + recortado)
     * Genera movimiento: "Se editaron los valores..."
     * ========================================================= */
    public function updatePieza(Request $request, int $id_pieza)
    {
        $data = $request->only([
            'peso_libras_inicial','cantidad_metros_inicial',
            'peso_libras_actual','cantidad_metros_actual',
            'peso_libras_recortados','cantidad_metros_recortados',
        ]);

        $v = Validator::make($data, [
            'peso_libras_inicial' => 'required|numeric',
            'cantidad_metros_inicial' => 'required|numeric',
            'peso_libras_actual' => 'required|numeric',
            'cantidad_metros_actual' => 'required|numeric',
            'peso_libras_recortados' => 'required|numeric',
            'cantidad_metros_recortados' => 'required|numeric',
        ]);

        if ($v->fails()) {
            return response()->json(['ok'=>false,'errors'=>$v->errors()], 422);
        }

        // Regla estricta: inicial = actual + recortado (lb y m)
        $lb_ok = abs((float)$data['peso_libras_inicial'] - ((float)$data['peso_libras_actual'] + (float)$data['peso_libras_recortados'])) < 0.0001;
        $m_ok  = abs((float)$data['cantidad_metros_inicial'] - ((float)$data['cantidad_metros_actual'] + (float)$data['cantidad_metros_recortados'])) < 0.0001;

        if (!$lb_ok || !$m_ok) {
            return response()->json([
                'ok'=>false,
                'message'=>'Validación: inicial debe ser igual a actual + recortado (libras y metros).'
            ], 422);
        }

        $pieza = DB::table('piezas')->where('id_pieza', $id_pieza)->where('eliminado', 0)->first();
        abort_if(!$pieza, 404);

        DB::beginTransaction();
        try {
            DB::table('piezas')->where('id_pieza', $id_pieza)->update($data);

            $this->insertMovimiento([
                'id_pieza' => $id_pieza,
                'tipo' => 'salida',
                'id_usuario' => auth()->id() ?? 1,
                'comentario' => "Se editaron los valores de la pieza {$pieza->codigo}",
            ]);

            DB::commit();
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    /* =========================================================
     * RETIRAR PIEZA (retirado=1) + movimiento con causa
     * ========================================================= */
    public function retirarPieza(Request $request, int $id_pieza)
    {
        $causa = trim((string)$request->input('causa',''));
        if ($causa === '') {
            return response()->json(['ok'=>false,'message'=>'Debe indicar la causa del retiro.'], 422);
        }

        $pieza = DB::table('piezas')->where('id_pieza', $id_pieza)->where('eliminado', 0)->first();
        abort_if(!$pieza, 404);

        DB::beginTransaction();
        try {
            DB::table('piezas')->where('id_pieza', $id_pieza)->update(['retirado'=>1]);

            $this->insertMovimiento([
                'id_pieza' => $id_pieza,
                'tipo' => 'salida',
                'id_usuario' => auth()->id() ?? 1,
                'comentario' => "Se retiró la pieza {$pieza->codigo} - {$causa}",
            ]);

            DB::commit();
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    /* =========================================================
     * ELIMINAR PIEZA (eliminado=1) + movimiento
     * ========================================================= */
    public function eliminarPieza(Request $request, int $id_pieza)
    {
        $pieza = DB::table('piezas')->where('id_pieza', $id_pieza)->where('eliminado', 0)->first();
        abort_if(!$pieza, 404);

        DB::beginTransaction();
        try {
            DB::table('piezas')->where('id_pieza', $id_pieza)->update(['eliminado'=>1]);

            $this->insertMovimiento([
                'id_pieza' => $id_pieza,
                'tipo' => 'salida',
                'id_usuario' => auth()->id() ?? 1,
                'comentario' => "Se eliminó la pieza {$pieza->codigo}",
            ]);

            DB::commit();
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    /* =========================================================
     * ELIMINAR LOTE (eliminado lógico + piezas en cascada lógica)
     * movimiento por cada pieza: "..., por eliminación del lote"
     * ========================================================= */
    public function eliminarLote(Request $request, int $id_lote)
    {
        $lote = DB::table('lotes')->where('id_lote', $id_lote)->where('eliminado', 0)->first();
        abort_if(!$lote, 404);

        $piezas = DB::table('piezas')->where('id_lote', $id_lote)->where('eliminado', 0)->get();

        DB::beginTransaction();
        try {
            // Marcar lote eliminado
            DB::table('lotes')->where('id_lote', $id_lote)->update(['eliminado'=>1]);

            // Cascada lógica de piezas + movimiento por pieza
            foreach ($piezas as $pz) {
                DB::table('piezas')->where('id_pieza', $pz->id_pieza)->update(['eliminado'=>1]);

                $this->insertMovimiento([
                    'id_pieza' => $pz->id_pieza,
                    'tipo' => 'salida',
                    'id_usuario' => auth()->id() ?? 1,
                    'comentario' => "Se eliminó la pieza {$pz->codigo}, por eliminación del lote",
                ]);
            }

            DB::commit();
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['ok'=>false,'message'=>$e->getMessage()], 500);
        }
    }

    /* =========================================================
     * BARCODE PDF (Carta)
     * ========================================================= */
    public function barcodePdf(int $id_pieza)
    {
        $pieza = DB::table('piezas')
            ->where('id_pieza', $id_pieza)
            ->where('eliminado', 0)
            ->first();

        abort_if(!$pieza, 404);

        $pdf = \PDF::loadView('inventario.barcode_pdf', [
            'pieza' => $pieza
        ])->setPaper('letter', 'portrait');

        return $pdf->download("barcode_pieza_{$pieza->codigo}.pdf");
    }


    /* =========================================================
     * Helpers
     * ========================================================= */
    private function insertMovimiento(array $payload): void
    {
        DB::table('movimientos_inventario')->insert([
            'id_pieza' => $payload['id_pieza'] ?? null,
            'id_corte' => $payload['id_corte'] ?? null,
            'id_compra' => $payload['id_compra'] ?? null,
            'origen' => self::ORIGEN,
            'tipo' => $payload['tipo'],
            'cantidad' => 0,
            'peso' => 0,
            'saldo_metros' => 0,
            'saldo_libras' => 0,
            'id_usuario' => $payload['id_usuario'],
            'comentario' => $payload['comentario'] ?? null,
            'eliminado' => 0,
        ]);
    }



}

