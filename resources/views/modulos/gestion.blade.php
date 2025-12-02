@extends('layouts.app')

@section('content')

<h2 class="erp-title">Gestión de Módulos</h2>

<div class="erp-actions">
    <a href="{{ route('modulos.nuevo') }}" class="btn-primary">Nuevo Módulo</a>
</div>

<form method="GET" action="{{ route('modulos.gestion') }}" class="filtro-form">
    <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
    <button class="btn-primary">Buscar</button>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Padre</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($modulos as $m)
        <tr>
            <td>{{ $m->id_modulo }}</td>
            <td>{{ $m->nombre }}</td>
            <td>{{ optional($m->padre)->nombre }}</td>
            <td>
                <a href="{{ route('modulos.editar', $m->id_modulo) }}" class="btn-secondary">Editar</a>
                <a href="{{ route('modulos.eliminar', $m->id_modulo) }}" class="btn-danger"
                    onclick="return confirm('¿Eliminar módulo?')">Eliminar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $modulos->links() }}

@endsection
