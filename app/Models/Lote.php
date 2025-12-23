<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'codigo',
        'fecha_ingreso',
        'peso_total_libras',
        'unidad_medida_peso',
        'cantidad_total_metros',
        'unidad_medida_longitud',
        'relacion_cantidad_peso',
        'total_piezas',
        'eliminado',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'peso_total_libras' => 'float',
        'cantidad_total_metros' => 'float',
        'relacion_cantidad_peso' => 'float',
        'total_piezas' => 'integer',
        'eliminado' => 'boolean',
    ];

    /* =====================
     * Relaciones
     * ===================== */

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function piezas()
    {
        return $this->hasMany(Pieza::class, 'id_lote');
    }
}
