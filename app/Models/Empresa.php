<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    // Nombre REAL de la tabla
    protected $table = 'empresas';

    // Llave primaria REAL
    protected $primaryKey = 'id_empresa';

    // La tabla NO tiene created_at / updated_at
    public $timestamps = false;

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'nit',
        'nrc',
        'razon_social',
        'direccion',
        'telefono',
        'correo_contacto',
        'inactivo'
    ];
}
