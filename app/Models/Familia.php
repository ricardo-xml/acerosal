<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = 'familias';
    protected $primaryKey = 'id_familia';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'detalle_color',
        'inactivo'
    ];

    protected $casts = [
        'inactivo' => 'boolean'
    ];

    // Relaciones
    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_familia');
    }

    public function compraFamilias()
    {
        return $this->hasMany(CompraFamilia::class, 'id_familia');
    }
}
