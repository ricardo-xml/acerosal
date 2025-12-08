<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CostoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\TareaController;


/*
|--------------------------------------------------------------------------
| LOGIN / LOGOUT
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [LoginController::class, 'procesarLogin']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS POR AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
Route::middleware('auth.custom')->group(function () {

Route::get('/dashboard', function () {
    return view('inicio');
})->name('dashboard');
Route::get('/', function () {
    return redirect()->route('dashboard');
});


    /*
    |--------------------------------------------------------------------------
    | EMPRESAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('empresa')->group(function () {
        Route::get('/lista', [EmpresaController::class, 'lista'])->name('empresa.lista');
        Route::get('/nuevo', [EmpresaController::class, 'crear'])->name('empresa.nueva');
        Route::post('/guardar', [EmpresaController::class, 'insertar'])->name('empresa.insertar');

        Route::get('/editar/{id}', [EmpresaController::class, 'editar'])->name('empresa.editar');
        Route::put('/actualizar/{id}', [EmpresaController::class, 'actualizar'])->name('empresa.actualizar');

        Route::delete('/eliminar/{id}', [EmpresaController::class, 'eliminar'])->name('empresa.eliminar');
    });


    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('usuarios')->group(function () {

        // Listas
        Route::get('/lista', [UsuarioController::class, 'lista'])->name('usuarios.lista');
        Route::get('/gestion', [UsuarioController::class, 'gestion'])->name('usuarios.gestion');

        // CRUD
        Route::get('/nuevo', [UsuarioController::class, 'nuevo'])->name('usuarios.nuevo');
        Route::post('/guardar', [UsuarioController::class, 'guardar'])->name('usuarios.guardar');

        Route::get('/editar/{id}', [UsuarioController::class, 'editar'])->name('usuarios.editar');

        Route::post('/actualizar-datos/{id}', [UsuarioController::class, 'actualizarDatos'])->name('usuarios.actualizar.datos');
        Route::post('/actualizar-password/{id}', [UsuarioController::class, 'actualizarPassword'])->name('usuarios.actualizar.password');

        Route::get('/eliminar/{id}', [UsuarioController::class, 'eliminar'])->name('usuarios.eliminar');

        // Roles
        Route::post('/roles/{id}', [UsuarioController::class, 'guardarRoles'])
            ->name('usuarios.roles.guardar');
    });

    // AJAX Buscar Rol
    Route::get('/roles/buscar', [RolController::class, 'buscar'])->name('roles.buscar');



// =====================================================
// ROLES
// =====================================================
        Route::prefix('roles')->group(function () {

            Route::get('/lista', [RolController::class, 'lista'])->name('roles.lista');
            Route::get('/gestion', [RolController::class, 'gestion'])->name('roles.gestion');

            Route::get('/nuevo', [RolController::class, 'nuevo'])->name('roles.nuevo');
            Route::post('/guardar', [RolController::class, 'guardar'])->name('roles.guardar');

            Route::get('/editar/{id}', [RolController::class, 'editar'])->name('roles.editar');
            Route::put('/actualizar/{id}', [RolController::class, 'actualizar'])->name('roles.actualizar');

            Route::post('/tareas/{id}', [RolController::class, 'guardarTareas'])->name('roles.tareas.guardar');

            Route::get('/eliminar/{id}', [RolController::class, 'eliminar'])->name('roles.eliminar');

            Route::get('/detalle/{id}', [RolController::class, 'detalle'])->name('roles.detalle');
        });


Route::get('/tareas/buscar', function(Request $request) {
    return \App\Models\Tarea::where('inactivo', 0)
        ->where('nombre', 'LIKE', "%{$request->q}%")
        ->limit(10)
        ->get();
})->name('tareas.buscar');
    /*
    |--------------------------------------------------------------------------
    | MÓDULOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('modulos')->group(function () {
        Route::get('/lista', [ModuloController::class, 'lista'])->name('modulos.lista');
        Route::get('/gestion', [ModuloController::class, 'gestion'])->name('modulos.gestion');

        Route::get('/nuevo', [ModuloController::class, 'nuevo'])->name('modulos.nuevo');
        Route::post('/guardar', [ModuloController::class, 'guardar'])->name('modulos.guardar');

        Route::get('/editar/{id}', [ModuloController::class, 'editar'])->name('modulos.editar');
        Route::put('/actualizar/{id}', [ModuloController::class, 'actualizar'])->name('modulos.actualizar');

        Route::get('/eliminar/{id}', [ModuloController::class, 'eliminar'])->name('modulos.eliminar');

        Route::get('/admin', [ModuloController::class, 'admin'])->name('modulos.admin');

        Route::get('/restaurar/{id}', [ModuloController::class, 'restaurar'])->name('modulos.restaurar');
    });


    /*
    |--------------------------------------------------------------------------
    | TAREAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('tareas')->group(function () {
        Route::get('/lista', [TareaController::class, 'lista'])->name('tareas.lista');
        Route::get('/gestion', [TareaController::class, 'gestion'])->name('tareas.gestion');

        Route::get('/nuevo', [TareaController::class, 'nuevo'])->name('tareas.nuevo');
        Route::post('/guardar', [TareaController::class, 'guardar'])->name('tareas.guardar');

        Route::get('/editar/{id}', [TareaController::class, 'editar'])->name('tareas.editar');
        Route::post('/actualizar/{id}', [TareaController::class, 'actualizar'])->name('tareas.actualizar');

        Route::get('/eliminar/{id}', [TareaController::class, 'eliminar'])->name('tareas.eliminar');
    });


    /*
    |--------------------------------------------------------------------------
    | COSTOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('costo')->group(function () {
        Route::get('/lista', [CostoController::class, 'lista'])->name('costo.lista');
        Route::get('/nuevo', [CostoController::class, 'crear'])->name('costo.nuevo');
        Route::post('/guardar', [CostoController::class, 'insertar'])->name('costo.insertar');

        Route::get('/editar/{id}', [CostoController::class, 'editar'])->name('costo.editar');
        Route::put('/actualizar/{id}', [CostoController::class, 'actualizar'])->name('costo.actualizar');

        Route::delete('/eliminar/{id}', [CostoController::class, 'eliminar'])->name('costo.eliminar');
    });


    
// FORMULARIOS
Route::view('/familia/nueva', 'placeholder')->name('familia.nueva');
Route::view('/producto/nuevo', 'placeholder')->name('producto.nuevo');
Route::view('/proveedor/nuevo', 'placeholder')->name('proveedor.nuevo');
Route::view('/compra/nueva', 'placeholder')->name('compra.nueva');
Route::view('/inventario/nuevo', 'placeholder')->name('inventario.nuevo');
Route::view('/inventario/manual', 'placeholder')->name('inventario.manual');

// LISTAS
Route::view('/familia/lista', 'placeholder')->name('familia.lista');
Route::view('/producto/lista', 'placeholder')->name('producto.lista');
Route::view('/proveedor/lista', 'placeholder')->name('proveedor.lista');

// GESTIÓN
Route::view('/familia/gestion', 'placeholder')->name('familia.gestion');
Route::view('/producto/gestion', 'placeholder')->name('producto.gestion');
Route::view('/proveedor/gestion', 'placeholder')->name('proveedor.gestion'); 
Route::view('/costo/gestion', 'placeholder')->name('costo.gestion'); 




});
