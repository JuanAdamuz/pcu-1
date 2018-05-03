@extends('layouts.pcu')

@section('title', __('acl.roles.list.title'))

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s6">
                <h5>@lang('acl.roles.list.heading')</h5>
            </div>
            <div class="col s6">
                <a href="{{ route('acl-roles-new') }}" class="btn-flat waves-effect right"><i class="material-icons left">add</i> @lang('acl.roles.list.add.button')</a>
            </div>
            <div class="col s12">
                <div class="card-panel">
                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>@lang('acl.roles.list.table.heading.name')</th>
                            <th>@lang('acl.roles.list.table.heading.members')</th>
                            <th>@lang('acl.roles.list.table.heading.actions')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->display_name }}</td>
                                <td>{{ $role->users()->count() }}</td>
                                <td><a href="{{ route('acl-roles-edit', $role) }}" class="btn-flat"><i class="material-icons">mode_edit</i></a></td>
                            </tr>
                        @endforeach
                        @if($roles->count() == 0)
                            {!! __('acl.roles.list.table.empty') !!}
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection