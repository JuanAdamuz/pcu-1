@extends('layouts.pcu')
@section('title', 'DGT Permisos')
@section('content')
    @include('inrol.banco.menu')
    <div class="container">
        <br>
        <h5>Cuenta: ES79 {{ wordwrap(substr($user->steamid, 1), 4, ' ', true) }}</h5>
        {{--<p>Historial de movimientos y saldo.</p>--}}
        <div class="row">
            <div class="col s12">
                <div class="card-panel">
                    <b><i class="mdi mdi-currency-eur"></i> Movimientos y saldo</b>

                    <table class="highlight">
                        <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Fecha</th>
                            <th>Más datos</th>
                            <th>Importe</th>
                            <th>Saldo</th>
                        </tr>
                        </thead>

                        <tbody>
                        @php
                            $saldo = $player->bankacc;
                        @endphp
                        @foreach($player->transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->getTypeName() }}</td>
                                <td>{{ $transaction->timestamp->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}</td>
                                <td></td>
                                <td>+{{ number_format($transaction->cantidad, 0, ',', '.') }}</td>
                                <td>+{{ number_format($saldo, 0, ',', '.') }}</td>
                            </tr>
                            @php
                                $saldo = $saldo - $transaction->cantidad;
                            @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col s12">
                <small class="right">Todos los importes están expresados en EUROS.</small>
            </div>
        </div>
    </div>
@endsection