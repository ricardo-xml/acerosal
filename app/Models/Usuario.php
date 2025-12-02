<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'username',
        'password',
        'nombre',
        'apellidos',
        'email',
        'celular',
        'inactivo'
    ];

    public $timestamps = true; // Si no las usas, puedes cambiar a false

    // ------------------------------------------------------------
    // RELACIÓN MUCHOS A MUCHOS: ROLES
    // ------------------------------------------------------------
    public function roles()
    {
        return $this->belongsToMany(
            Rol::class,              // Modelo relacionado
            'usuarios_roles',       // Tabla pivote
            'id_usuario',           // FK en pivote a Usuario
            'id_rol'                // FK en pivote a Rol
        )->withPivot('inactivo')
         ->withTimestamps();
    }

    // ------------------------------------------------------------
    // OBTENER SOLO LOS ROLES ACTIVOS
    // ------------------------------------------------------------
    public function rolesActivos()
    {
        return $this->belongsToMany(
            Rol::class,
            'usuarios_roles',
            'id_usuario',
            'id_rol'
        )
        ->wherePivot('inactivo', 0);
    }

    // ------------------------------------------------------------
    // ¿TIENE UN ROL ESPECÍFICO?
    // ------------------------------------------------------------
    public function tieneRol($rolId)
    {
        return $this->rolesActivos()
            ->where('roles.id_rol', $rolId)
            ->exists();
    }

    // ------------------------------------------------------------
    // ¿TIENE ALGÚN ROL DE UNA LISTA?
    // ------------------------------------------------------------
    public function tieneAlgunRol(array $rolesIds)
    {
        return $this->rolesActivos()
            ->whereIn('roles.id_rol', $rolesIds)
            ->exists();
    }
}
