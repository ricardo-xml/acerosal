@extends('layouts.app')

@section('content')

<div class="erp-section">

    <h2 class="erp-title">
        <i class="fa-solid fa-users"></i> Usuarios — Solo Lectura
    </h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('usuarios.lista') }}" class="erp-filters">
        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}">
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" value="{{ request('username') }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="{{ request('email') }}">
            </div>

        </div>

        <div class="form-actions">
            <button class="btn-primary">Aplicar Filtros</button>
            <a href="{{ route('usuarios.lista') }}" class="btn-secondary">Limpiar</a>
        </div>
    </form>

    {{-- TABLA --}}
    <table class="erp-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Celular</th>
            </tr>
        </thead>

        <tbody>
            @forelse($usuarios as $u)
                <tr>
                    <td>{{ $u->id_usuario }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->nombre }} {{ $u->apellidos }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->celular }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Sin resultados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $usuarios->links() }}

</div>

@endsection
