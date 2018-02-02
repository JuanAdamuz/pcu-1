@php
    $notifications = auth()->user()->notifications;
    $unreadNotifications = $notifications->where('read_at', null);
    $readNotifications = $notifications->where('read_at', '!=' , null);
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#000000">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#000000">
    @yield('head')
</head>
<body>
<div class="navbar-fixed">
    <nav class="black white-text navbar-main">
        <div class="nav-wrapper container">
            <div class="nav-wrapper">
                <a href="{{ route('home') }}" class="brand-logo">
                    <img src="/img/logopopsinmetro.png">
                </a>
                @if($unreadNotifications->count() > 0)
                    <a href="#"  data-activates="slide-out" class="blue-text button-collapse"><i class="material-icons mdl-badge" data-badge="1">menu</i></a>
                @else
                    <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
                @endif
                <ul class="right hide-on-med-and-down">
                    @if(Auth::user()->hasFinishedSetup())
                    <li>
                        <a class="waves-effect waves-light" href="https://foro.poplife.wtf"><i class="mdi mdi-forum left"></i> Foro</a>
                    </li>
                    @endif
            @if($unreadNotifications->count() > 0)
                    <li >
                        <a href="#modal-notifications" class="white-text waves-effect waves-light modal-trigger">
                            <i class="material-icons white-text text-accent-4 pulse left">notifications_active</i> <span class="new badge white black-text" data-badge-caption="sin leer">{{ $unreadNotifications->count() }}</span>
                        </a>
                    </li>
                @else
                    <li>
                        <a href="#modal-notifications" class="white-text waves-effect waves-light modal-trigger">
                            <i class="material-icons">notifications_none</i>
                        </a>
                    </li>
                @endif
                <li><a class="dropdown-button-extend" class="waves-effect" data-activates='dropdown-navbar'>{{ Auth::user()->username }} <i class="material-icons right">arrow_drop_down</i></a></li>
            </ul>
            </div>
        </div>
    </nav>
</div>

<ul id="slide-out" class="side-nav">
    <li><div class="user-view">
            <div class="background black">
                {{--<img src="img/pop2.jpg" class="teaser">--}}
            </div>
            {{--<a href="#!user"><img class="circle" src="/img/logopopsinmetro.png"></a>--}}
            <img src="/img/logopopsinmetro.png" height="45">
            <a href="#!name"><span class="white-text name">{{ Auth::user()->username }}</span></a>
            <br>
        </div></li>
    <li><a href="{{ route('home') }}" class="waves-effect"><i class="material-icons">home</i>Inicio</a></li>
    @if(Auth::user()->hasFinishedSetup())
        <li><a href="https://foro.poplife.wtf" class="waves-effect"><i class="material-icons">forum</i>Foro</a></li>
    @endif
    <li><div class="divider"></div></li>
    @if($unreadNotifications->count() > 0)
        <li >
            <a href="#modal-notifications" class="waves-effect waves-light modal-trigger">
                <i class="material-icons blue-text pulse left">notifications_active</i> {{ $unreadNotifications->count() }} sin leer
            </a>
        </li>
    @else
        <li>
            <a href="#modal-notifications" class="waves-effect waves-light modal-trigger">
                <i class="material-icons">notifications_none</i> 0 notificaciones sin leer
            </a>
        </li>
    @endif
    <li><div class="divider"></div></li>
    <li><a href="{{ route('account') }}">Tu cuenta</a></li>
    @permission('mod*')
    <li><a href="{{ route('mod-dashboard') }}" class="waves-effect"><i class="material-icons left">folder_open</i> Moderación</a></li>
    @endpermission
    @if(Auth::user()->isAdmin())
        <li><a href="{{ route('acl-users') }}" class="waves-effect"><i class="material-icons left">vpn_key</i> ACL</a></li>
        <li><a href="{{ route('whitelist') }}" class="waves-effect">Whitelist</a></li>
    @endif
    <li><a class="waves-effect" href="{{ route('logout') }}" class="waves-effect">Cerrar sesión</a></li>
