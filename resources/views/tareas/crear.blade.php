@extends('layouts.app')

@section('content')

<h2 class="erp-title">Nueva Tarea</h2>

<form method="POST" action="{{ route('tareas.guardar') }}" class="erp-form">
    @csrf

    <div class="form-group">
        <label>Módulo</label>
        <select name="id_modulo" class="form-control">
            @foreach($modulos as $m)
                <option value="{{ $m->id_modulo }}">{{ $m->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control">
    </div>

    <div class="form-group">
        <label>Ruta</label>
        <input type="text" name="ruta" class="form-control">
    </div>

    <div class="form-group">
        <label>Ícono</label>
        <input type="text" name="icono" class="form-control">
    </div>

    <div class="form-group">
        <label>Orden</label>
        <input type="number" name="orden" class="form-control">
    </div>

    <div class="form-group">
        <label>Visible</label>
        <select name="visible" class="form-control">
            <option value="1">Visible</option>
            <option value="0">Oculta</option>
        </select>
    </div>

    <div class="form-actions">
        <button class="btn-primary">Guardar</button>
    </div>
</form>

@endsection
