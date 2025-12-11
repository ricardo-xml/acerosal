<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\RolesUsuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /* ======================================================
        LISTA (solo lectura)
    ====================================================== */
    public function lista(Request $request)
    {
        $query = Usuario::where('inactivo', 0);

        if ($request->has('nombre') && $request->nombre != '') {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }

        if ($request->has('apellidos') && $request->apellidos != '') {
            $query->where('apellidos', 'LIKE', "%{$request->apellidos}%");
        }

        if ($request->has('email') && $request->email != '') {
            $query->where('email', 'LIKE', "%{$request->email}%");
        }

        if ($request->has('celular') && $request->celular != '') {
            $query->where('celular', 'LIKE', "%{$request->celular}%");
        }

        $usuarios = $query->paginate(10);

        return view('usuarios.lista', compact('usuarios'));
    }

    /* ======================================================
        GESTIÓN
    ====================================================== */
    public function gestion(Request $request)
    {
        $query = Usuario::where('inactivo', 0);

        if ($request->filled('nombre')) {
            $query->where('nombre', 'LIKE', "%{$request->nombre}%");
        }
        if ($request->filled('apellidos')) {
            $query->where('apellidos', 'LIKE', "%{$request->apellidos}%");
        }
        if ($request->filled('email')) {
            $query->where('email', 'LIKE', "%{$request->email}%");
        }
        if ($request->filled('celular')) {
            $query->where('celular', 'LIKE', "%{$request->celular}%");
        }

        $usuarios = $query->paginate(10);

        return view('usuarios.gestion', compact('usuarios'));
    }

    /* ======================================================
        FORM CREAR
    ====================================================== */
    public function nuevo()
    {
        return view('usuarios.crear');
    }

    /* ======================================================
        GUARDAR NUEVO
    ====================================================== */
    public function guardar(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:usuarios,username',
            'password' => 'required|min:4',
            'email' => 'required|email',
            'celular' => 'required'
        ]);

        Usuario::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'celular' => $request->celular,
            'inactivo' => 0
        ]);

        return redirect()->route('usuarios.lista')->with('msg', 'Usuario creado');
    }

    /* ======================================================
        EDITAR
    ====================================================== */
    public function editar($id)
    {
        $usuario = Usuario::findOrFail($id);

        // roles asignados activos
        $rolesAsignados = Rol::select('roles.id_rol', 'roles.nombre')
            ->join('roles_usuarios', 'roles.id_rol', '=', 'roles_usuarios.id_rol')
            ->where('roles_usuarios.id_usuario', $id)
            ->where('roles_usuarios.inactivo', 0)
            ->get();

        return view('usuarios.editar', compact('usuario', 'rolesAsignados'));
    }

    /* ======================================================
        ACTUALIZAR DATOS
    ====================================================== */
    public function actualizarDatos(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'celular' => 'required'
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'celular' => $request->celular
        ]);

        return redirect()->route('usuarios.editar', $id)->with('msg', 'Datos actualizados');
    }

    /* ======================================================
        ACTUALIZAR PASSWORD
    ====================================================== */
    public function actualizarPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:4|confirmed'
        ]);

        $usuario = Usuario::findOrFail($id);
        $usuario->password = Hash::make($request->password);
        $usuario->save();

        return redirect()->route('usuarios.editar', $id)->with('msg', 'Contraseña actualizada');
    }

    /* ======================================================
        GUARDAR ROLES
    ====================================================== */
    public function guardarRoles(Request $request, $id)
    {
        $user = Usuario::findOrFail($id);

        DB::table('roles_usuarios')
            ->where('id_usuario', $id)
            ->update(['inactivo' => 1]);

        if ($request->filled('roles')) {
            foreach ($request->roles as $idRol) {
                RolesUsuario::updateOrCreate(
                    ['id_usuario' => $id, 'id_rol' => $idRol],
                    ['inactivo' => 0]
                );
            }
        }

        return redirect()->route('usuarios.editar', $id)->with('msg', 'Roles asignados');
    }

    /* ======================================================
        BORRADO LÓGICO
    ====================================================== */
    public function eliminar($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->inactivo = 1;
        $usuario->save();

        return redirect()->route('usuarios.gestion')->with('msg', 'Usuario eliminado');
    }

    /* ======================================================
        DETALLE (Solo lectura)
    ====================================================== */
    public function detalle($id)
    {
        $usuario = Usuario::findOrFail($id);

        $roles = Rol::select('roles.nombre', 'roles.descripcion')
            ->join('roles_usuarios', 'roles.id_rol', '=', 'roles_usuarios.id_rol')
            ->where('roles_usuarios.id_usuario', $id)
            ->where('roles_usuarios.inactivo', 0)
            ->get();

        return view('usuarios.detalle', compact('usuario', 'roles'));
    }
}
