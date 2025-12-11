<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesUsuario extends Model
{
    protected $table = 'roles_usuarios';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_usuario',
        'inactivo'
    ];
}
