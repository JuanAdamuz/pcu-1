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
<body class="black">

<div class="navbar-fixed">
    <nav class="black">
        <div class="nav-wrapper container">
            <ul class="hide-on-med-and-down">
                {{--<li><a href=""><b>PoPLife</b></a></li>--}}
            </ul>
        </div>
    </nav>
</div>
<div class="parallax-container" style="background-color: rgba(0, 0, 0, 0.85); box-shadow: 0 0 200px rgba(0,0,0,0.9) inset;">
    <div class="container">
        <br><br>
        <img src="/img/logopoptrim.png" height="75px" style="filter: grayscale(100%);" alt="">
        <p class="white-text flow-text"><b>No ha sido posible iniciar sesión.</b><br><br>
        @if(!is_null($reason))
            {{ $reason }}
        @endif
        </p>
    </div>
    <div class="parallax"><img src="/img/popreborn1.jpg"></div>
</div>
<br>
<div class="container white-text">
    <h5 class="white-text">Preguntas frecuentes</h5>
    <br>
    <span>> ¡Me dice que no aceptan registros!</span>
    <p>No te preocupes. Actualmente no aceptamos registros porque el servidor no está abierto.
        <br>Puedes leer <a href="https://foro.poplife.wtf">nuestro foro</a> y <a
                href="https://twitter.com/poplifewtf">seguirnos en Twitter</a> para estar al tanto de las novedades.</p>
    <br>
    <span>> Me han sancionado. ¿Se puede recurrir?</span>
    <p>
        Lo normal es que sí. Haz clic en el siguiente enlace: <a href="https://plataoplomo.wtf/forum/index.php?/forms/9-formulario-para-recurrir-sanci%C3%B3nban/">Formulario para recurrir sanción</a>
        <br><small>Si tienes una cuenta en el foro sin banear, inicia sesión con ella antes de rellenar el formulario.</small>
    </p>
    <br>
    <p>Cualquier otra duda puedes preguntárnosla por TeamSpeak. <code>ts3.plataoplomo.wtf</code></p>



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