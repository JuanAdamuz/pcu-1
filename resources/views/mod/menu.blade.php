<nav class="indigo">
    <div class="nav-wrapper">
        <div class="container">
            <ul id="nav-mobile" class="left">
                <li><a class="waves-effect" href="{{ route('mod-dashboard') }}"><i class="material-icons left">folder_open</i> Moderación</a></li>
            </ul>
            <ul class="left hide-on-small-only right">
                @permission('mod-search')
                <li><a class="waves-effect" href="{{ route('exams.index') }}">Exámenes</a></li>
                @endpermission
                @permission('mod-search')
                <li><a class="waves-effect" href="{{ route('names.index') }}">Nombres</a></li>
                @endpermission
                @permission(['mod-search', 'mod-interview'])
                <li><a class="waves-effect" href="{{ route('mod-search') }}">Usuarios</a></li>
                @endpermission
            </ul>
        </div>
    </div>
</nav>