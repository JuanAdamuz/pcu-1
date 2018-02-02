@extends('layouts.pcu')

@section('title', 'Nuevo usuario')

@section('content')
    @include('acl.users.menu')
<div class="container">
    <h5>Añadir usuario</h5>
    @include('common.errors')
    <div class="card-panel">
        <form action="{{ route('acl-users-new') }}" method="POST">
            {{ csrf_field() }}
            <p>Información</p>
            <div class="row">
                <div class="input-field col s6">
                    <input id="name" name="name" type="text" required value="{{ old('name') }}">
                    <label for="name">Nombre <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>El nombre del usuario. Debe ser el mismo que en el juego. No se aceptan pseudónimos.</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="steamid" name="steamid" type="text" required value="{{ old('steamid') }}">
                    <label for="steamid">SteamID <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>La SteamID del usuario. La usará para iniciar sesión.</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="email" name="email" type="email" required value="{{ old('email') }}">
                    <label for="email">Correo electrónico <span class="red-text">*</span></label>
                </div>
                <div class="col s6">
                    <span>El correo electrónico del usuario, para notificaciones y comunicaciones importantes. Se comprobará.</span>
                </div>
            </div>
            <p>Grupos</p>
            <div class="row">
                <div class="col s12">
                    <select multiple style="width: 100%" name="roles[]" id="roles" class="select2">
                        @if(!is_null(old('roles')))
                            @foreach(old('roles') as $id)
                                <option value="{{ $id }}" selected="selected">{{ \App\Role::findOrFail($id)->display_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <br>
            <button class="btn green waves-effect" type="submit">Crear usuario</button>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script>


        function formatRole (role) {
            return  $(
                '<span><b>' + role.text + '</b> <small>' + role.description + '</small></span>'
            );
        };

        $('select').select2({
            data: [@foreach($roles as $role){ id: {{$role->id}}, text: "{{ $role->display_name }}", description: "{{ $role->description }}" },@endforeach],
            templateResult: formatRole,
        });
    </script>
@endsection