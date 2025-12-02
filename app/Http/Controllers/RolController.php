namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // LISTA (solo lectura)
    public function lista(Request $request)
    {
        $roles = Rol::where('inactivo', 0)
            ->when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->paginate(10);

        return view('roles.lista', compact('roles'));
    }

    // GESTION
    public function gestion(Request $request)
    {
        $roles = Rol::when($request->nombre, fn($q) => $q->where('nombre', 'LIKE', "%{$request->nombre}%"))
            ->paginate(10);

        return view('roles.gestion', compact('roles'));
    }

    public function nuevo()
    {
        return view('roles.crear');
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'descripcion' => 'required'
        ]);

        Rol::create($request->all());
        return back()->with('msg', 'Rol creado correctamente.');
    }

    public function editar($id)
    {
        $rol = Rol::findOrFail($id);
        return view('roles.editar', compact('rol'));
    }

    public function actualizar(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $rol->update($request->all());
        return back()->with('msg', 'Rol actualizado');
    }

    public function eliminar($id)
    {
        Rol::findOrFail($id)->update(['inactivo' => 1]);
        return back()->with('msg', 'Rol eliminado');
    }
}
