@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title"><i class="fa-solid fa-coins"></i> Nuevo Costo</h2>

    <form method="POST" action="{{ route('costo.insertar') }}">
        @csrf

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required value="{{ old('nombre') }}">
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" required rows="3">{{ old('descripcion') }}</textarea>
        </div>

        <div class="form-actions">
            <button class="btn-primary">Guardar</button>
            <a class="btn-secondary" href="{{ route('costo.lista') }}">Cancelar</a>
        </div>

    </form>

</div>

@endsection
