@extends('layouts.pcu')
@section('title', 'Transferir vehículo')
@section('content')
    @include('inrol.dgt.menu')
    <div class="container" id="app">
        <br>
        <small><a href="{{ route('inrol-dgt-matriculados') }}"><< sus vehículos matriculados</a></small>
        <br>
        <h5>Transferir titularidad</h5>
        <p>Confirme desde aquí la transferencia de titularidad de su vehículo.</p>
        @if(!$vehicle->alive)
            <div class="card-panel">
                <b>No se pueden transferir vehículos siniestrados</b>
                <p>El vehículo que está intentando transferir ha sido declarado siniestrado, por lo que no podrá ser transferido.</p>
                <small><i class="mdi mdi-recycle green-text"></i> No olvide reciclar su vehículo. Cuidemos el medioambiente.</small>
            </div>
        @elseif($vehicle->active)
            <div class="card-panel">
                <b>Guarde el vehículo en un garaje</b>
                <p>Para poder comenzar la transferencia de titular, el vehículo debe estar guardado en un garaje.</p>
                <small>El sistema podría tardar unos minutos en detectar que el vehículo ha sido guardado.</small>
            </div>
        @else
            <div class="card-panel" v-if="!accepted">
                <b>Datos del vehículo</b>
                <p>
                    <small>Matrícula:</small>
                    <br>{{ $vehicle->id }}
                </p>
                <p>
                    <small>Modelo:</small>
                    <br>{{ $vehicle->name }}
                </p>
                <p>
                    <small>Fecha de matriculación:</small>
                    <br>{{ $vehicle->insert_time->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                </p>
                <p>
                    <small><b>Tasa administrativa:</b></small>
                    <br>{{ number_format(round($vehicle->price*0.1), 0, ',', '.') }},0 €
                </p>
                <a @click.prevent="accepted = true" class="btn white blue-text waves-effect"><i class="mdi mdi-check left"></i> Aceptar y continuar</a>
            </div>
            <div class="card-panel" v-if="accepted" v-cloak>
                <div class="row">
                    <form class="col s12">
                        <div class="row">
                            <div class="input-field col s12">
                                <input v-model="dni" id="first_name" type="text" maxlength="9" minlength="9" required>
                                <label for="first_name">DNI del nuevo titular <span class="red-text">*</span></label>
                            </div>
                            <div class=" col s12">
                                <label>Motivo de la transferencia <span class="red-text">*</span></label>
                                <select class="browser-default" name="" id="" required>
                                    <option value="-1" disabled selected>Seleccione un motivo...</option>
                                    <option value="">Venta</option>
                                    <option value="">Cesión/donación</option>
                                    <option value="">Cambio temporal de titularidad</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-panel">
                    <b><i class="mdi mdi-image-filter-hdr"></i>MetroPay</b> <span class="right"><img src="/img/cajametro.png" height="32" alt=""></span>
                    <p>
                        <small>Concepto:</small>
                        <br>*DGT TRANSF {{ $vehicle->id }}*
                    </p>
                    <p>
                        <small>Cantidad:</small>
                        <br>{{ number_format(round($vehicle->price*0.1), 0, ',', '.') }},0 €
                    </p>
                    <p>
                        <small>Método de pago:</small>
                        <br>Transferencia <small>(MT79 {{ wordwrap(substr(Auth::user()->steamid, 1), 4, ' ', true) }})</small>
                    </p>
                    <button @click.prevent="loading = true" v-if="!loading" :disabled="dni == ''" class="btn indigo white-text waves-effect">Pagar <b>{{ number_format(round($vehicle->price*0.1), 0, ',', '.') }},0 €</b></button>
                    <small v-if="dni == ''"><br>Complete el formulario antes de pagar.</small>
                    <div v-if="loading" class="preloader-wrapper small active">
                        <div class="spinner-layer spinner-indigo-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#app',
            data: {
                accepted: false,
                dni: '',
                loading: false,
            }
        });
    </script>
@endsection