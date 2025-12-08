@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-diagram-project"></i> Editar Módulo
    </h2>

    {{-- MENSAJE --}}
    @if(session('msg'))
        <div class="form-alert">
            {{ session('msg') }}
        </div>
    @endif

    <form method="POST" action="{{ route('modulos.actualizar', $modulo->id_modulo) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required 
                value="{{ old('nombre', $modulo->nombre) }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion', $modulo->descripcion) }}</textarea>
            @error('descripcion')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Módulo Padre</label>
            <select name="id_modulo_padre" class="form-control">
                <option value="">(Ninguno)</option>

                @foreach($padres as $p)
                    <option value="{{ $p->id_modulo }}"
                        {{ $modulo->id_modulo_padre == $p->id_modulo ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                @endforeach
            </select>

            @error('id_modulo_padre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Si más adelante quieres activar/desactivar --}}
        {{-- 
        <div class="form-group">
            <label>Estado</label>
            <select name="inactivo" class="form-control">
                <option value="0" {{ $modulo->inactivo == 0 ? 'selected' : '' }}>Activo</option>
                <option value="1" {{ $modulo->inactivo == 1 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        --}}

        <div class="form-actions">
            <button class="btn-primary" type="submit">Actualizar</button>
            <a class="btn-secondary" href="{{ route('modulos.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
