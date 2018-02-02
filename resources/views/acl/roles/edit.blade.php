@extends('layouts.pcu')

@section('title', $role->display_name)

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <h5>Editar grupo "{{ $role->display_name }}"</h5>
        @include('common.errors')
        <div class="card-panel">
            <form action="{{ route('acl-roles-edit', $role) }}" method="POST">
                {{ csrf_field() }}
                <p>Información</p>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="name" name="name" type="text" required value="{{ !is_null(old('name')) ? old('name') : $role->name }}">
                        <label for="name">Identificador</label>
                    </div>
                    <div class="col s6">
                        <span>Una palabra corta y solo con letras. Identifica el grupo.</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="display_name" name="display_name" type="text" required value="{{ !is_null(old('display_name')) ? old('display_name') : $role->display_name }}">
                        <label for="display_name">Nombre a mostrar</label>
                    </div>
                    <div class="col s6">
                        <span>El nombre que se mostrará a los usuarios.</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="description" name="description" type="text" required value="{{ !is_null(old('description')) ? old('description') : $role->description }}">
                        <label for="description">Descripción</label>
                    </div>
                    <div class="col s6">
                        <span>Describe el grupo y su función de forma corta y concisa.</span>
                    </div>
                </div>
                <p>Permisos</p>
                <div class="row">
                    <div class="col s12">
                        <select id="select-permissions" multiple style="width: 100%" name="permissions[]" id="permissions" class="select2">
                            @if(old('permissions'))
                                @foreach(old('permissions') as $id)
                                    <option value="{{ $id }}" selected="selected">{{ \App\Permission::findOrFail($id)->name }}</option>
                                @endforeach
                            @else
                                @foreach($role->permissions as $permission)
                                    <option selected value="{{ $permission->id }}">{{ $permission->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <br>
                <button class="btn green waves-effect" type="submit">Editar grupo</button>
            </form>
            <br>
            <p class="red-text">Mucho ojo:</p>
            <form onsubmit="return confirm('¿Vaciar y borrar grupo? No se puede deshacer')" action="{{ route('acl-roles-delete', $role) }}" method="POST">
                {{ csrf_field() }}
                <button class="btn red waves-effect" type="submit"><i class="material-icons left">delete_sweep</i> Quitar a todos del grupo y eliminar</button>
            </form>
        </div>
        <div class="card-panel">
            <b>Usuarios</b>
            @if($role->users()->count() == 0)
                <p>Ningún usuario en el grupo.</p>
            @else
                <ul>
                    @foreach($role->users as $user)
                        <li><a href="{{ route('acl-users-edit', $user) }}">{{ $user->username }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script>
        function formatPermission (permission) {
            return  $(
                '<span><b>' + permission.text + '</b> <small>' + permission.description + '</small></span>'
            );
        };

        $('#select-permissions').select2({
            data: [@foreach($permissions as $permission){ id: {{$permission->id}}, text: "{{ $permission->name }}", description: "{{ $permission->description }}" },@endforeach],
            templateResult: formatPermission,
        });
    </script>
@endsection