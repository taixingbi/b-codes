@extends('layouts.master')

@section('title')
    big bike agent
@endsection

@section('content')
    <div class="container click">
        <p class="new"> Hmmm...the page you requsted was not a found, <a href="{{ route('agent.main') }}">click me</a></p>
    </div>

@endsection