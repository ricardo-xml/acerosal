<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    public $timestamps = false;

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

    protected $casts = [
        'inactivo' => 'boolean'
    ];

    public function compras()
    {
        return $this->hasMany(Compra::class, 'id_empresa');
    }
}

