@extends('layouts.app')

@section('content')

<h2 class="erp-title">Gestión de Tareas</h2>

<div class="erp-actions">
    <a href="{{ route('tareas.nuevo') }}" class="btn-primary">Nueva Tarea</a>
</div>

<form method="GET" action="{{ route('tareas.gestion') }}" class="filtro-form">
    <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
    <button class="btn-primary">Buscar</button>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Módulo</th>
            <th>Nombre</th>
            <th>Ruta</th>
            <th>Orden</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($tareas as $t)
        <tr>
            <td>{{ $t->id_tarea }}</td>
            <td>{{ $t->modulo->nombre }}</td>
            <td>{{ $t->nombre }}</td>
            <td>{{ $t->ruta }}</td>
            <td>{{ $t->orden }}</td>
            <td>
                <a href="{{ route('tareas.editar', $t->id_tarea) }}" class="btn-secondary">Editar</a>
                <a href="{{ route('tareas.eliminar', $t->id_tarea) }}" class="btn-danger"
                    onclick="return confirm('¿Eliminar tarea?')">Eliminar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $tareas->links() }}

@endsection
