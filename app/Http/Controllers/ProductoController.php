<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    /**
     * Obtener los productos de una familia (AJAX)
     */
    public function porFamilia($id)
    {
        // Retorna solo lo necesario para el select
        $productos = Producto::where('id_familia', $id)
            ->where('eliminado', 0)
            ->orderBy('descripcion')
            ->get(['id_producto', 'descripcion']);

        return response()->json($productos);
    }
}
