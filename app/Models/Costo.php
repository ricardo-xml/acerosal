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
}
