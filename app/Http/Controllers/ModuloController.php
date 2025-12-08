<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Modulo;

class ModuloController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTA SOLO LECTURA
    |--------------------------------------------------------------------------
    */
    public function lista()
    {
        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->paginate(110);
        return view('modulos.lista', compact('modulos'));
    }

    /*
    |--------------------------------------------------------------------------
    | GESTION (con acciones)
    |--------------------------------------------------------------------------
    */
    public function gestion()
    {
        $modulos = Modulo::where('inactivo', 0)->orderBy('nombre')->paginate(110);
        return view('modulos.gestion', compact('modulos'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO NUEVO
    |--------------------------------------------------------------------------
    */
    public function nuevo()
    {
        $modulosPadre = Modulo::whereNull('id_modulo_padre')->orWhere('id_modulo_padre', 0)->get();
        return view('modulos.crear', compact('modulosPadre'));
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR NUEVO
    |--------------------------------------------------------------------------
    */
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'id_modulo_padre' => 'nullable|integer'
        ]);

        Modulo::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_modulo_padre' => $request->id_modulo_padre,
            'inactivo' => 0
        ]);

        return redirect()->route('modulos.gestion')->with('msg', 'Módulo creado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | FORMULARIO EDITAR
    |--------------------------------------------------------------------------
    */
    public function editar($id)
    {
        $modulo = Modulo::findOrFail($id);

        // Modulos disponibles como padre (solo activos y excluyendo el mismo)
        $padres = Modulo::where('inactivo', 0)
            ->where('id_modulo', '!=', $id)
            ->orderBy('nombre')
            ->get();

        return view('modulos.editar', compact('modulo', 'padres'));
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'nullable|max:255',
            'id_modulo_padre' => 'nullable|integer'
        ]);

        $modulo = Modulo::findOrFail($id);

        $modulo->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'id_modulo_padre' => $request->id_modulo_padre
        ]);

        return redirect()->route('modulos.gestion')->with('msg', 'Módulo actualizado correctamente.');
    }

    /*
    |--------------------------------------------------------------------------
    | BORRADO LÓGICO
    |--------------------------------------------------------------------------
    */
    public function eliminar($id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->update(['inactivo' => 1]);

        return redirect()->route('modulos.gestion')->with('msg', 'Módulo eliminado correctamente.');
    }
}
