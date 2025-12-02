class Tarea extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id_tarea';
    protected $fillable = [
        'id_modulo', 'nombre', 'descripcion', 'ruta',
        'icono', 'orden', 'visible', 'inactivo'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo');
    }
}
