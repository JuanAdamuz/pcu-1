<div class="col s12 m4">
    <div class="card-panel teaser white-text">
        <small class="white-text">Buenas,</small>
        <br>
        <span class="flow-text white-text"> <b>{{ $user->username }} {{-- @if($player->donorlevel > 0)<i class="mdi mdi-gift amber-text tooltipped" data-tooltip="Donador"></i> @endif --}}</b></span>
        <br>
        <p>
            <small>DNI:</small>
            <br><span class="copy tooltipped clickable" data-tooltip="Copiar al portapapeles" data-clipboard-text="{{ $user->dni }}" onclick="Materialize.toast('Copiado al portapapeles',  3000)"><span class="white-text">{{ $user->dni }} </span> <a><i class="mdi mdi-content-copy"></i></a></span>
        </p>
    </div>

</div>
<div class="col s12 m8">
    
    <div class="row">
        <div class="col s12 l6">
            <div class="card-panel">
                <center><img src="/img/dgt.png" height="50" alt="">
                    <br><br>
                    <a href="{{ route('inrol-dgt-matriculados') }}" class="btn-flat blue-text waves-effect">Vehículos matriculados</a>
                    <br>
                    <a href="{{ route('inrol-dgt-permisos') }}" class="btn-flat blue-text waves-effect">Consulta de permisos</a>
                </center>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="card-panel">
                <center><img src="/img/cajametro.png" height="50" alt="">
                    <br><br>
                    <a href="{{ route('inrol-banco-cuentas') }}" class="btn-flat indigo-text waves-effect">Cuentas</a>
                    <br>
                    <a class="btn-flat indigo-text"></a>
                </center>
            </div>
        </div>
        {{--<div class="col s12 l6">--}}
            {{--<div class="card-panel">--}}
                {{--<center><img src="/img/ministeriojusticiamini.png" height="50" alt="">--}}
                    {{--<br><br>--}}
                    {{--<a href="{{ route('inrol-justicia-personas') }}" class="btn-flat black-text waves-effect">Búsqueda persona física</a>--}}
                    {{--<br>--}}
                    {{--<a href="{{ route('inrol-banco-cuentas') }}" class="btn-flat black-text waves-effect">Búsqueda vehículo</a>--}}
                {{--</center>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
    
    {{--<div class="card-panel">--}}
        {{--<b class="deep-orange-text"><i class="mdi mdi-alert"></i> Tu nombre no coincide</b>--}}
        {{--<p>Has jugado a POPLife con un nombre que no coincide con el que tienes en la PCU.</p>--}}
        {{--<p>Deberías tener "<b>{{ $user->username }}</b>", cuando el nombre que tienes es "{{ $player->name }}".</p>--}}
        {{--<a href="" class="btn white blue-text waves-effect">Aprende cómo solucionarlo</a>--}}
    {{--</div>--}}
    
</div>