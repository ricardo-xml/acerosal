@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-bars-staggered"></i> Nueva Tarea
    </h2>

    {{-- MENSAJE --}}
    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    <form method="POST" action="{{ route('tareas.guardar') }}">
        @csrf

        {{-- MÓDULO (NOT NULL) --}}
        <div class="form-group">
            <label>Módulo *</label>
            <select name="id_modulo" required>
                <option value="">Seleccione un módulo</option>
                @foreach($modulos as $m)
                    <option value="{{ $m->id_modulo }}"
                        {{ (string)old('id_modulo') === (string)$m->id_modulo ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_modulo')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- NOMBRE (NOT NULL) --}}
        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- DESCRIPCIÓN (NOT NULL) --}}
        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- RUTA (NULLABLE) --}}
        <div class="form-group">
            <label>Ruta</label>
            <input type="text" name="ruta" value="{{ old('ruta') }}">
            @error('ruta')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ÍCONO (NULLABLE) --}}
        <div class="form-group">
            <label>Ícono</label>
            <input type="text" name="icono" value="{{ old('icono') }}">
            @error('icono')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ORDEN (NOT NULL) --}}
        <div class="form-group">
            <label>Orden *</label>
            <input type="number" name="orden" required value="{{ old('orden') }}">
            @error('orden')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- VISIBLE (TINYINT, checkbox) --}}
        <div class="form-group">
            <label>
                <input type="hidden" name="visible" value="0">
                <input type="checkbox" name="visible" value="1"
                    {{ old('visible', 1) ? 'checked' : '' }}>
                Visible en menú
            </label>
            @error('visible')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar</button>
            <a class="btn-secondary" href="{{ route('tareas.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
