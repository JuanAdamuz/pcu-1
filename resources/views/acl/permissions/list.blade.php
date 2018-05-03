@extends('layouts.pcu')

@section('title', __('acl.permissions.list.title'))

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5>@lang('acl.permissions.list.heading')</h5>
                <p>@lang('acl.permissions.list.subtitle')</p>
            </div>
            <div class="col s12">
                <div class="card-panel">
                    <table class="highlight responsive-table">
                        <thead>
                        <tr>
                            <th>@lang('acl.permissions.list.table.id')</th>
                            <th>@lang('acl.permissions.list.table.name')</th>
                            <th>@lang('acl.permissions.list.table.description')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->display_name }}</td>
                                <td>{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                        @if($permission->count() == 0)
                            <p><b>@lang('acl.permissions.list.empty')</b></p>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection