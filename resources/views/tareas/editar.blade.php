@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-bars-staggered"></i> Editar Tarea
    </h2>

    {{-- MENSAJE --}}
    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    <form method="POST" action="{{ route('tareas.actualizar', $tarea->id_tarea) }}">
        @csrf
        @method('PUT')

        {{-- MÓDULO --}}
        <div class="form-group">
            <label>Módulo *</label>
            <select name="id_modulo" required>
                <option value="">Seleccione un módulo</option>
                @foreach($modulos as $m)
                    <option value="{{ $m->id_modulo }}"
                        {{ (string)old('id_modulo', $tarea->id_modulo) === (string)$m->id_modulo ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_modulo')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOMBRE --}}
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text"
                   name="nombre"
                   required
                   value="{{ old('nombre', $tarea->nombre) }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- DESCRIPCIÓN --}}
        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion', $tarea->descripcion) }}</textarea>
            @error('descripcion')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- RUTA --}}
        <div class="form-group">
            <label>Ruta</label>
            <input type="text"
                   name="ruta"
                   value="{{ old('ruta', $tarea->ruta) }}">
            @error('ruta')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ÍCONO --}}
        <div class="form-group">
            <label>Ícono</label>
            <input type="text"
                   name="icono"
                   value="{{ old('icono', $tarea->icono) }}">
            @error('icono')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ORDEN --}}
        <div class="form-group">
            <label>Orden *</label>
            <input type="number"
                   name="orden"
                   required
                   value="{{ old('orden', $tarea->orden) }}">
            @error('orden')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- VISIBLE --}}
        <div class="form-group">
            <label>
                <input type="hidden" name="visible" value="0">
                <input type="checkbox"
                       name="visible"
                       value="1"
                       {{ old('visible', $tarea->visible) ? 'checked' : '' }}>
                Visible en menú
            </label>
            @error('visible')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Actualizar</button>
            <a class="btn-secondary" href="{{ route('tareas.gestion') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
