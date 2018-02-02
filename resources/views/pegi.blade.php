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
        <img src="/img/logopopfull.png" style="filter: grayscale(100%);" height="150px" alt="">
        <img src="/img/PEGI_16.svg" height="125px" alt="">
        <p class="white-text flow-text">Todavía no tienes la edad necesaria. <br> Para jugar, hace falta tener <b>16</b> años.</p>
        <br>
        <img src="/img/pegi1.gif">
        <img src="/img/pegi2.gif">
        <img src="/img/pegi3.gif">
        <img src="/img/pegi4.gif">
        <img src="/img/pegi5.gif">
        <img src="/img/pegi6.gif">
    </div>
    <div class="parallax"><img src="/img/pop1.jpg"></div>
</div>
<br>
<div class="container white-text">
    @if(config('pcu.altis_enabled'))
        <h5 class="white-text">Prueba Altis Life</h5>
        <p>
            Altis Life es un servidor parecido a POPLife pero sin restricciones de edad ni mods que descargar.
            <br>Te recomendamos que lo pruebes mientras esperas a tener la edad.
        </p>
    @endif
    <a href="{{ route('altis') }}">Más información</a>
    <h5 class="white-text">Preguntas frecuentes</h5>
    <br>
    <span>> Me queda poco para cumplir los 16. ¿Puedo entrar ya?</span>
    <p>No se hacen excepciones. No te preocupes si te queda poco. ¡Deja el ArmA descargado y ven cuando tengas la edad, te estaremos esperando!</p>
    <br>
    <span>> Si dono, ¿puedo entrar antes?</span>
    <p>Se agradecen las donaciones, pero no harán que entres antes de tener 16 años a jugar.</p>
    <br>
    <span>> Se acabó. Voy a mentir para empezar a jugar ya.</span>
    <p>Nos tomamos muy en serio la limitación de edad. Si te pillamos jugando con menos de 16 años, no podrás volver a jugar nunca más.</p>
    <br>
    <span>> Ya he cumplido los 16. ¿Puedo intentarlo ahora?</span>
    <p>Tienes que rellenar un formulario para recurrir sanción en el foro. Enlace: <a href="https://plataoplomo.wtf/forum/index.php?/forms/9-formulario-para-recurrir-sanci%C3%B3nban/">Formulario para recurrir sanción</a></p>
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