</ul>

<!-- Notificaciones -->
<div id="modal-notifications" class="modal">
    <div class="modal-content">
        <h5>Notificaciones</h5>
        @if($notifications->count() == 0)
            <p>No tienes ninguna notificación por ahora. No te preocupes, te avisaremos:</p>

            <nav class="black white-text">
                <div class="nav-wrapper">
                    <ul class="right">
                        <li>
                            <a class="white-text">
                                <i class="material-icons white-text text-accent-4 pulse left">notifications_active</i> <span class="new badge white black-text" data-badge-caption="sin leer">?</span>
                            </a>
                        </li>
                        <li>
                            <a>
                                {{ Auth::user()->username }} <i class="material-icons right">arrow_drop_down</i>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        @endif
        @if($unreadNotifications->count() > 0)
            <p>Notificaciones sin leer</p>
            <ul class="collection white black-text">
                @foreach ($unreadNotifications as $notification)
                    @include('common.notification')
                @endforeach
            </ul>
            <form method="POST" action="{{ route('notifications-allread')  }}">
                {{ csrf_field()  }}
                <button type="submit" class="btn blue waves-effect"><i class="material-icons left">done_all</i> Marcar notificaciones como leídas</button>
            </form>
        @endif
        @if($readNotifications->count() > 0)
            <p>Últimas 10 notificaciones leídas</p>
            <ul class="collection white black-text">
                @foreach ($readNotifications->take(10) as $notification)
                    @include('common.notification')
                @endforeach
            </ul>
        @endif
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Cerrar</a>
    </div>
</div>

<!-- Dropdown navbar -->
<ul id='dropdown-navbar' class='dropdown-content'>
    <li><a href="{{ route('account') }}">Tu cuenta</a></li>
    @permission('mod*')
    <li><a href="{{ route('mod-dashboard') }}"><i class="material-icons left">folder_open</i> Moderación</a></li>
    @endpermission
    @if(Auth::user()->isAdmin())
        <li><a href="{{ route('acl-users') }}"><i class="material-icons left">vpn_key</i> ACL</a></li>
        <li><a href="{{ route('whitelist') }}" class="waves-effect">Whitelist</a></li>
    @endif
    <li class="divider"></li>
    <li><a href="{{ route('logout') }}">Cerrar sesión</a></li>
</ul>


@yield('content')


<div class="fixed-action-btn">
    <a class="btn-floating btn-large blue lighten-1 modal-trigger" href="{{ route('page', ['slug' => 'ayuda']) }}">
        <i class="large material-icons">help</i>
    </a>
</div>

<div class="container">
    <small>
        <br>
        <a href="/tos" class="grey-text text-darken-1">Términos y condiciones</a>
        <span class="grey-text text-darken-2">-</span>
        <a href="/privacy" class="grey-text text-darken-1">Privacidad</a>
        <span class="grey-text text-darken-2">-</span>
        <a href="/monetization" class="grey-text text-darken-1">Monetización</a>
        <span class="grey-text text-darken-2">-</span>
        <a href="/about" class="grey-text text-darken-1">Acerca de</a>
        <br>
        <span class="blue-grey-text text-darken-2">&copy; poplife.wtf 2018
            <br>
            <br>
</span>
    </small>
</div>

<!-- Modal Structure -->
<div id="modalhelp" class="modal">
    <div class="modal-content">
        <h5>Soporte</h5>
        <p>¿En qué podemos ayudarte?</p>
        <p>
            <input type="text" placeholder="Empieza a escribir un tema o una pregunta">
        </p>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-action modal-close waves-effect btn-flat">Cerrar</a>
    </div>
</div>

<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', '{{ config('pcu.analytics') }}', 'auto');
    ga('send', 'pageview');
</script>
@if(\Illuminate\Support\Facades\Session::has('status'))
    <script>
        Materialize.toast('{{ \Illuminate\Support\Facades\Session::get('status') }}', 4000);
    </script>
@endif
@yield('js')
</body>
</html>