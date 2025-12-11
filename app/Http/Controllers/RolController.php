<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Tarea;
use App\Models\RolTarea;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // LISTA SOLO LECTURA
    public function lista(Request $request)
    {
        $query = Rol::where('inactivo', 0);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }

        $roles = $query->orderBy('nombre')->paginate(10);

        return view('roles.lista', compact('roles'));
    }

    // LISTA GESTION
    public function gestion(Request $request)
    {
        $query = Rol::where('inactivo', 0);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }

        $roles = $query->orderBy('nombre')->paginate(10);

        return view('roles.gestion', compact('roles'));
    }

    // NUEVO FORMULARIO
    public function nuevo()
    {
        return view('roles.crear');
    }

    // GUARDAR NUEVO
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        Rol::create($request->all());

        return redirect()->route('roles.lista')
            ->with('msg', 'Rol creado correctamente.');
    }

    // EDITAR
public function editar($id)
{
    $rol = Rol::findOrFail($id);

    // Tareas asignadas al rol (solo activas)
    $tareasAsignadas = $rol->tareas()
        ->where('roles_tareas.inactivo', 0)
        ->get();

    // Tareas disponibles para autocompletar
    $todasLasTareas = Tarea::where('inactivo', 0)->get(['id_tarea', 'nombre']);

    return view('roles.editar', compact('rol', 'tareasAsignadas', 'todasLasTareas'));
}

    // ACTUALIZAR DATOS GENERALES
    public function actualizar($id, Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required'
        ]);

        $rol = Rol::findOrFail($id);
        $rol->update($request->only('nombre', 'descripcion'));

        return back()->with('msg', 'Rol actualizado correctamente.');
    }

    // ACTUALIZAR TAREAS ASIGNADAS
    public function guardarTareas($id, Request $request)
    {
        $rol = Rol::findOrFail($id);

        $ids = $request->input('tareas', []);

        RolTarea::where('id_rol', $id)->update(['inactivo' => 1]);

        foreach ($ids as $idTarea) {
            RolTarea::updateOrCreate(
                ['id_rol' => $id, 'id_tarea' => $idTarea],
                ['inactivo' => 0]
            );
        }

        return back()->with('msg', 'Tareas asignadas correctamente.');
    }

    public function detalle($id)
    {
        $rol = Rol::findOrFail($id);

        // Obtener las tareas activas asignadas a este rol
        $tareas = $rol->tareas()->where('roles_tareas.inactivo', 0)->get();

        return view('roles.detalle', compact('rol', 'tareas'));
    }


    // BORRADO LÓGICO
    public function eliminar($id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update(['inactivo' => 1]);

        return back()->with('msg', 'Rol eliminado.');
    }


    public function buscar(Request $request)
    {
        $q = $request->q;

        return Rol::where('inactivo', 0)
            ->where('nombre', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get();
    }
}
