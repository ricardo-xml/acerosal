@extends('layouts.app')

@section('content')

<h2 class="erp-title">Nuevo Módulo</h2>

<form method="POST" action="{{ route('modulos.guardar') }}" class="erp-form">
    @csrf

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control">
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <input type="text" name="descripcion" class="form-control">
    </div>

    <div class="form-group">
        <label>Módulo Padre</label>
        <select name="id_modulo_padre" class="form-control">
            <option value="">(Ninguno)</option>
            @foreach($padres as $p)
                <option value="{{ $p->id_modulo }}">{{ $p->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-actions">
        <button class="btn-primary">Guardar</button>
    </div>
</form>

@endsection
