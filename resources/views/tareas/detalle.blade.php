@extends('layouts.app')

@section('content')

<div class="panel-info">

    <h2 class="form-title">
        <i class="fa-solid fa-eye"></i> Detalle de Tarea
    </h2>

    <div>
        <div class="panel-label">Nombre</div>
        <div class="panel-value">{{ $tarea->nombre }}</div>
    </div>

    <div>
        <div class="panel-label">Descripción</div>
        <div class="panel-value">{{ $tarea->descripcion }}</div>
    </div>

    <div>
        <div class="panel-label">Módulo</div>
        <div class="panel-value">{{ optional($tarea->modulo)->nombre ?? '-' }}</div>
    </div>

    <div>
        <div class="panel-label">Ruta</div>
        <div class="panel-value">{{ $tarea->ruta ?? '-' }}</div>
    </div>

    <div>
        <div class="panel-label">Ícono</div>
        <div class="panel-value">{{ $tarea->icono ?? '-' }}</div>
    </div>

    <div>
        <div class="panel-label">Orden</div>
        <div class="panel-value">{{ $tarea->orden }}</div>
    </div>

    <div>
        <div class="panel-label">Visible</div>
        <div class="panel-value">{{ $tarea->visible ? 'Sí' : 'No' }}</div>
    </div>

    <div class="panel-actions">
        <a class="btn-secondary" href="{{ route('tareas.lista') }}">Volver a Lista</a>
        <a class="btn-primary" href="{{ route('tareas.gestion') }}">Ir a Gestión</a>
    </div>

</div>

@endsection