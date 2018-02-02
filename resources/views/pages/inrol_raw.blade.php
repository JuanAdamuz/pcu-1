@extends('layouts.pcu')
@section('title', $page->title)
@section('content')

<nav class="white black-text">
    <div class="nav-wrapper">
        <div class="container">
            <ul id="nav-mobile" class="left">
                <li><a class="waves-effect black-text" href="{{ route('page', ['slug' => 'inrol']) }}"><i class="mdi mdi-account left"></i> Dentro de rol</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <br>
    {!! $page->content !!}
</div>
@endsection