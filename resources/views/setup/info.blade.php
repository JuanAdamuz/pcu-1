@extends('layouts.pcu')

@section('title', 'Datos')

@section('content')
    <br>
    <div class="container" id="app">
        @include('setup.breadcrumb')
        <br>
        <h5>Información personal</h5>
        @include('common.errors')
        <form action="{{ route('setup-info') }}" method="POST">
            {{ csrf_field() }}
            <div class="card-panel">
                @if(is_null(auth()->user()->birth_date))
                    <div class="">
                        <label for="birth_date">Fecha de nacimiento <span class="red-text">*</span></label>
                        <input name="birth_date" id="birth_date" type="text" class="datepicker" required placeholder="Haz clic para elegir tu fecha de nacimiento">
                        <small><b>Formato dd/mm/aaa - Ej.: 13/06/2002</b> Los menores de 13 años no pueden registrarse.</small>
                    </div>
                @endif
                @if(is_null(auth()->user()->country))
                <br>
                <label>País de residencia <span class="red-text">*</span></label>
                <select name="country" id="country" class="browser-default" required>
                    <option value="" disabled selected>Selecciona una opción</option>
                    <option value="ES">España</option>
                    <option value="" disabled>---------------------</option>
                    @foreach(Countries::all()->pluck('translations.spa.common', 'cca2') as $cca2 => $name)
                        <option @if(old('country') == $cca2) selected @endif value="{{ $cca2 }}">{{ is_null($name) ? $cca2 : $name }}</option>
                    @endforeach
                </select>
                @endif
                @if(is_null(auth()->user()->attributes['timezone']))
                <br>
                <label>Zona horaria <span class="red-text">*</span></label>
                {!! Timezonelist::create('timezone', null, 'class="browser-default" v-model="timezone" required') !!}
                <small style="padding-top: 16px">Se usará para mostrar las horas en tu hora local.</small>
                @endif
            </div>
            <div class="card-panel">
                <button type="submit" class="btn blue waves-effect">Continuar <i class="material-icons right">navigate_next</i></button>
            </div>
        </form>
    </div>
@endsection
@section('js')
<script>
    var app = new Vue({
        el: '#app',
        data: {
            timezone: moment.tz.guess()
        }
    });
</script>
<script>
    $('.datepicker').pickadate({
        monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        weekdaysFull: ['Domingo', 'Lunes', 'Masrte', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
        closeOnSelect: true,
        today: '',
        clear: '',
        close: '',
        format: 'dd/mm/yyyy',
        submitFormat: 'dd/mm/yyyy',
        selectYears: 165,
        selectMonths: true,
        disable: [
            true,
            { from: [1920, 0, 1], to: [2004, 0, 1] }
        ]
    });

    $(document).ready(function() {
        $('select').material_select();
    });
</script>
@endsection