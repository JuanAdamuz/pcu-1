<!DOCTYPE html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}"  media="screen,projection"/>
    <title>Acerca de - POPLife</title>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<body class="">

@include('policy.menu')

<div class="container">
    <div class="row">
        <div class="col s12">
            <img src="/img/logopopsinmetro.png" height="50" alt="">
            <div class="card-panel">
                <h5>Acerca de esta página</h5>
                <p>Información sobre la página.</p>
                <b>Versión</b>
                <p>{{ config('pcu.version') }} <small>{{ exec('git rev-parse --short HEAD') }}</small></p>
                <b>Características</b>
                <ul class="browser-default">
                    <li>Sistema de usuarios con inicio de sesión con cuenta de Steam</li>
                    <li>
                        Sistema integral de whitelist
                        <ul class="browser-default">
                            <li>Sistema de exámenes aleatorios y con corrección con intervención de un moderador</li>
                        </ul>
                    </li>
                    <li>Sistema de noticias</li>
                    <li>Sistema de páginas (wiki, sistema de soporte)</li>
                    <li>Sistema de notificaciones y seguimiento de páginas/categorías</li>
                </ul>
                <b>Créditos</b>
                <p>
                    <small>Autor:</small>
                    <br><a href="https://steamcommunity.com/id/Apecengo/">Manolo Pérez</a>
                </p>
                <b>Licencia</b>
                <p>Todos los derechos reservados. Se prohíbe la reproducción o reutilización de esta página para otro motivo que no sea:</p>
                <p><code>POPLife 5 plataoplomo.wtf</code></p>
                <p>Para más información acerca de la licencia, contactar con el autor.</p>
                <b>Tecnología usada</b>
                <p>Esta página ha sido creada usando <a href="https://laravel.com/">Laravel 5.5</a>,
                    <a href="https://github.com/Dogfalo/materialize">Materialize</a> y <a href="https://vuejs.org/">Vue</a>, principalmente, en
                    <a href="https://www.jetbrains.com/phpstorm/">PhpStorm</a>.</p>
            </div>
            <small>&copy; Manolo Pérez (Apecengo) 2017-2018</small>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
</body>
</html>