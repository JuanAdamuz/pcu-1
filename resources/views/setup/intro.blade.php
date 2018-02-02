@extends('layouts.pcu')

@section('content')
    <div class="container">
        <br>
        @include('setup.breadcrumb')
        <br>
        <h5>Introducción a PoPLife</h5>
        <p>Para ayudarte a empezar, te explicamos el funcionamiento del servidor.</p>



        <div class="row">
            <div class="col s12 m9 l10">
                <div id="conceptos" class="section scrollspy">
                    <p>Conceptos básicos</p>
                    <div class="card-panel">
                        <p>PoPLife es un <b>servidor de rol</b>. <br> Básicamente, el objetivo es <b>desarrollar un papel</b> que tú escojas, como por ejemplo médico, policía, periodista, mafioso, etc.</p>
                        <p>Como servidor de rol, tendrás que mantener tu papel (como si fuera una obra de teatro) en todo momento, intentando emular (recordando que es un juego) la vida real.</p>
                    </div>
                </div>

                <div id="personaje" class="section scrollspy">
                    <p>Tu personaje</p>
                    <div class="card-panel">
                        <p>A continuación, te pediremos que crees tu personaje.
                            <br>En PoPLife, <b>los personajes son persistentes</b>. Su estado se guarda incluso cuando te desconectas.
                            <br>Tu dinero, estado de salud, posición, vehículos, roles, nivel y demás atributos persisten entre sesiones de juego.
                        </p>
                        <p>
                            A la hora de actuar, hazlo como lo haría una persona "sensata" de la vida real, por norma general.
                            <br><b>Valora tu vida</b>. No hagas cosas temerarias como dejar de comer, tirarte por un puente, ignorar a una persona armada encañonándote, etc.
                        </p>
                        <p>La muerte es un elemento importante de la dinámica. Al morir, pierdes dinero, tus pertenencias, posición, y no puedes participar más en el rol en el que estuvieras.</p>
                    </div>
                </div>

                <div id="roles" class="section scrollspy">
                    <p>Roles</p>
                    <div class="card-panel">
                        <p>Existen varios roles o papeles que tu personaje puede representar en PoPLife.</p>
                        <p>Existen <b>tres grandes ramas: civiles, policías y EMS</b>. Para que te hagas una idea:</p>
                        <ul class="browser-default">
                            <li><b>Civil</b>
                                <br>
                                <span>La mayoría de roles están aquí. La base del servidor.</span>
                                <ul class="browser-default">
                                    <li>Legal
                                        <ul class="browser-default">
                                            <li>Abogado</li>
                                            <li>Periodista</li>
                                            <li>Obrero</li>
                                            <li>Agricultor</li>
                                            <li>Taxista</li>
                                            <li>Camionero</li>
                                            <li>Alcalde</li>
                                            <li>Juez</li>
                                            <li>...</li>
                                        </ul>
                                    </li>
                                    <li>
                                        Ilegal
                                        <ul class="browser-default">
                                            <li>Traficante de órganos</li>
                                            <li>Ladrón</li>
                                            <li>Mafioso</li>
                                            <li>Contrabandista</li>
                                            <li>Narcotraficante</li>
                                            <li>Terrorista</li>
                                            <li>...</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <b>Policía</b>
                                <br>
                                <span>Para aquellos que busquen un rol más exigente e interesante.</span>
                                <ul class="browser-default">
                                    <li>Policía Nacional
                                        <ul class="browser-default">
                                            <li>GEO</li>
                                            <li>UIP</li>
                                            <li>UDEV</li>
                                        </ul>
                                    </li>

                                    <li>Guardia Civil
                                        <ul class="browser-default">
                                            <li>ATGC</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <b>EMS</b>
                                <br>
                                <span>Rol neutral de servicios de emergencia.</span>
                                <ul class="browser-default">
                                    <li>Técnico de ambulancia</li>
                                    <li>Médico</li>
                                    <li>Bombero</li>
                                    <li>Piloto de salvamento</li>
                                    <li>Salvamento marítimo</li>
                                    <li>Cirujano</li>
                                    <li>Mecánico</li>
                                </ul>
                            </li>
                        </ul>
                        <p>Cuando entras al servidor, <b>empiezas como civil</b>.
                            <br>A partir de ahí, presentándote a las <b>oposiciones</b> correspondientes, puedes <b>acceder a la EMS o Policía</b>.</p>
                        <p></p>
                    </div>
                </div>

                <div id="dinamica" class="section scrollspy">
                    <p>Dinámica del servidor</p>
                </div>

                <div id="normativa" class="section scrollspy">
                    <p>Normativa</p>
                </div>
            </div>
            <div class="col hide-on-small-only m3 l2 pushpin-demo-nav">
                <br>
                <ul class="section table-of-contents">
                    <li><a href="#conceptos">Conceptos</a></li>
                    <li><a href="#personaje">Tu personaje</a></li>
                    <li><a href="#roles">Roles</a></li>
                    <li><a href="#dinamica">Dinámica del servidor</a></li>
                    <li><a href="#normativa">Normativa</a></li>
                </ul>
            </div>
        </div>


        <div class="card-panel">
            <a href="{{ route('setup-name') }}" class="btn blue waves-effect">Escoger un nombre <i class="material-icons right">navigate_next</i></a>
        </div>

    </div>
@endsection
@section('js')
<script>
    $(document).ready(function(){
        $('.scrollspy').scrollSpy();
    });
</script>
@endsection