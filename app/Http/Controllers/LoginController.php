<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function mostrarLogin()
    {
        // Cargar empresas ACTIVAS (inactivo = 0)
        $empresas = DB::table('empresas')
            ->where('inactivo', 0)
            ->orderBy('nombre', 'ASC')
            ->get();

        return view('login', compact('empresas'));
    }

    public function procesarLogin(Request $request)
    {
        // Validación
        $request->validate([
            'usuario' => 'required',
            'password' => 'required',
            'empresa' => 'required',
        ], [
            'usuario.required' => 'El usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'empresa.required' => 'Debe seleccionar una empresa.',
        ]);

        // Buscar usuario por username (nuevo campo)
        $user = DB::table('usuarios')
            ->where('username', $request->usuario)
            ->first();

        if (!$user) {
            return back()->with('mensaje', '❌ Usuario o contraseña incorrectos.');
        }

        // Validar estado (inactivo = 1)
        if ($user->inactivo == 1) {
            return back()->with('mensaje', '❌ Usuario inactivo.');
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('mensaje', '❌ Usuario o contraseña incorrectos.');
        }

        // Validar empresa seleccionada
        $empresa = DB::table('empresas')
            ->where('id_empresa', $request->empresa)
            ->first();

        if (!$empresa) {
            return back()->with('mensaje', '❌ Empresa no válida.');
        }

        if ($empresa->inactivo == 1) {
            return back()->with('mensaje', '❌ Empresa inactiva.');
        }

        // Guardar sesión en Laravel
        Session::put('idUsuario', $user->id_usuario);
        Session::put('nombreUsuario', $user->username);
        Session::put('idEmpresa', $empresa->id_empresa);
        Session::put('nombreEmpresa', $empresa->nombre);

        return redirect('/dashboard');
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}

