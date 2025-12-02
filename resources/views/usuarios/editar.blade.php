@extends('layouts.app')

@section('content')

@php
    $seccion = request('seccion'); // datos / password / roles
@endphp

<div class="erp-section">

    <h2 class="erp-title">
        <i class="fa-solid fa-user-pen"></i> Editar Usuario — {{ $usuario->username }}
    </h2>

    {{-- MENSAJES --}}
    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    {{-- -------------------------------------------------- --}}
    {{-- SECCIÓN 1: DATOS GENERALES --}}
    {{-- -------------------------------------------------- --}}
    <div class="accordion">

        <div class="accordion-header" data-target="datos">
            <span class="accordion-icon">{{ $seccion === 'datos' ? '▼' : '▶' }}</span>
            <span class="accordion-title">Datos Generales</span>
        </div>

        <div id="accordion-datos"
             class="accordion-content"
             style="display: {{ $seccion === 'datos' ? 'block' : 'none' }}">

            <form class="erp-form" method="POST"
                action="{{ route('usuarios.actualizar.datos', $usuario->id_usuario) }}">
                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" value="{{ $usuario->username }}" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" class="form-control"
                               value="{{ old('nombre', $usuario->nombre) }}">
                    </div>

                    <div class="form-group">
                        <label>Apellidos</label>
                        <input type="text" name="apellidos" class="form-control"
                               value="{{ old('apellidos', $usuario->apellidos) }}">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $usuario->email) }}">
                    </div>

                    <div class="form-group">
                        <label>Celular</label>
                        <input type="text" name="celular" class="form-control"
                               value="{{ old('celular', $usuario->celular) }}">
                    </div>

                </div>

                <div class="form-actions">
                    <button class="btn-primary">Guardar Datos</button>
                </div>

            </form>

        </div>
    </div>

    {{-- -------------------------------------------------- --}}
    {{-- SECCIÓN 2: CONTRASEÑA --}}
    {{-- -------------------------------------------------- --}}
    <div class="accordion">

        <div class="accordion-header" data-target="password">
            <span class="accordion-icon">{{ $seccion === 'password' ? '▼' : '▶' }}</span>
            <span class="accordion-title">Cambiar Contraseña</span>
        </div>

        <div id="accordion-password"
             class="accordion-content"
             style="display: {{ $seccion === 'password' ? 'block' : 'none' }}">

            <form class="erp-form" method="POST"
                action="{{ route('usuarios.actualizar.password', $usuario->id_usuario) }}">
                @csrf

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" name="password2" class="form-control">
                    </div>

                </div>

                <div class="form-actions">
                    <button class="btn-primary">Actualizar Contraseña</button>
                </div>

            </form>

        </div>
    </div>

    {{-- -------------------------------------------------- --}}
    {{-- SECCIÓN 3: ROLES --}}
    {{-- -------------------------------------------------- --}}
    <div class="accordion">

        <div class="accordion-header" data-target="roles">
            <span class="accordion-icon">{{ $seccion === 'roles' ? '▼' : '▶' }}</span>
            <span class="accordion-title">Asignar Roles</span>
        </div>

        <div id="accordion-roles"
             class="accordion-content"
             style="display: {{ $seccion === 'roles' ? 'block' : 'none' }}">

            <form class="erp-form" method="POST"
                action="{{ route('usuarios.roles.guardar', $usuario->id_usuario) }}">
                @csrf

                {{-- BUSCADOR --}}
                <div class="form-group" style="position:relative;">
                    <label>Buscar rol</label>
                    <input type="text" id="buscadorRol" class="form-control"
                           placeholder="Escriba para buscar...">
                    <div id="resultadosRoles" class="autocomplete-results"></div>
                </div>

                {{-- ROLES ACTUALES --}}
                <div class="form-group">
                    <label>Roles asignados</label>
                    <div id="contenedorRoles" class="roles-container">

                        @foreach($rolesAsignados as $rol)
                            <div class="rol-tag" data-id="{{ $rol->id_rol }}">
                                {{ $rol->nombre }}
                                <input type="hidden" name="roles[]" value="{{ $rol->id_rol }}">
                                <button type="button" class="btn-remove-rol">✖</button>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn-primary">Guardar Roles</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection


{{-- ------------------------------------------------------------ --}}
{{-- JS PARA ACORDEONES + BUSQUEDA DE ROLES --}}
{{-- ------------------------------------------------------------ --}}
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    // ========================
    //  ACORDEONES
    // ========================
    document.querySelectorAll(".accordion-header").forEach(header => {
        header.addEventListener("click", () => {
            let target = header.dataset.target;
            let content = document.getElementById("accordion-" + target);
            let icon = header.querySelector(".accordion-icon");

            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block";
                icon.textContent = "▼";
            } else {
                content.style.display = "none";
                icon.textContent = "▶";
            }
        });
    });


    // ========================
    //  BUSQUEDA DE ROLES
    // ========================
    const buscador = document.getElementById("buscadorRol");
    const resultados = document.getElementById("resultadosRoles");
    const contenedor = document.getElementById("contenedorRoles");

    buscador.addEventListener("keyup", async () => {
        let q = buscador.value.trim();
        if(q.length < 2){
            resultados.innerHTML = "";
            return;
        }

        const res = await fetch("{{ route('roles.buscar') }}?q=" + q);
        const roles = await res.json();

        resultados.innerHTML = "";
        roles.forEach(rol => {

            // Evitar duplicados
            if (document.querySelector('.rol-tag[data-id="' + rol.id_rol + '"]')) return;

            let div = document.createElement("div");
            div.classList.add("autocomplete-item");
            div.textContent = rol.nombre;
            div.dataset.id = rol.id_rol;

            div.onclick = () => agregarRol(rol.id_rol, rol.nombre);
            resultados.appendChild(div);
        });
    });

    function agregarRol(id, nombre) {
        if (document.querySelector('.rol-tag[data-id="' + id + '"]')) return;

        let tag = document.createElement("div");
        tag.className = "rol-tag";
        tag.dataset.id = id;
        tag.innerHTML = `
            ${nombre}
            <input type="hidden" name="roles[]" value="${id}">
            <button type="button" class="btn-remove-rol">✖</button>
        `;
        contenedor.appendChild(tag);
        resultados.innerHTML = "";
        buscador.value = "";
    }

    contenedor.addEventListener("click", (e) => {
        if(e.target.classList.contains("btn-remove-rol")){
            e.target.parentElement.remove();
        }
    });

});
</script>
@endsection
