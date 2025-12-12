<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CostoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\KardexGlobalController;
use App\Http\Controllers\InventarioManualController;

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

        // LISTAS
        Route::get('/lista', [UsuarioController::class, 'lista'])
            ->name('usuarios.lista');        // solo lectura

        Route::get('/gestion', [UsuarioController::class, 'gestion'])
            ->name('usuarios.gestion');      // editar / eliminar

        Route::get('/admin', [UsuarioController::class, 'admin'])
            ->name('usuarios.admin');        // incluye eliminados ( para futuro )

        // NUEVO / GUARDAR
        Route::get('/nuevo', [UsuarioController::class, 'crear'])
            ->name('usuarios.nuevo');

        Route::post('/guardar', [UsuarioController::class, 'guardar'])
            ->name('usuarios.guardar');

        // DETALLE (texto plano + lista de roles)
        Route::get('/detalle/{id}', [UsuarioController::class, 'detalle'])
            ->name('usuarios.detalle');

        // EDITAR DATOS
        Route::get('/editar/{id}', [UsuarioController::class, 'editar'])
            ->name('usuarios.editar');

        Route::put('/actualizar/{id}', [UsuarioController::class, 'actualizarDatos'])
            ->name('usuarios.actualizar.datos');

        // CAMBIAR PASSWORD (form aparte)
        Route::put('/password/{id}', [UsuarioController::class, 'actualizarPassword'])
            ->name('usuarios.actualizar.password');

        // BORRADO LÓGICO / RESTAURAR
        Route::get('/eliminar/{id}', [UsuarioController::class, 'eliminar'])
            ->name('usuarios.eliminar');

        Route::get('/restaurar/{id}', [UsuarioController::class, 'restaurar'])
            ->name('usuarios.restaurar');

        // ROLES (formularios de “tablillas”)
        Route::post('/roles/{id}', [UsuarioController::class, 'guardarRoles'])
            ->name('usuarios.roles.guardar');
    });

    // Buscador AJAX de Roles (ya lo venías usando)
    Route::get('/roles/buscar', [RolController::class, 'buscar'])
        ->name('roles.buscar');


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

    // ==========================================================
    //  TAREAS
    // ==========================================================
    Route::prefix('tareas')->group(function () {

        Route::get('/lista', [TareaController::class, 'lista'])->name('tareas.lista');
        Route::get('/gestion', [TareaController::class, 'gestion'])->name('tareas.gestion');

        // Vista futura Admin
        Route::get('/admin', [TareaController::class, 'admin'])->name('tareas.admin'); 

        Route::get('/nuevo', [TareaController::class, 'nuevo'])->name('tareas.nuevo');
        Route::post('/guardar', [TareaController::class, 'guardar'])->name('tareas.guardar');

        Route::get('/editar/{id}', [TareaController::class, 'editar'])->name('tareas.editar');
        Route::put('/actualizar/{id}', [TareaController::class, 'actualizar'])->name('tareas.actualizar');

        Route::get('/detalle/{id}', [TareaController::class, 'detalle'])->name('tareas.detalle');

        Route::get('/eliminar/{id}', [TareaController::class, 'eliminar'])->name('tareas.eliminar');
        Route::get('/restaurar/{id}', [TareaController::class, 'restaurar'])->name('tareas.restaurar');
    });

    // Buscador AJAX para asignación de roles
    Route::get('/tareas/buscar', function(Request $request) {
        return \App\Models\Tarea::where('inactivo', 0)
            ->where('nombre', 'LIKE', "%{$request->q}%")
            ->limit(10)
            ->get();
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


  
    /*
    |--------------------------------------------------------------------------
    | COMPRAS
    |--------------------------------------------------------------------------
    */  

    Route::prefix('compras')->name('compras.')->group(function () {

        // LISTA PRINCIPAL (solo activas)
        Route::get('/', [CompraController::class, 'index'])->name('lista');

        // CREAR
        Route::get('/nueva', [CompraController::class, 'create'])->name('nueva');
        Route::post('/guardar', [CompraController::class, 'store'])->name('store');

        // DETALLE
        Route::get('/detalle/{id}', [CompraController::class, 'detalle'])->name('detalle');

        // ELIMINAR / ANULAR
        Route::post('/eliminar/{id}', [CompraController::class, 'eliminar'])->name('eliminar');

        // SOLO ADMIN – VER ANULADAS
        Route::get('/anuladas', [CompraController::class, 'anuladas'])
            ->middleware('admin')
            ->name('anuladas');

    });
        
     // AJAX – Obtener productos por familia
        Route::get('/productos/familia/{id}', [ProductoController::class, 'porFamilia'])
         ->name('productos.porFamilia');

    /*
    |--------------------------------------------------------------------------
    | Inventario
    |--------------------------------------------------------------------------
    */  
    Route::prefix('inventario')->name('inventario.')->group(function () {

        // Formulario automático
        Route::get('/automatico', [InventarioController::class, 'automatico'])
            ->name('automatico');

        // AJAX detalle compra
        Route::get('/compra/{id}/detalle', [InventarioController::class, 'detalleCompra'])
            ->name('detalle-compra');

        // Validar código lote
        Route::get('/verificar-codigo-lote', [InventarioController::class, 'verificarCodigoLote'])
            ->name('verificar-codigo-lote');

        // Guardar inventario
        Route::post('/automatico/guardar', [InventarioController::class, 'guardarAutomatico'])
            ->name('guardar-automatico');
    });


    /*
    |--------------------------------------------------------------------------
    | KARDEX
    |--------------------------------------------------------------------------
    */  
    
    Route::prefix('inventario')->name('inventario.')->group(function () {

        // ... rutas que ya tienes (automático, etc.)

        // Kardex
        Route::get('/kardex', [KardexController::class, 'index'])->name('kardex.index');

        // Autocomplete por código de pieza
        Route::get('/kardex/buscar-pieza', [KardexController::class, 'buscarPieza'])
            ->name('kardex.buscar-pieza');

        // Datos del kardex (JSON)
        Route::get('/kardex/pieza/{id}', [KardexController::class, 'obtenerKardex'])
            ->name('kardex.obtener');

        // Exportar a PDF
        Route::get('/kardex/pieza/{id}/pdf', [KardexController::class, 'exportarPdf'])
            ->name('kardex.pdf');

        // Kardex Global
        Route::get('/kardex-global', [KardexGlobalController::class, 'index'])
            ->name('kardex-global.index');

        Route::get('/kardex-global/datos', [KardexGlobalController::class, 'datos'])
            ->name('kardex-global.datos');

        Route::get('/kardex-global/pdf', [KardexGlobalController::class, 'exportarPdf'])
            ->name('kardex-global.pdf');
    });

Route::get('/inventario/manual', [InventarioManualController::class, 'index'])
    ->name('inventario.manual');

Route::post('/inventario/manual/guardar', [InventarioManualController::class, 'guardar']);

// FORMULARIOS
Route::view('/familia/nueva', 'placeholder')->name('familia.nueva');
Route::view('/producto/nuevo', 'placeholder')->name('producto.nuevo');
Route::view('/proveedor/nuevo', 'placeholder')->name('proveedor.nuevo');
//Route::view('/inventario/nuevo', 'placeholder')->name('inventario.nuevo');
//Route::view('/inventario/manual', 'placeholder')->name('inventario.manual');

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
