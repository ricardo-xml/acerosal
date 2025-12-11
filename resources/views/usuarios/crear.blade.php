@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
    </h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    <form method="POST" action="{{ route('usuarios.guardar') }}">
        @csrf

        <div class="form-group">
            <label>Usuario *</label>
            <input type="text" name="username" required value="{{ old('username') }}">
            @error('username')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña *</label>
                <input type="password" name="password" required>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Confirmar Contraseña *</label>
                <input type="password" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos" value="{{ old('apellidos') }}">
            @error('apellidos')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required value="{{ old('email') }}">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Celular *</label>
                <input type="text" name="celular" required value="{{ old('celular') }}">
                @error('celular')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar</button>
            <a class="btn-secondary" href="{{ route('usuarios.lista') }}">Cancelar</a>
        </div>
    </form>

</div>

@endsection
