@extends('layouts.pcu')
@section('title', $page->title)
@section('content')
    <div class="container">
        <br>
        <h5>{{$page->title}}</h5>
        <div class="card-panel">
            {% $page->content %}
        </div>
        <small>Última actualización {{ $page->updated_at->diffForHumans() }}</small>
    </div>
@endsection