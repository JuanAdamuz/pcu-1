@extends('layouts.pcu')
@section('title', 'DGT Permisos')
@section('content')
    @include('inrol.dgt.menu')
    <div class="container">
        <br>
        <h5>Permisos de conducción y licencias</h5>
        <div class="row">
            <div class="col s12">
            </div>
            <div class="col s12 m6">
                <div class="card-panel">
                    <b>Sedes de la Jefatura de Tráfico de Metrópolis</b>
                    <p>
                        Centro de exámenes general
                        <br>
                        <small>
                            Permisos B y C
                            <br>Coordenadas: 077, 064
                            <br>Junto al Cementerio Municipal, Metrópolis
                        </small>
                    </p>
                    <p>
                        Oficina de expedición de licencias de Taxi
                        <br>
                        <small>
                            Permiso Taxi
                            <br>Coordenadas: 081, 068
                            <br>Centro empresarial de Metrópolis
                        </small>
                    </p>
                    <p>
                        Oficina de expedición de licencias de Autobús
                        <br>
                        <small>
                            Permiso Bus
                            <br>Coordenadas: 080, 068
                            <br>Centro empresarial de Metrópolis
                        </small>
                    </p>
                </div>
                <div class="card-panel hide-on-small-only">
                    La conducción de vehículos a motor y ciclomotores exigirá haber obtenido
                    previamente la preceptiva autorización administrativa, que se dirigirá a verificar que los
                    conductores tengan los requisitos de capacidad, conocimientos y habilidad necesarios para
                    la conducción del vehículo, de acuerdo con lo que se determine reglamentariamente. Se
                    prohibe conducir vehículos a motor y ciclomotores sin estar en posesión de la mencionada
                    autorización administrativa.
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card-panel">
                    <b class="flow-text">Permiso AM</b>
                    <p>
                    <ul>
                        <li><i class="mdi mdi-bike"></i> Ciclomotores y cuadriciclos ligeros</li>
                    </ul>
                    </p>
                    <p class="green-text"><i class="mdi mdi-check-circle"></i> Tiene este permiso.</p>
                </div>
                <div class="card-panel">
                    <b class="flow-text">Permiso B</b>
                    <p>
                        <ul>
                            <li><i class="mdi mdi-motorbike"></i> Motocicletas</li>
                            <li><i class="mdi mdi-car"></i> Automóviles</li>
                            <li><i class="mdi mdi-car-estate"></i> Furgones<sup>1</sup></li>
                        </ul>
                    </p>
                    @if(str_contains($licenses, '`license_civ_driver`,1'))
                        <p class="green-text"><i class="mdi mdi-check-circle"></i> Tiene este permiso.</p>
                    @endif
                </div>
                <div class="card-panel">
                    <b class="flow-text">Permiso C</b>
                    <p>
                        Necesario tener el Permiso B.
                    <ul>
                        <li><i class="mdi mdi-truck"></i> Camiones</li>
                    </ul>
                    </p>
                    @if(str_contains($licenses, '`license_civ_trucking`,1'))
                        @if(!str_contains($licenses, '`license_civ_driver`,1'))
                            <p>Tiene este permiso, aunque con restricciones.</p>
                            <p class="red-text"><i class="mdi mdi-alert"></i> Sin validez hasta que obtenga el Permiso B.</p>
                        @else
                            <p class="green-text"><i class="mdi mdi-check-circle"></i> Tiene este permiso.</p>
                        @endif
                    @endif
                </div>
                <div class="card-panel">
                    <b class="flow-text">Permiso Taxi</b>
                    <p>
                        Necesario tener el Permiso B.
                    <ul>
                        <li><i class="mdi mdi-taxi"></i> Taxis</li>
                    </ul>
                    </p>
                    @if(str_contains($licenses, '`license_civ_taxi`,1'))
                        @if(!str_contains($licenses, '`license_civ_driver`,1'))
                            <p>Tiene este permiso, aunque con restricciones.</p>
                            <p class="red-text"><i class="mdi mdi-alert"></i> Sin validez hasta que obtenga el Permiso B.</p>
                        @else
                            <p class="green-text"><i class="mdi mdi-check-circle"></i> Tiene este permiso.</p>
                        @endif
                    @endif
                </div>
                <div class="card-panel">
                    <b class="flow-text">Permiso Bus</b>
                    <p>
                        Necesario tener el Permiso B.
                    <ul>
                        <li><i class="mdi mdi-bus-side"></i> Autobuses</li>
                    </ul>
                    </p>
                    @if(str_contains($licenses, '`license_civ_taxi`,1'))
                        @if(!str_contains($licenses, '`license_civ_driver`,1'))
                            <p>Tiene este permiso, aunque con restricciones.</p>
                            <p class="red-text"><i class="mdi mdi-alert"></i> Sin validez hasta que obtenga el Permiso B.</p>
                        @else
                            <p class="green-text"><i class="mdi mdi-check-circle"></i> Tiene este permiso.</p>
                        @endif
                    @endif
                </div>

                <small>
                    <sup>1</sup> Puede estar limitado a ciertos modelos. Consultar en el concesionario.
                </small>
            </div>
        </div>
    </div>
@endsection