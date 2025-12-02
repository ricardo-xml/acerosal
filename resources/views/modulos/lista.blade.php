@extends('layouts.app')

@section('content')

<h2 class="erp-title">Lista de Módulos</h2>

<form method="GET" action="{{ route('modulos.lista') }}" class="filtro-form">
    <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
    <button class="btn-primary">Buscar</button>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Módulo Padre</th>
        </tr>
    </thead>
    <tbody>
        @foreach($modulos as $m)
        <tr>
            <td>{{ $m->id_modulo }}</td>
            <td>{{ $m->nombre }}</td>
            <td>{{ $m->descripcion }}</td>
            <td>{{ optional($m->padre)->nombre }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $modulos->links() }}

@endsection
