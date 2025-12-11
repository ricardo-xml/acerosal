<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'id_familia',
        'codigo',
        'descripcion',
        'unidad_medida',
        'milimetros',
        'pulgadas',
        'pulgadas_decimal',
        'tolerancia',
        'peso_lb_mts',
        'precio_venta_sin_iva',
        'precio_fijo',
        'eliminado'
    ];

    protected $casts = [
        'milimetros' => 'double',
        'pulgadas_decimal' => 'double',
        'tolerancia' => 'double',
        'peso_lb_mts' => 'double',
        'precio_venta_sin_iva' => 'decimal:2',
        'precio_fijo' => 'boolean',
        'eliminado' => 'boolean'
    ];

    public function familia()
    {
        return $this->belongsTo(Familia::class, 'id_familia');
    }

    public function compraProductos()
    {
        return $this->hasMany(CompraProducto::class, 'id_producto');
    }
}
