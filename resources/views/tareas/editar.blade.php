@extends('layouts.app')

@section('content')

<h2 class="erp-title">Editar Tarea</h2>

<form method="POST" action="{{ route('tareas.actualizar', $tarea->id_tarea) }}" class="erp-form">
    @csrf

    <div class="form-group">
        <label>Módulo</label>
        <select name="id_modulo" class="form-control">
            @foreach($modulos as $m)
                <option value="{{ $m->id_modulo }}"
                    {{ $tarea->id_modulo == $m->id_modulo ? 'selected' : '' }}>
                    {{ $m->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ $tarea->nombre }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" value="{{ $tarea->descripcion }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Ruta</label>
        <input type="text" name="ruta" value="{{ $tarea->ruta }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Ícono</label>
        <input type="text" name="icono" value="{{ $tarea->icono }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Orden</label>
        <input type="number" name="orden" value="{{ $tarea->orden }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Visible</label>
        <select name="visible" class="form-control">
            <option value="1" {{ $tarea->visible ? 'selected' : '' }}>Visible</option>
            <option value="0" {{ !$tarea->visible ? 'selected' : '' }}>Oculta</option>
        </select>
    </div>

    <div class="form-actions">
        <button class="btn-primary">Guardar Cambios</button>
    </div>
</form>

@endsection
