@extends('layouts.pcu')

@section('title', $user->name)

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <h5>"{{ $user->username }}"</h5>
        @include('common.errors')
        <div class="row">
            <div class="col s12 m6">
                <p>@lang('acl.users.edit.data.heading')</p>
                <div class="card-panel">
                    <form action="{{ route('acl-users-edit', $user) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="input-field col s12">
                                <input disabled type="text" required value="{{ $user->username }}">
                                <label for="steamid">@lang('acl.users.edit.data.id')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input disabled type="text" required value="{{ $user->steamid }}">
                                <label for="steamid">@lang('acl.users.edit.data.steamid')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="email" disabled required value="{{ $user->email or "?" }}">
                                <label for="email">@lang('acl.users.edit.data.email')</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input @if($user->isAdmin()) disabled @endif type="checkbox" name="disabled" aria-invalid="disabled" class="filled-in" id="filled-in-box" @if(!is_null(old('anonymous'))) checked="checked" @elseif($user->disabled) checked @endif/>
                                <label for="filled-in-box">@lang('acl.users.edit.data.disabled')</label>
                            </div>
                        </div>
                        <br>
                        @if($user->isAdmin()) <small>Nota: la información de los Administradores del Panel no puede ser editada, aunque sí los grupos y permisos.</small> @endif
                        <br>
                        <p>@lang('acl.users.edit.groups.heading')</p>
                        <div class="row">
                            <div class="col s12">
                                <select id="select-roles" multiple style="width: 100%" name="roles[]" id="roles" class="select2">
                                    @if(old('roles'))
                                        @foreach(old('roles') as $id)
                                            <option value="{{ $id }}" selected="selected">{{ \App\Role::findOrFail($id)->display_name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($user->roles as $role)
                                            <option selected value="{{ $role->id }}">{{ $role->display_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <p>@lang('acl.users.edit.permissions.heading')</p>
                        <div class="row">
                            <div class="col s12">
                                <select id="select-permissions" multiple style="width: 100%" name="permissions[]" id="permissions" class="select2">
                                    @if(old('permissions'))
                                        @foreach(old('permissions') as $id)
                                            <option value="{{ $id }}" selected="selected">{{ \App\Permission::findOrFail($id)->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($user->permissions as $permission)
                                            <option selected value="{{ $permission->id }}">{{ $permission->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <br>
                        <button class="btn green waves-effect" type="submit">@lang('acl.users.edit.submit')</button>
                    </form>
                </div>
            </div>
            <div class="col s12 m6">
                <p>Revisiones</p>
                <div class="card-panel">
                    <table>
                        <thead>
                            <tr>
                                <th>24h</th>
                                <th>7d</th>
                                <th>1m</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $user->reviews()->where('created_at', '>=', Carbon::now()->subDays(1))->count() }}</td>
                                <td>{{ $user->reviews()->where('created_at', '>=', Carbon::now()->subDays(7))->count() }}</td>
                                <td>{{ $user->reviews()->where('created_at', '>=', Carbon::now()->subMonths(1))->count() }}</td>
                                <td>{{ $user->reviews()->count() }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>x&#x0304; revisiones aprobadas: {{ is_null($user->reviews->average('score')) ? "?" : $user->reviews->average('score') .'%' }}</p>
                    <p> x&#x0304; respuestas aprobadas: {{ is_null($user->reviews->where('reviewable_type', 'App\Name')->average('score')) ? "?" : $user->reviews->average('score').'%' }}</p>
                    <p>x&#x0304; nombres aprobados: {{ is_null($user->reviews->where('reviewable_type', 'App\Answer')->average('score')) ? "?" : $user->reviews->average('score').'%' }}</p>
                </div>

                <p>Entrevistas</p>
                <div class="card-panel">
                    <table>
                        <thead>
                        <tr>
                            <th>24h</th>
                            <th>7d</th>
                            <th>1m</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $user->interviewing()->where('created_at', '>=', Carbon::now()->subDays(1))->count() }}</td>
                            <td>{{ $user->interviewing()->where('created_at', '>=', Carbon::now()->subDays(7))->count() }}</td>
                            <td>{{ $user->interviewing()->where('created_at', '>=', Carbon::now()->subMonths(1))->count() }}</td>
                            <td>{{ $user->interviewing()->count() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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

        function formatPermission (permission) {
            return  $(
                '<span><b>' + permission.text + '</b> <small>' + permission.description + '</small></span>'
            );
        };

        $('#select-roles').select2({
            data: [@foreach($roles as $role){ id: {{$role->id}}, text: "{{ $role->display_name }}", description: "{{ $role->description }}" },@endforeach],
            templateResult: formatRole,
        });

        $('#select-permissions').select2({
            data: [@foreach($permissions as $permission){ id: {{$permission->id}}, text: "{{ $permission->name }}", description: "{{ $permission->description }}" },@endforeach],
            templateResult: formatPermission,
        });
    </script>
@endsection