@extends('layouts.app')

@section('content')

<div class="form-container">

    <h2 class="form-title">
        <i class="fa-solid fa-user-pen"></i> Editar Usuario
    </h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    {{-- FORM 1: DATOS GENERALES --}}
    <form method="POST" action="{{ route('usuarios.actualizar.datos', $usuario->id_usuario) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Usuario *</label>
            <input type="text" name="username" required value="{{ old('username', $usuario->username) }}">
            @error('username')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}">
            @error('nombre')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}">
            @error('apellidos')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required value="{{ old('email', $usuario->email) }}">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Celular *</label>
                <input type="text" name="celular" required value="{{ old('celular', $usuario->celular) }}">
                @error('celular')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button class="btn-primary" type="submit">Guardar Datos</button>
            <a class="btn-secondary" href="{{ route('usuarios.gestion') }}">Volver</a>
        </div>
    </form>

    {{-- FORM 2: CAMBIAR CONTRASEÑA --}}
    <div class="accordion" style="margin-top: 20px;">
        <div class="accordion-header" onclick="toggleAccordion('pwdBox')">
            <span class="accordion-icon">🔐</span> Cambiar contraseña
        </div>
        <div id="pwdBox" class="accordion-content">

            <form method="POST" action="{{ route('usuarios.actualizar.password', $usuario->id_usuario) }}">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label>Nueva Contraseña *</label>
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

                <div class="form-actions" style="margin-top: 10px;">
                    <button class="btn-primary" type="submit">Guardar Contraseña</button>
                </div>
            </form>

        </div>
    </div>

    {{-- FORM 3: ROLES DEL USUARIO --}}
    <div class="accordion" style="margin-top: 20px;">
        <div class="accordion-header" onclick="toggleAccordion('rolesBox')">
            <span class="accordion-icon">📌</span> Roles del usuario
        </div>
        <div id="rolesBox" class="accordion-content">

            <form method="POST" action="{{ route('usuarios.roles.guardar', $usuario->id_usuario) }}">
                @csrf

                <div style="position:relative; margin-bottom: 10px;">
                    <input type="text"
                           id="buscar_rol"
                           class="search-input"
                           placeholder="Buscar roles...">
                    <div id="resultados_roles" class="autocomplete-results"></div>
                </div>

                <div id="roles_seleccionados" class="roles-container">
                    @foreach($rolesAsignados as $r)
                    <div class="rol-tag">
                        {{ $r->nombre }}
                        <input type="hidden" name="roles[]" value="{{ $r->id_rol }}">
                        <button type="button" class="btn-remove-rol" onclick="this.parentNode.remove()">✖</button>
                    </div>
                    @endforeach
                </div>

                <div class="form-actions" style="margin-top: 15px;">
                    <button class="btn-primary" type="submit">Guardar Roles</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
function toggleAccordion(id) {
    const box = document.getElementById(id);
    if (!box) return;
    box.style.display = (box.style.display === "block") ? "none" : "block";
}

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("buscar_rol");
    const contResultados = document.getElementById("resultados_roles");
    const contSeleccionados = document.getElementById("roles_seleccionados");

    if (!input) return;

    input.addEventListener("keyup", async function () {
        let q = this.value.trim();
        if (q.length < 2) {
            contResultados.innerHTML = "";
            return;
        }

        const resp = await fetch(`{{ route('roles.buscar') }}?q=${encodeURIComponent(q)}`);
        const data = await resp.json();

        contResultados.innerHTML = "";

        data.forEach(item => {
            const div = document.createElement("div");
            div.classList.add("autocomplete-item");
            div.textContent = item.nombre;

            div.onclick = function () {
                // Evitar duplicados si ya existe ese rol seleccionado
                if (contSeleccionados.querySelector(`input[value="${item.id_rol}"]`)) {
                    contResultados.innerHTML = "";
                    input.value = "";
                    return;
                }

                contSeleccionados.innerHTML += `
                    <div class="rol-tag">
                        ${item.nombre}
                        <input type="hidden" name="roles[]" value="${item.id_rol}">
                        <button type="button" class="btn-remove-rol" onclick="this.parentNode.remove()">✖</button>
                    </div>
                `;
                contResultados.innerHTML = "";
                input.value = "";
            };

            contResultados.appendChild(div);
        });
    });
});
</script>

@endsection
