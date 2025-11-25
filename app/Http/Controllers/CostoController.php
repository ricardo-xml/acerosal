<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Costo;

class CostoController extends Controller
{
    /* Mostrar formulario */
    public function crear()
    {
        return view('formularios.costo');
    }

    /* Insertar */
    public function insertar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255'
        ]);

        Costo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'inactivo' => 0,
        ]);

        return redirect()->route('costo.lista')
            ->with('msg', '✅ Costo creado correctamente.');
    }

    /* Listar */
    public function lista(Request $request)
    {
        $nombre = $request->nombre;

        $costos = Costo::where('inactivo', 0)
            ->when($nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$nombre}%"))
            ->orderBy('nombre')
            ->paginate(10);

        return view('listas.listacosto', compact('costos', 'nombre'));
    }

    /* Editar */
    public function editar($id)
    {
        $costo = Costo::findOrFail($id);
        return view('formularios.editar_costo', compact('costo'));
    }

    /* Actualizar */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'descripcion' => 'required|string|max:255',
        ]);

        $costo = Costo::findOrFail($id);

        $costo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('costo.lista')
            ->with('msg', '✅ Costo actualizado correctamente.');
    }

    /* Eliminar (lógico) */
    public function eliminar($id)
    {
        $costo = Costo::findOrFail($id);
        $costo->update(['inactivo' => 1]);

        return redirect()->route('costo.lista')
            ->with('msg', '🗑️ Costo eliminado.');
    }
}
