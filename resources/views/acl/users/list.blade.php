@extends('layouts.pcu')

@section('title', 'Lista de usuarios')

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>Usuarios</h5>
                <form action="" method="GET">
                    <input name="q" type="text" placeholder="Buscar usuario por nombre, SteamID, GUID" value="@if(isset($q)){{ $q }}@endif" autofocus onfocus="var temp_value=this.value; this.value=''; this.value=temp_value">
                    @foreach(\Illuminate\Support\Facades\Input::except('q') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>

                <div class="chip dropdown-button-extend clickable @if(request()->has('individual-perms')) black white-text @endif" data-activates='dropdown-individual-perms'>
                    Tiene permisos individuales: @if(request()->has('individual-perms')) {!! request()->input('individual-perms') ? "<b>Sí</b>" : "<b>No</b>" !!} @endif
                    <i class="chipicon material-icons">vpn_key</i>
                </div>
                <!-- Dropdown Structure -->
                <ul id='dropdown-individual-perms' class='dropdown-content'>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('individual-perms') +  ['individual-perms' => true]) }}" class="waves-effect">Sí</a></li>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('individual-perms') +  ['individual-perms' => false]) }}" class="waves-effect">No</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('individual-perms') +  ['individual-perms' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Da igual</a></li>
                </ul>

                <div class="chip dropdown-button-extend clickable @if(request()->has('has-groups')) black white-text @endif" data-activates='dropdown-has-groups'>
                    Pertenece a un grupo: @if(request()->has('has-groups')) {!! request()->input('has-groups') ? "<b>Sí</b>" : "<b>No</b>" !!} @endif
                    <i class="chipicon material-icons">group_work</i>
                </div>
                <!-- Dropdown Structure -->
                <ul id='dropdown-has-groups' class='dropdown-content'>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('has-groups') +  ['has-groups' => true]) }}" class="waves-effect">Sí</a></li>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except(['has-groups', 'group']) +  ['has-groups' => false, 'group' => null]) }}" class="waves-effect">No</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('has-groups') +  ['has-groups' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Da igual</a></li>
                </ul>

                <div class="chip dropdown-button-extend clickable @if(request()->has('group')) black white-text @endif" data-activates='dropdown-group'>
                    Grupo: @if(request()->has('group')) {{ request()->input('group') }} @endif
                    <i class="chipicon material-icons">group_work</i>
                </div>
                <!-- Dropdown Structure -->
                <ul id='dropdown-group' class='dropdown-content'>
                    <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except('group') +  ['group' => null]) }}" class="waves-effect"><i class="material-icons left">clear</i>Da igual</a></li>
                    <li class="divider"></li>
                    @foreach($roles as $role)
                        <li><a href="{{ request()->fullUrlWithQuery(\Illuminate\Support\Facades\Input::except(['group', 'has-groups']) +  ['has-groups' => true, 'group' => $role->name]) }}" class="waves-effect">{{ $role->display_name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col s12">
                <p><i class="material-icons tiny">list</i> Resultados ({{ $results->total() }})</p>

                <div class="card-panel">
                    @if($results->total() == 0)
                        <p><b>Ningún resultado.</b></p>
                        <p>Prueba a repetir la búsqueda con otros parámetros.</p>
                    @endif
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>SteamID</th>
                            <th>Grupos</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($results as $user)
                            <tr>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->steamid }}</td>
                                <td>@foreach($user->roles as $role) {{$role->display_name}} @endforeach @if($user->roles()->count() == 0) - @endif</td>
                                <td>@if($user->isDisabled()) <i class="material-icons red-text tooltipped" data-tooltip="Cuenta desactivada">highlight_off</i> @endif
                                    @if($user->isAdmin()) <i class="material-icons green-text tooltipped" data-tooltip="Administrador del panel">supervisor_account</i> @endif
                                    @if($user->permissions()->count() > 0) <i class="material-icons black-text tooltipped" data-tooltip="Permisos individuales">lock</i> @endif
                                </td>
                                <td><a href="{{ route('acl-users-edit', $user) }}" class="btn-flat"><i class="material-icons">mode_edit</i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection