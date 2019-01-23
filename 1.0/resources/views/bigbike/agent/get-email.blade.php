@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/agent-order.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::to('css/vegas.min.css') }}">
    <style media="screen">
        footer{
            display: none;
        }
        body{
            background: #191919;
        }
    </style>
@endsection
@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-4 center-mainl">
            <div class="logo">
                <img src="{{ URL::to('images/favicon.png') }}" width="200px" height="200px"></div>
            <h2 class="white">Enter Email</h2>
            @if(count($errors)>0)
                <div class="alert alert-danger">
                    {{--@foreach($errors->all() as $error)--}}
                        <p>{{ $errors }}</p>
                    {{--@endforeach--}}
                </div>
            @endif
            <p class="white">Please enter your email address. You will receive a link to create a new password via email.</p>
            <form action="{{ route('agent.getEmail') }}" method="post">
                <div class="form-group">
                    {{--<label class="white" for="email">E-mail</label>--}}
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                {{ csrf_field() }}
                <div><button type="submit" style="margin-bottom: 7px" class="btn btn-primary">Submit</button></div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ URL::to('js/vegas.min.js') }}"></script>
<script type="text/javascript">
    $("#example, body").vegas({
        firstTransitionDuration: 3000,
        slides: [
            { src: "{{ URL::to('images/1.jpg') }}" },
            { src: "{{ URL::to('images/2.jpg') }}" },
            { src: "{{ URL::to('images/3.jpg') }}" }
        ],
        overlay: '{{ URL::to('images/overlays/01.png') }}'
    });
</script>
@endsection