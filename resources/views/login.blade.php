<!DOCTYPE html>
<html>
<head>
    <title>POPLife - PCU</title>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">
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
    <meta name="theme-color" content="#000000">    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        .heading-parallax {
            text-shadow: 0px 0px 5px rgba(150, 150, 150, 1);

        }
    </style>
</head>
<body class="black">

<div class="navbar-fixed">
    <nav class="black">
        <div class="nav-wrapper container">
            <ul class="hide-on-med-and-down">
                {{--<li><a href=""><b>PoPLife</b></a></li>--}}
            </ul>
            <ul class="right hide-on-med-and-down">
                <li><a @if(session('status')) disabled @endif href="{{ route('login') }}" class="btn-flat white-text waves-effect waves-green"><i class="mdi mdi-steam left" style="height: inherit; line-height: inherit;"></i> Iniciar sesión con Steam</a></li>
            </ul>
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.6); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <img src="/img/logopoptrim.png" height="75px" alt="">
        <p class="white-text flow-text">POPLife es un modo de juego de rol para Arma 3. <br> Próximamente, una nueva versión.</p>
        @if(session('status'))
            <p class="red-text text-lighten-1">{{ session('status') }}</p>
        @else
            <br>
        @endif
        @if(!config('pcu.registrations_enabled'))
            <small class="white-text">Solo usuarios ya registrados:</small>
        @endif
        <br>
        <a @if(session('status')) disabled @endif href="{{ route('login') }}" class="btn green waves-effect"><i class="mdi mdi-steam left"></i> Iniciar sesión con Steam</a>
        <br>
        <small class="white-text">
            Iniciando sesión aceptas los <a class="white-text" href="{{ route('tos') }}"><b>Términos y condiciones</b></a>, la
            <a class="white-text" href="{{ route('privacy') }}"><b>Política de privacidad</b></a> y el uso de cookies.
            <br>
        </small>
    </div>
    <div class="parallax"><img src="/img/popreborn1.jpg"></div>
</div>
<br>
<div class="container white-text">
    <h5 class="white-text">Actualmente en desarrollo</h5>
    <p>Estamos desarrollando una nueva versión de POPLife. En estos momentos no aceptamos nuevos registros.</p>
    <p>Entérate de todas las novedades de POPLife y del desarrollo:</p>
    <ul>
        <li><a href="https://foro.poplife.wtf"><i class="mdi mdi-link"></i> Visita el foro de POPLife</a></li>
        <li><a href="https://twitter.com/poplifewtf"><i class="mdi mdi-link"></i> Síguenos en Twitter</a></li>
    </ul>

    <br>
    <small>Parte de la comunidad Plata o Plomo.</small>



</div>

<div class="container">
    <small>
        <br>
        <a href="/tos" class="grey-text text-lighten-2">Términos y condiciones</a>
        <span class="grey-text text-lighten-4">-</span>
        <a href="/privacy" class="grey-text text-lighten-4">Privacidad</a>
        <span class="grey-text text-lighten-4">-</span>
        <a href="/monetization" class="grey-text text-lighten-4">Monetización</a>
        <span class="grey-text text-lighten-4">-</span>
        <a href="/about" class="grey-text text-lighten-2">Acerca de</a>
        <br>
        <span class="white-text text-darken-2">&copy; poplife.wtf 2018
            <br>
            <br>
</span>
    </small>
</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', '{{ config('pcu.analytics') }}', 'auto');
    ga('send', 'pageview');
</script>
<script>
    $(document).ready(function(){
        $('.parallax').parallax();
    });
</script>
</body>
</html>