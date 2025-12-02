@extends('layouts.app')

@section('content')

<h2 class="erp-title">Editar Rol</h2>

@if(session('msg'))
<div class="form-alert">{{ session('msg') }}</div>
@endif

<form method="POST" action="{{ route('roles.actualizar', $rol->id_rol) }}" class="erp-form">
    @csrf

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ $rol->nombre }}" class="form-control">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" value="{{ $rol->descripcion }}" class="form-control">
    </div>

    <div class="form-actions">
        <button class="btn-primary">Guardar Cambios</button>
    </div>
</form>

@endsection
