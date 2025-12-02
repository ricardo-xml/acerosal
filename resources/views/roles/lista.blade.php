@extends('layouts.app')

@section('content')

<h2 class="erp-title"><i class="fa-solid fa-id-badge"></i> Lista de Roles</h2>

<form method="GET" action="{{ route('roles.lista') }}" class="filtro-form">
    <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
    <button class="btn-primary">Buscar</button>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $r)
        <tr>
            <td>{{ $r->id_rol }}</td>
            <td>{{ $r->nombre }}</td>
            <td>{{ $r->descripcion }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $roles->links() }}

@endsection
