<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Modulo;

class TareaController extends Controller
{
    public function lista(Request $request)
    {
        $query = Tarea::where('inactivo', 0);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        if ($request->filled('id_modulo')) {
            $query->where('id_modulo', $request->id_modulo);
        }

        $tareas = $query->orderBy('orden')->paginate(10);

        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->get();

        return view('tareas.lista', compact('tareas', 'modulos'));
    }


    public function gestion(Request $request)
    {
        $query = Tarea::where('inactivo', 0);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        }

        if ($request->filled('id_modulo')) {
            $query->where('id_modulo', $request->id_modulo);
        }

        $tareas = $query->orderBy('orden')->paginate(10);
        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->get();

        return view('tareas.gestion', compact('tareas', 'modulos'));
    }


    public function admin(Request $request)
    {
        $query = Tarea::with('modulo');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }

        $tareas = $query->paginate(10);

        return view('tareas.admin', compact('tareas'));
    }

    public function detalle($id)
    {
        $tarea = Tarea::with('modulo')->findOrFail($id);

        return view('tareas.detalle', compact('tarea'));
    }

    public function nuevo()
    {
        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->get();
        return view('tareas.crear', compact('modulos'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'id_modulo' => 'required',
            'nombre' => 'required',
            'descripcion' => 'required',
        ]);

        Tarea::create([
            'id_modulo' => $request->id_modulo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'ruta' => $request->ruta,
            'icono' => $request->icono,
            'orden' => $request->orden ?? 0,
            'visible' => $request->visible ? 1 : 0,
        ]);

        return redirect()->route('tareas.gestion')->with('msg', 'Tarea creada');
    }

    public function editar($id)
    {
        $tarea = Tarea::findOrFail($id);
        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->get();

        return view('tareas.editar', compact('tarea', 'modulos'));
    }

    public function actualizar(Request $request, $id)
    {
        $tarea = Tarea::findOrFail($id);

        $tarea->update([
            'id_modulo' => $request->id_modulo,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'ruta' => $request->ruta,
            'icono' => $request->icono,
            'orden' => $request->orden ?? 0,
            'visible' => $request->visible ? 1 : 0,
        ]);

        return redirect()->route('tareas.gestion')->with('msg', 'Tarea actualizada');
    }

    public function eliminar($id)
    {
        Tarea::where('id_tarea', $id)->update(['inactivo' => 1]);
        return redirect()->route('tareas.gestion')->with('msg', 'Tarea eliminada');
    }

    public function restaurar($id)
    {
        Tarea::where('id_tarea', $id)->update(['inactivo' => 0]);
        return redirect()->route('tareas.admin')->with('msg', 'Tarea restaurada');
    }
}
