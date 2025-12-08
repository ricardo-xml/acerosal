<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Modulo;

class TareaController extends Controller
{
    public function lista(Request $request)
    {
        $tareas = Tarea::where('inactivo', 0)
            ->when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->paginate(10);

        return view('tareas.lista', compact('tareas'));
    }

    public function gestion(Request $request)
    {
        $tareas = Tarea::when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->paginate(10);

        return view('tareas.gestion', compact('tareas'));
    }

    public function nuevo()
    {
        $modulos = Modulo::where('inactivo', 0)->get();
        return view('tareas.crear', compact('modulos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'id_modulo' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        Tarea::create($request->all());
        return back()->with('msg', 'Tarea creada');
    }

    public function editar($id)
    {
        $tarea = Tarea::findOrFail($id);
        $modulos = Modulo::where('inactivo', 0)->get();
        return view('tareas.editar', compact('tarea', 'modulos'));
    }

    public function actualizar(Request $request, $id)
    {
        Tarea::findOrFail($id)->update($request->all());
        return back()->with('msg', 'Tarea actualizada');
    }

    public function eliminar($id)
    {
        Tarea::findOrFail($id)->update(['inactivo' => 1]);
        return back()->with('msg', 'Tarea eliminada');
    }
}
