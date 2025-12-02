namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $primaryKey = 'id_modulo';
    protected $fillable = ['nombre', 'descripcion', 'inactivo', 'id_modulo_padre'];

    public function padre()
    {
        return $this->belongsTo(Modulo::class, 'id_modulo_padre');
    }

    public function hijos()
    {
        return $this->hasMany(Modulo::class, 'id_modulo_padre');
    }
}
