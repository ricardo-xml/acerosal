@extends('layouts.app')

@section('content')

<div class="erp-section">

    <h2 class="erp-title"><i class="fa fa-user-plus"></i> Nuevo Usuario</h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    <form class="erp-form" method="POST" action="{{ route('usuarios.guardar') }}">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Username*</label>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}">
                @error('username') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password*</label>
                <input type="password" class="form-control" name="password">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Repetir Password*</label>
                <input type="password" class="form-control" name="password2">
                @error('password2') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control" name="nombre" value="{{ old('nombre') }}">
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" class="form-control" name="apellidos" value="{{ old('apellidos') }}">
            </div>

            <div class="form-group">
                <label>Email*</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Celular*</label>
                <input type="text" class="form-control" name="celular" value="{{ old('celular') }}">
                @error('celular') <div class="form-error">{{ $message }}</div> @enderror
            </div>

        </div>

        <div class="form-actions">
            <button class="btn-primary">Guardar</button>
            <a class="btn-secondary" href="{{ route('usuarios.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
