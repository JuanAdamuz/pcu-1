@extends('layouts.pcu')
@section('content')
    @include('inrol.dgt.menu')
    <div class="container">
        <br>
        <h5>Vehículos matriculados a su nombre</h5>
        <p>A continuación encontrará una lista de los vehículos en los que usted figura como titular.</p>
        <div class="card-panel">
            @if($vehicles->count() == 0)
                <p>No tiene ningún vehículo matriculado.</p>
            @else
                <table class="highlight">
                    <thead>
                    <tr>
                        <th>Matrícula</th>
                        <th>Modelo</th>
                        <th>Estado</th>
                        <th class="hide-on-small-only">Fecha matriculación</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($vehicles as $vehicle)
                        <tr>
                            <td>{{ $vehicle->id }}</td>
                            <td>{{ $vehicle->name }}</td>
                            <td>
                                @if($vehicle->alive)
                                    ACTIVO
                                @else
                                    <span class="red-text">SINIESTRADO</span> <span><a href="#modal-siniestrado" class="black-text modal-trigger"><i class="mdi mdi-help-circle-outline"></i></a></span>
                                @endif
                            </td>
                            <td class="hide-on-small-only">{{ $vehicle->insert_time->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</td>
                            <td><a @if(!$vehicle->isTransferable()) disabled @endif href="{{ route('inrol-dgt-transferir', $vehicle) }}" class="btn-flat"><span class="hide-on-small-only">Transferir</span> <i class="mdi mdi-transfer right"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="card-panel">
            <b>Información sobre transferencias</b>
            <p>Si ha vendido su vehículo, deberá transferirlo a través de este servicio. Solo se podrán transferir vehículos que estén guardados en un garaje.</p>
            <small><sup>1</sup> El dueño a transferir deberá pagar un 10% del precio sin rebaja del vehículo en concepto de tasas administrativas.</small>
            <br><small><sup>2</sup> No se permite la transferencia de vehículos de empresa: policiales, SME o similar.</small>
            <br><small><sup>3</sup> Tanto el titular antiguo como el nuevo deberán tener el carnet de conducir para poder realizar la transferencia.</small>
            <br><small><sup>4</sup> Solo es posible transferir un vehículo cada 24 horas.</small>
            <br><small><sup>5</sup> Por el momento no es posible transferir aeronaves ni embarcaciones.</small>
            <br><small><sup>6</sup> Solo los residentes de más de un mes podrán transferir o recibir vehículos.</small>
            <br><small><sup>7</sup> Para poder transferir un vehículo debe haber pasado una semana desde su matriculación o transferencia.</small>
        </div>
        <small>Los datos podrían tener una antigüedad de hasta 15 minutos o no estar completos. Datos puramente informativos, sin ningún valor jurídico.</small>
    </div>

    <!-- Modal Structure -->
    <div id="modal-siniestrado" class="modal">
        <div class="modal-content">
            <h5>Vehículo siniestrado</h5>
            <p>Cuando un vehículo sufre un accidente o golpe en el que el vehículo queda en muy mal estado (tanto que ya no es posible conducir en él), se le declara <span class="red-text">siniestrado</span>.</p>
            <p>Cuando un vehículo es declarado siniestrado, será reciclado en un centro autorizado, y no podrá ser utilizado más, ni tampoco transferido.</p>
            <p>Los vehículos siniestrados serán dados de baja un tiempo después de ser declarados como tal.</p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect btn-flat">Cerrar</a>
        </div>
    </div>
@endsection