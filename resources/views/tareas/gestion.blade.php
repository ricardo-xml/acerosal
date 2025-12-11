@extends('layouts.app')

@section('content')

<h2 class="erp-title">Gestión de Tareas</h2>

{{-- ACCIONES --}}
<div class="erp-actions" style="margin-bottom: 10px;">
    <a href="{{ route('tareas.nuevo') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Nueva Tarea
    </a>
</div>

{{-- FILTROS --}}
<form method="GET" action="{{ route('tareas.gestion') }}" class="erp-search-form">
    <div class="search-row">
        {{-- filtro por nombre --}}
        <input type="text"
               name="nombre"
               class="search-input"
               placeholder="Nombre"
               value="{{ request('nombre') }}">

        {{-- filtro por módulo --}}
        <select name="id_modulo" class="search-input">
            <option value="">Todos los módulos</option>
            @foreach($modulos as $mod)
                <option value="{{ $mod->id_modulo }}"
                    {{ (string)request('id_modulo') === (string)$mod->id_modulo ? 'selected' : '' }}>
                    {{ $mod->nombre }}
                </option>
            @endforeach
        </select>

        <button class="btn-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Módulo</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($tareas as $index => $t)
        <tr>
            <td>{{ ($tareas->currentPage() - 1) * $tareas->perPage() + $index + 1 }}</td>

            <td>{{ $t->nombre }}</td>
            <td>{{ $t->descripcion }}</td>
            <td>{{ optional($t->modulo)->nombre ?? '-' }}</td>

            <td class="erp-actions-cell">
                <a href="{{ route('tareas.detalle', $t->id_tarea) }}"
                   class="btn-table btn-edit">
                    <i class="fa-solid fa-eye"></i>
                </a>

                <a href="{{ route('tareas.editar', $t->id_tarea) }}"
                   class="btn-table btn-edit">
                    <i class="fa-solid fa-pen"></i>
                </a>

                <form method="POST"
                      action="{{ route('tareas.eliminar', $t->id_tarea) }}"
                      style="display:inline;"
                      onsubmit="return confirm('¿Eliminar esta tarea (borrado lógico)?')">
                    @csrf
                    {{-- si la ruta la definiste como DELETE, añade @method('DELETE') --}}
                    @method('DELETE')
                    <button type="submit" class="btn-table btn-delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="no-results">No hay tareas para gestionar.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($tareas->hasPages())
    <div style="margin-top:12px;">
        {{ $tareas->links() }}
    </div>
@endif

@endsection
