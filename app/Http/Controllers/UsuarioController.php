<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // ------------------------------------------------------------
    // LISTA – SOLO LECTURA
    // ------------------------------------------------------------
    public function lista(Request $request)
    {
        $usuarios = Usuario::where('inactivo', 0)
            ->when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->when($request->username, fn($q) => $q->where('username', 'LIKE', "%{$request->username}%"))
            ->when($request->email, fn($q) => $q->where('email', 'LIKE', "%{$request->email}%"))
            ->orderBy('id_usuario', 'desc')
            ->paginate(10);

        return view('usuarios.lista', compact('usuarios'));
    }

    // ------------------------------------------------------------
    // GESTIÓN – LISTA CON ACCIONES
    // ------------------------------------------------------------
    public function gestion(Request $request)
    {
        $usuarios = Usuario::where('inactivo', 0)
            ->when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->when($request->username, fn($q) => $q->where('username', 'LIKE', "%{$request->username}%"))
            ->when($request->email, fn($q) => $q->where('email', 'LIKE', "%{$request->email}%"))
            ->orderBy('id_usuario', 'desc')
            ->paginate(10);

        return view('usuarios.gestion', compact('usuarios'));
    }

    // ------------------------------------------------------------
    // FORMULARIO CREAR
    // ------------------------------------------------------------
    public function nuevo()
    {
        return view('usuarios.crear');
    }

    // ------------------------------------------------------------
    // INSERTAR NUEVO USUARIO
    // ------------------------------------------------------------
    public function guardar(Request $request)
    {
        $request->validate([
            'username'  => 'required|max:25|unique:usuarios,username',
            'password'  => 'required|min:6',
            'password2' => 'required|same:password',
            'email'     => 'required|email',
            'celular'   => 'required'
        ]);

        Usuario::create([
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'celular'   => $request->celular,
        ]);

        return redirect()->route('usuarios.gestion')
            ->with('msg', 'Usuario creado correctamente.');
    }

    // ------------------------------------------------------------
    // FORMULARIO EDITAR
    // ------------------------------------------------------------
    public function editar($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.editar', compact('usuario'));
    }

    // ------------------------------------------------------------
    // ACTUALIZAR DATOS
    // ------------------------------------------------------------
    public function actualizar(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'email'   => 'required|email',
            'celular' => 'required',
        ]);

        $usuario->update([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'celular'   => $request->celular,
        ]);

        return redirect()->route('usuarios.gestion')
            ->with('msg', 'Usuario actualizado correctamente.');
    }

    // ------------------------------------------------------------
    // BORRADO LÓGICO
    // ------------------------------------------------------------
    public function eliminar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['inactivo' => 1]);

        return redirect()->route('usuarios.gestion')
            ->with('msg', 'Usuario eliminado correctamente.');
    }

    // ------------------------------------------------------------
    // ASIGNAR ROLES (placeholder)
    // ------------------------------------------------------------
    public function roles($id)
    {
        $usuario = Usuario::findOrFail($id);

        // Aquí luego cargaremos roles actuales, roles disponibles, etc.
        return view('usuarios.roles', compact('usuario'));
    }
}
