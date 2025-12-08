@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-clipboard-list"></i> Nueva Tarea
    </h2>

    <form method="POST" action="{{ route('tareas.guardar') }}">
        @csrf

        {{-- Módulo --}}
        <div class="form-group">
            <label>Módulo *</label>
            <select name="id_modulo" class="form-control" required>
                <option value="">Seleccione...</option>
                @foreach($modulos as $m)
                    <option value="{{ $m->id_modulo }}">{{ $m->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Nombre --}}
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required placeholder="Ej: Gestión de usuarios">
        </div>

        {{-- Descripción --}}
        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="2" required placeholder="Descripción corta de la tarea"></textarea>
        </div>

        {{-- Fila de dos --}}
        <div class="form-row">
            <div class="form-group">
                <label>Ruta (opcional)</label>
                <input type="text" name="ruta" placeholder="Ej: usuarios/lista">
            </div>

            <div class="form-group">
                <label>Ícono (opcional)</label>
                <input type="text" name="icono" placeholder="fa-solid fa-user">
            </div>
        </div>

        {{-- Fila de dos --}}
        <div class="form-row">
            <div class="form-group">
                <label>Orden *</label>
                <input type="number" name="orden" required value="1">
            </div>

            <div class="form-group">
                <label>Visible *</label>
                <select name="visible" class="form-control" required>
                    <option value="1">Visible</option>
                    <option value="0">Oculta</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar</button>
            <a class="btn-secondary" href="{{ route('tareas.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
