@extends('layouts.pcu')
@section('title', 'Exámenes')
@section('content')
    @include('mod.menu')
    <div class="container">
        <br>
        <h5>Búsqueda de exámenes</h5>

        <p><i class="material-icons tiny">list</i> Resultados ({{ $results->total() }})</p>
        @if($results->total() == 0)
            <br>
            <p><b>Ningún resultado.</b></p>
            <p>Prueba a repetir la búsqueda con otros parámetros.</p>
        @endif

        @foreach($results as $exam)
            <a href="{{ route('exams.show', $exam) }}">
                <div class="card-panel black-text hoverable">
                    <b>{{ $exam->user->username }}</b> <small class="right">{{ $exam->updated_at->diffForHumans() }}</small>
                    <br>
                    @if(is_null($exam->passed))
                        @if($exam->end_at <= \Carbon\Carbon::now() || $exam->finished)
                            <i class="mdi mdi-file-document"></i> Prueba escrita esperando corrección
                        @else
                            <i class="mdi mdi-file-document"></i> Prueba escrita en proceso
                        @endif
                    @elseif(!$exam->passed)
                        <i class="mdi mdi-file-document red-text"></i> <span class="red-text">Prueba escrita suspensa</span>
                    @elseif(is_null($exam->interview_passed))
                        @if($exam->expires_at <= \Carbon\Carbon::now())
                            Expirado {{ $exam->expires_at->setTimezone(Auth::user()->timezone)->format('d/m/Y H:i') }}
                        @elseif(is_null($exam->interview_user_id))
                            <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-clock"></i> Esperando entrevista
                            {{-- TODO botón iniciar entrevista --}}
                        @else
                            @if(is_null($exam->interview_code_at))
                                <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-code-array"></i> Entrevista en curso, esperando código <small>({{ $exam->interviewer->username }})</small>
                            @else
                                <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset"></i> Entrevista en curso <small>({{ $exam->interviewer->username }})</small>
                            @endif
                        @endif
                    @elseif($exam->interview_passed)
                        <span class="green-text"><i class="mdi mdi-check-circle"></i> Prueba escrita y entrevista aprobadas</span>
                    @elseif(!$exam->interview_passed)
                        <i class="mdi mdi-file-document green-text"></i> <i class="mdi mdi-headset red-text"></i> <span class="red-text">Entrevista suspensa</span>
                    @else
                        <i>Estado desconocido o no válido.</i>
                    @endif
                </div>
            </a>
        @endforeach
        {{ $results->links() }}
    </div>

@endsection