<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Costo extends Model
{
    protected $table = 'costos';
    protected $primaryKey = 'id_costo';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'inactivo'
    ];

    protected $casts = [
        'inactivo' => 'boolean'
    ];

    public function compraCostos()
    {
        return $this->hasMany(CompraCosto::class, 'id_costo');
    }
}
