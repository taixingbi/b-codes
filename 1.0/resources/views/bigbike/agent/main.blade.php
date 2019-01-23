@extends('layouts.master')

@section('title')
    big bike agent
@endsection
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >


@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')

    <br><br><br><br>
    @if(Session::has('price-error'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('price-error') }}
                    {{ Session::forget('price-error') }}
                </div>
            </div>
        </div>
    @endif


    @if(Session::has('success'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('success') }}
                    {{ Session::forget('success') }}
                </div>
            </div>
        </div>
    @endif

@endsection

@section('scripts')
    {{--<script src="{{ URL::to('agent-rent.js') }}"></script>--}}
@endsection