<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CostoController;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\TareaController;


Route::get('/login', [LoginController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [LoginController::class, 'procesarLogin']);
Route::get('/logout', [LoginController::class, 'logout']);

Route::middleware('auth.custom')->group(function () {
    Route::get('/dashboard', function () {
        return view('inicio');
    });
});

// Dashboard

// Compras
Route::get('/compras/nueva', fn() => 'nueva compra')->name('compras.nueva');

// Productos
Route::get('/productos', fn() => 'lista productos')->name('productos.lista');
Route::get('/productos/nuevo', fn() => 'nuevo producto')->name('productos.nuevo');

// Proveedores
Route::get('/proveedores', fn() => 'lista proveedores')->name('proveedores.lista');

// Usuarios
Route::get('/usuarios', fn() => 'lista usuarios')->name('usuarios.lista');
Route::get('/usuarios/nuevo', fn() => 'nuevo usuario')->name('usuarios.nuevo');

// Inventario
Route::get('/inventario', fn() => 'inventario')->name('inventario.form');

// Roles
Route::get('/roles', fn() => 'lista roles')->name('roles.lista');

// Empresas
// Mostrar formulario
Route::get('/empresa/nueva', [EmpresaController::class, 'crear'])
    ->name('empresa.nueva');

// Guardar datos
Route::post('/empresa/insertar', [EmpresaController::class, 'insertar'])
    ->name('empresa.insertar');

// Listado
Route::get('/empresa/lista/', [EmpresaController::class, 'lista'])
    ->name('empresa.lista');

route::get('/empresa/editar/{id}', [EmpresaController::class, 'editar'])
    ->name('empresa.editar');

Route::put('/empresa/actualizar/{id}', [EmpresaController::class, 'actualizar'])
    ->name('empresa.actualizar');

Route::delete('/empresa/eliminar/{id}', [EmpresaController::class, 'eliminar'])
    ->name('empresa.eliminar');

// Empresas fin

Route::get('/costos', [CostoController::class, 'lista'])->name('costo.lista');
Route::get('/costos/nuevo', [CostoController::class, 'crear'])->name('costo.nuevo');
Route::post('/costos/insertar', [CostoController::class, 'insertar'])->name('costo.insertar');

Route::get('/costos/editar/{id}', [CostoController::class, 'editar'])->name('costo.editar');
Route::put('/costos/actualizar/{id}', [CostoController::class, 'actualizar'])->name('costo.actualizar');

Route::delete('/costos/eliminar/{id}', [CostoController::class, 'eliminar'])->name('costo.eliminar');

// ==========================================================
//  USUARIOS
// ==========================================================
Route::prefix('usuarios')->group(function () {

    // LISTAS
    Route::get('/lista', [UsuarioController::class, 'lista'])
        ->name('usuarios.lista');

    Route::get('/gestion', [UsuarioController::class, 'gestion'])
        ->name('usuarios.gestion');

    // CRUD BÁSICO
    Route::get('/nuevo', [UsuarioController::class, 'nuevo'])
        ->name('usuarios.nuevo');

    Route::post('/guardar', [UsuarioController::class, 'guardar'])
        ->name('usuarios.guardar');

    Route::get('/editar/{id}', [UsuarioController::class, 'editar'])
        ->name('usuarios.editar');

    // Guardar datos generales
    Route::post('/actualizar-datos/{id}', [UsuarioController::class, 'actualizarDatos'])
        ->name('usuarios.actualizar.datos');

    // Guardar contraseña
    Route::post('/actualizar-password/{id}', [UsuarioController::class, 'actualizarPassword'])
        ->name('usuarios.actualizar.password');

    // Borrado lógico
    Route::get('/eliminar/{id}', [UsuarioController::class, 'eliminar'])
        ->name('usuarios.eliminar');

    // ROLES PARA USUARIO
    Route::post('/roles/{id}', [UsuarioController::class, 'guardarRoles'])
        ->name('usuarios.roles.guardar');
});

// Buscador AJAX de Roles
Route::get('/roles/buscar', function(Request $request) {
    return \App\Models\Rol::where('inactivo', 0)
        ->where('nombre', 'LIKE', "%{$request->q}%")
        ->limit(10)
        ->get();
})->name('roles.buscar');


// ==========================================================
//  ROLES
// ==========================================================
Route::prefix('roles')->group(function () {

    Route::get('/lista', [RolController::class, 'lista'])
        ->name('roles.lista');

    Route::get('/gestion', [RolController::class, 'gestion'])
        ->name('roles.gestion');

    Route::get('/nuevo', [RolController::class, 'nuevo'])
        ->name('roles.nuevo');

    Route::post('/guardar', [RolController::class, 'guardar'])
        ->name('roles.guardar');

    Route::get('/editar/{id}', [RolController::class, 'editar'])
        ->name('roles.editar');

    Route::post('/actualizar/{id}', [RolController::class, 'actualizar'])
        ->name('roles.actualizar');

    Route::get('/eliminar/{id}', [RolController::class, 'eliminar'])
        ->name('roles.eliminar');
});


// ==========================================================
//  MODULOS
// ==========================================================
Route::prefix('modulos')->group(function () {

    Route::get('/lista', [ModuloController::class, 'lista'])
        ->name('modulos.lista');

    Route::get('/gestion', [ModuloController::class, 'gestion'])
        ->name('modulos.gestion');

    Route::get('/nuevo', [ModuloController::class, 'nuevo'])
        ->name('modulos.nuevo');

    Route::post('/guardar', [ModuloController::class, 'guardar'])
        ->name('modulos.guardar');

    Route::get('/editar/{id}', [ModuloController::class, 'editar'])
        ->name('modulos.editar');

    Route::post('/actualizar/{id}', [ModuloController::class, 'actualizar'])
        ->name('modulos.actualizar');

    Route::get('/eliminar/{id}', [ModuloController::class, 'eliminar'])
        ->name('modulos.eliminar');
});


// ==========================================================
//  TAREAS
// ==========================================================
Route::prefix('tareas')->group(function () {

    Route::get('/lista', [TareaController::class, 'lista'])
        ->name('tareas.lista');

    Route::get('/gestion', [TareaController::class, 'gestion'])
        ->name('tareas.gestion');

    Route::get('/nuevo', [TareaController::class, 'nuevo'])
        ->name('tareas.nuevo');

    Route::post('/guardar', [TareaController::class, 'guardar'])
        ->name('tareas.guardar');

    Route::get('/editar/{id}', [TareaController::class, 'editar'])
        ->name('tareas.editar');

    Route::post('/actualizar/{id}', [TareaController::class, 'actualizar'])
        ->name('tareas.actualizar');

    Route::get('/eliminar/{id}', [TareaController::class, 'eliminar'])
        ->name('tareas.eliminar');
});



// -------- FORMULARIOS ----------
Route::get('/familia/nueva', fn() => 'Formulario: Nueva Familia')->name('familia.nueva');
//Route::get('/costo/nuevo', fn() => 'Formulario: Nuevo Costo')->name('costo.nuevo');
Route::get('/modulo/nuevo', fn() => 'Formulario: Nuevo Modulo')->name('modulo.nuevo');
Route::get('/producto/nuevo', fn() => 'Formulario: Nuevo Producto')->name('producto.nuevo');
Route::get('/tarea/nueva', fn() => 'Formulario: Nueva Tarea')->name('tarea.nueva');
Route::get('/usuario/nuevo', fn() => 'Formulario: Nuevo Usuario')->name('usuario.nuevo');
Route::get('/rol/nuevo', fn() => 'Formulario: Nuevo Rol')->name('rol.nuevo');
Route::get('/proveedor/nuevo', fn() => 'Formulario: Nuevo Proveedor')->name('proveedor.nuevo');
Route::get('/compra/nueva', fn() => 'Formulario: Nueva Compra')->name('compra.nueva');
Route::get('/inventario/nuevo', fn() => 'Formulario: Inventario')->name('inventario.nuevo');
Route::get('/inventario/manual', fn() => 'Formulario: Inventario Manual')->name('inventario.manual');

// -------- LISTAS ----------
Route::get('/familia/lista', fn() => 'Lista: Familias')->name('familia.lista');
//Route::get('/costo/lista', fn() => 'Lista: Costos')->name('costo.lista');
Route::get('/modulo/lista', fn() => 'Lista: Modulos')->name('modulo.lista');
Route::get('/producto/lista', fn() => 'Lista: Productos')->name('producto.lista');
Route::get('/tarea/lista', fn() => 'Lista: Tareas')->name('tarea.lista');
Route::get('/usuario/lista', fn() => 'Lista: Usuarios')->name('usuario.lista');
Route::get('/rol/lista', fn() => 'Lista: Roles')->name('rol.lista');
Route::get('/proveedor/lista', fn() => 'Lista: Proveedores')->name('proveedor.lista');

// -------- GESTIONES ----------
Route::get('/familia/gestion', fn() => 'Gestionar: Familias')->name('familia.gestion');
Route::get('/costo/gestion', fn() => 'Gestionar: Costos')->name('costo.gestion');
Route::get('/modulo/gestion', fn() => 'Gestionar: Modulos')->name('modulo.gestion');
Route::get('/producto/gestion', fn() => 'Gestionar: Productos')->name('producto.gestion');
Route::get('/tarea/gestion', fn() => 'Gestionar: Tareas')->name('tarea.gestion');
Route::get('/usuario/gestion', fn() => 'Gestionar: Usuarios')->name('usuario.gestion');
Route::get('/rol/gestion', fn() => 'Gestionar: Roles')->name('rol.gestion');
Route::get('/proveedor/gestion', fn() => 'Gestionar: Proveedores')->name('proveedor.gestion');




    