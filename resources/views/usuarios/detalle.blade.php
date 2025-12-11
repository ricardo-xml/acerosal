@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-eye"></i> Detalle de Usuario
    </h2>

    <div class="form-group">
        <label>Usuario</label>
        <div>{{ $usuario->username }}</div>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <div>{{ $usuario->nombre }}</div>
    </div>

    <div class="form-group">
        <label>Apellidos</label>
        <div>{{ $usuario->apellidos }}</div>
    </div>

    <div class="form-group">
        <label>Email</label>
        <div>{{ $usuario->email }}</div>
    </div>

    <div class="form-group">
        <label>Celular</label>
        <div>{{ $usuario->celular }}</div>
    </div>

    <h3 class="form-title" style="font-size:18px; margin-top:20px;">
        <i class="fa-solid fa-id-card"></i> Roles asignados
    </h3>

    @if($roles->isEmpty())
        <p class="no-results">Este usuario no tiene roles asignados.</p>
    @else
        <div class="erp-table-container" style="margin-top:10px;">
            <table class="erp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Rol</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $i => $rol)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $rol->nombre }}</td>
                        <td>{{ $rol->descripcion }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="form-actions" style="margin-top: 18px;">
        <a class="btn-secondary" href="{{ route('usuarios.lista') }}">Volver a Lista</a>
        <a class="btn-primary" href="{{ route('usuarios.gestion') }}">Ir a Gestión</a>
    </div>

</div>

@endsection
