<!DOCTYPE html>
<html>
<head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css"  media="screen,projection"/>
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
        .heading-parallax {
            text-shadow: 0px 0px 5px rgba(150, 150, 150, 1);

        }
    </style>
</head>
<body class="grey darken-4">

<div class="navbar-fixed">
    <nav class="grey darken-4">
        <div class="nav-wrapper container">
            <ul class="hide-on-med-and-down">
                <li><a class="btn-flat waves-effect white-text waves-white" href="/"><i class="material-icons left">arrow_back</i> Volver a POPLife</a></li>
            </ul>
            <ul class="right">
                <li><a href="{{ config('pcu.altis_forum') }}" class="waves-effect waves-white"><i class="material-icons left">forum</i> Foro</a></li>
            </ul>
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.45); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <h1 class="white-text"><b>Altis Life</b></h1>
        <p class="white-text flow-text">El lugar ideal para iniciarse en la comunidad y el mundo del rol.
            <br><b>Sin edad mínima</b>, sin esperas y sin mods que instalar.
            <br><br>Entra y disfruta desde el primer momento.
        </p>
        <a href="steam://connect/{{ config('pcu.altis_ip') }}" class="btn light-green waves-effect">Empezar a jugar ahora mismo <i class="material-icons right">open_in_new</i></a>
        <br>
        <small class="white-text">
            Haz clic para conectarte directamente al servidor. Antes de eso, quita todos los mods que tengas cargados en Arma.
            <br>IP si no funcionara el botón: <code>{{ config('pcu.altis_ip') }}</code>
        </small>

    </div>
    <div class="parallax"><img src="/img/altis.jpg"></div>
</div>
<br>
<div class="container white-text">
    <div class="card-panel grey darken-3">
        <h5 class="white-text">Normas</h5>
        <span>Revisa las normas en la siguiente página antes de empezar a jugar:</span>
        <p>
            <a class="light-green-text" href="{{ config('pcu.altis_rules') }}">{{ config('pcu.altis_rules') }}</a>
        </p>
    </div>

    <div class="card-panel grey darken-3">
        <h5 class="white-text">Más información</h5>
        <p>
            El servidor Altis Life de la comunidad Plata o Plomo es un lugar ideal para iniciarse en nuestra comunidad y en el mundo de rol que ofrece Arma 3.
        </p>
        <p>
            Sin edad mínima, sin withelist ni certificación y sin mod solo entra y disfruta.
        </p>
        <p>
            Elige tu Rol (Policia, EMS, ladrón....), o simplemente gana tu dinero realizando distintos trabajos legales e ilegales, así como recolectar melocotones, cazar, robos, secuestros y, en definitiva, simular situaciones cotidianas en cada rincón de nuestra ciudad en la que intervienen esos tres bandos.
        </p>
            <b>¡Entra y forja tu propia leyenda!</b>
        </p>
    </div>

    <p>
        Cualquier otra duda puedes preguntárnosla por TeamSpeak.
        <a class="light-green-text" href="{{ config('pcu.ts3_link') }}"><code>ts3.plataoplomo.wtf</code></a>
    </p>

    <br>
    <small class="white-text">&copy; 2017 Plata o Plomo <span class="grey-text text-darken-1">- Por Manolo Pérez (Apecengo)</span></small>
    <br><br>

</div>

<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>
<script>
    $(document).ready(function(){
        $('.parallax').parallax();
    });
</script>
</body>
</html>