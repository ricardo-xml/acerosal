
@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-building"></i> Nueva Empresa
    </h2>

    @if(session('msg'))
        <div class="form-alert">
            {{ session('msg') }}
        </div>
    @endif

    <form method="POST" action="{{ route('empresa.insertar') }}">
        @csrf

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required value="{{ old('nombre') }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>NIT *</label>
                <input type="text" name="nit" required value="{{ old('nit') }}">
                @error('nit')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>NRC *</label>
                <input type="text" name="nrc" required value="{{ old('nrc') }}">
                @error('nrc')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Razón Social *</label>
            <input type="text" name="razon_social" required value="{{ old('razon_social') }}">
            @error('razon_social')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Dirección *</label>
            <textarea name="direccion" rows="3" required>{{ old('direccion') }}</textarea>
            @error('direccion')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono *</label>
                <input type="text" name="telefono" required value="{{ old('telefono') }}">
                @error('telefono')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Correo de Contacto *</label>
                <input type="email" name="correo_contacto" required value="{{ old('correo_contacto') }}">
                @error('correo_contacto')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar</button>
            <a class="btn-secondary" href="{{ route('empresa.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
