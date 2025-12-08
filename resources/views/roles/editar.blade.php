@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title"><i class="fa-solid fa-id-card"></i> Editar Rol</h2>

    {{-- MENSAJE --}}
    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    {{-- FORM 1: DATOS --}}
    <form method="POST" action="{{ route('roles.actualizar', $rol->id_rol) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre *</label>
            <input type="text" name="nombre" required value="{{ old('nombre', $rol->nombre) }}">
        </div>

        <div class="form-group">
            <label>Descripción *</label>
            <textarea name="descripcion" rows="3" required>{{ old('descripcion', $rol->descripcion) }}</textarea>
        </div>

        <div class="form-actions">
            <button class="btn-primary">Guardar Datos</button>
        </div>
    </form>

    {{-- FORM 2: ASIGNAR TAREAS --}}
    <div class="accordion" style="margin-top: 25px;"> {{-- 🔹 Más separación --}}
        <div class="accordion-header" onclick="toggleAccordion('tareabox')">
            <span class="accordion-icon">📌</span> Asignar Tareas
        </div>

        <div id="tareabox" class="accordion-content">

            <form method="POST" action="{{ route('roles.tareas.guardar', $rol->id_rol) }}">
            @csrf

                <div style="position:relative; margin-bottom: 12px;">
                    <input type="text" id="buscar_tarea" class="search-input" placeholder="Buscar tareas...">
                    <div id="resultados_tareas" class="autocomplete-results"></div>
                </div>

                <div id="tareas_seleccionadas" class="roles-container" style="margin-bottom: 15px;">
                    @foreach($tareasAsignadas as $t)
                    <div class="rol-tag">
                        {{ $t->nombre }}
                        <input type="hidden" name="tareas[]" value="{{ $t->id_tarea }}">
                        <button type="button" class="btn-remove-rol" onclick="this.parentNode.remove()">✖</button>
                    </div>
                    @endforeach
                </div>

                <div class="form-actions">
                    <button class="btn-primary">Guardar Tareas</button>
                </div>

            </form>
        </div>
    </div>

    <a class="btn-secondary" style="margin-top: 20px; display:inline-block;" href="{{ route('roles.gestion') }}">
        Volver
    </a>

</div>

<script>
function toggleAccordion(id) {
    const box = document.getElementById(id);
    box.style.display = box.style.display === "block" ? "none" : "block";
}

document.getElementById("buscar_tarea").addEventListener("keyup", async function(){
    let query = this.value;
    if(query.length < 2) return;

    let response = await fetch(`/tareas/buscar?q=${query}`);
    let data = await response.json();
    let cont = document.getElementById("resultados_tareas");
    cont.innerHTML = '';

    data.forEach(item => {
        let div = document.createElement("div");
        div.classList.add("autocomplete-item");
        div.innerHTML = item.nombre;

        div.onclick = function(){
            document.getElementById("tareas_seleccionadas").innerHTML += `
                <div class='rol-tag'>
                    ${item.nombre}
                    <input type='hidden' name='tareas[]' value='${item.id_tarea}'>
                    <button type='button' class='btn-remove-rol' onclick='this.parentNode.remove()'>✖</button>
                </div>`;
            cont.innerHTML = '';
        }
        cont.appendChild(div);
    });
});
</script>

@endsection
