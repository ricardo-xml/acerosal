@extends('layouts.app')

@section('content')

<h2 class="erp-title">Lista de Tareas</h2>

<form method="GET" action="{{ route('tareas.lista') }}" class="filtro-form">
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
        </tr>
        @endforeach
    </tbody>
</table>

{{ $tareas->links() }}

@endsection
