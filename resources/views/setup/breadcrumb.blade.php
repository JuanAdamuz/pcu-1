<nav class="black white-text hide-on-med-and-down">
    <div class="nav-wrapper">
        <div class="col s12">
            <a></a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/info') white-text @else grey-text @endif">Datos</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/email') white-text @else grey-text @endif">Correo</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/intro') white-text @else grey-text @endif">Intro</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/name') white-text @else grey-text @endif">Nombre</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/rules') white-text @else grey-text @endif">Normas</a>
            <a class="breadcrumb @if(request()->is('setup/exam') || request()->is('setup/exam/*')) white-text @else grey-text @endif">Prueba</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/forum') white-text @else grey-text @endif">Foro</a>
            <a class="breadcrumb @if(request()->route()->uri() == 'setup/interview') white-text @else grey-text @endif">Entrevista</a>
        </div>
    </div>
</nav>

