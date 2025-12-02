@extends('layouts.app')

@section('content')

<h2 class="erp-title">Nuevo Rol</h2>

@if(session('msg'))
<div class="form-alert">{{ session('msg') }}</div>
@endif

<form method="POST" action="{{ route('roles.guardar') }}" class="erp-form">
    @csrf

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control">
    </div>

    <div class="form-actions">
        <button class="btn-primary">Guardar</button>
    </div>
</form>

@endsection
