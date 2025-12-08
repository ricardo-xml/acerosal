@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-diagram-project"></i> Nuevo Módulo
    </h2>

    {{-- MENSAJE --}}
    @if(session('msg'))
        <div class="form-alert">
            {{ session('msg') }}
        </div>
    @endif

    <form method="POST" action="{{ route('modulos.guardar') }}">
        @csrf

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Módulo Padre</label>
            <select name="id_modulo_padre" class="form-control">
                <option value="">(Ninguno)</option>
                @foreach($modulosPadre as $p)
                    <option value="{{ $p->id_modulo }}">
                        {{ $p->nombre }}
                    </option>
                @endforeach
            </select>
            @error('id_modulo_padre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar</button>
            <a class="btn-secondary" href="{{ route('modulos.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
