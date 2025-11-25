<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    /* Mostrar formulario */
    public function crear()
    {
        return view('formularios.empresa');
    }

    /* Insertar nueva empresa */
    public function insertar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:25',
            'nrc' => 'nullable|string|max:25',
            'razon_social' => 'nullable|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'correo_contacto' => 'nullable|email|max:100',
        ]);

        Empresa::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'nrc' => $request->nrc,
            'razon_social' => $request->razon_social,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo_contacto' => $request->correo_contacto,
            'inactivo' => 0
        ]);

        return redirect()->route('empresa.lista')
            ->with('msg', '✅ Empresa creada correctamente.');
    }

    /* Listar empresas */
    public function lista(Request $request)
    {
        // Capturar filtros
        $nombre = $request->nombre;
        $nit = $request->nit;
        $nrc = $request->nrc;
        $correo = $request->correo;
        $telefono = $request->telefono;

        // Consulta dinámica
        $empresas = Empresa::where('inactivo', 0)
            ->when($nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$nombre}%"))
            ->when($nit, fn($q) => $q->where('nit', 'LIKE', "%{$nit}%"))
            ->when($nrc, fn($q) => $q->where('nrc', 'LIKE', "%{$nrc}%"))
            ->when($correo, fn($q) => $q->where('correo_contacto', 'LIKE', "%{$correo}%"))
            ->when($telefono, fn($q) => $q->where('telefono', 'LIKE', "%{$telefono}%"))
            ->orderBy('nombre')
            ->paginate(10);

        $empresas->appends($request->all());

        return view('listas.listaempresa', compact('empresas', 'nombre', 'nit', 'nrc', 'correo', 'telefono'));
    }

    /* Mostrar formulario de edición */
    public function editar($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('formularios.editar_empresa', compact('empresa'));
    }

    /* Actualizar empresa */
    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:25',
            'nrc' => 'nullable|string|max:25',
            'razon_social' => 'nullable|string|max:255',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:15',
            'correo_contacto' => 'nullable|email|max:100',
        ]);

        $empresa = Empresa::findOrFail($id);

        $empresa->update([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'nrc' => $request->nrc,
            'razon_social' => $request->razon_social,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo_contacto' => $request->correo_contacto,
        ]);

        return redirect()->route('empresa.lista')
            ->with('msg', '✅ Empresa actualizada correctamente.');
    }

    /* Eliminación lógica */
    public function eliminar($id)
    {
        $empresa = Empresa::findOrFail($id);

        $empresa->update([
            'inactivo' => 1
        ]);

        return redirect()->route('empresa.lista')
            ->with('msg', '🗑️ Empresa eliminada.');
    }
}
