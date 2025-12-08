@extends('layouts.app')

@section('content')

<h2 class="erp-title">Detalle del Rol: {{ $rol->nombre }}</h2>

<div class="filter-box" style="font-size:15px;">
    <p><strong>Nombre:</strong> {{ $rol->nombre }}</p>
    <p><strong>Descripción:</strong> {{ $rol->descripcion }}</p>
</div>

<h3 class="erp-title">Tareas Asignadas</h3>

<table class="erp-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre Tarea</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tareas as $index => $t)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $t->nombre }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="no-results">Sin tareas asociadas</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:15px;">
    <a class="btn-secondary" href="{{ route('roles.lista') }}">Volver</a>
</div>

@endsection
