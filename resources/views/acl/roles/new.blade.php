@extends('layouts.pcu')

@section('title', __('acl.roles.add.title'))

@section('content')
    @include('acl.users.menu')
    <div class="container">
        <h5>@lang('acl.roles.add.heading')</h5>
        @include('common.errors')
        <div class="card-panel">
            <form action="{{ route('acl-roles-new') }}" method="POST">
                {{ csrf_field() }}
                <p>@lang('acl.roles.add.form.info.heading')</p>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="name" name="name" type="text" required value="{{ old('name') }}">
                        <label for="name">@lang('acl.roles.add.form.info.id') <span class="red-text">*</span></label>
                    </div>
                    <div class="col s6">
                        <span>@lang('acl.roles.add.form.info.id.description')</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="display_name" name="display_name" type="text" required value="{{ old('display_name') }}">
                        <label for="display_name">@lang('acl.roles.add.form.info.displayname') <span class="red-text">*</span></label>
                    </div>
                    <div class="col s6">
                        <span>@lang('acl.roles.add.form.info.displayname.description')</span>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <input id="description" name="description" type="text" required value="{{ old('description') }}">
                        <label for="description">@lang('acl.roles.add.form.info.description') <span class="red-text">*</span></label>
                    </div>
                    <div class="col s6">
                        <span>@lang('acl.roles.add.form.info.description.description')</span>
                    </div>
                </div>
                <p>@lang('acl.roles.add.form.permissions.heading')</p>
                <div class="row">
                    <div class="col s12">

                        <select multiple style="width: 100%" name="permissions[]" id="permissions" class="select2">
                            @if(!is_null(old('permissions')))
                                @foreach(old('permissions') as $id)
                                    <option value="{{ $id }}" selected="selected">{{ \App\Permission::findOrFail($id)->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <br>
                <button class="btn green waves-effect" type="submit">@lang('acl.roles.add.form.submit')</button>
            </form>
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

    $('select').select2({
        data: [@foreach($permissions as $permission){ id: {{$permission->id}}, text: "{{ $permission->name }}", description: "{{ $permission->description }}" },@endforeach],
        templateResult: formatPermission,
    });
</script>
@endsection