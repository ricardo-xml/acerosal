<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolTarea extends Model
{
    protected $table = 'roles_tareas';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = [
        'id_rol',
        'id_tarea'
    ];

    protected $fillable = [
        'id_rol',
        'id_tarea',
        'inactivo'
    ];
}
