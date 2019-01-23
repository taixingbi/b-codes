@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/agent-order.css') }}">
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


@section('content')
<div class="row">
    <div class="col-md-4 center-mainl">
        <h1 class="white text-center">Sign Up</h1>
        @if(count($errors)>0)
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
        <form action="{{ route('user.signup') }}" method="post">
            <div class="form-group">
                <label class="white" for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control">
            </div>
            <div class="form-group">
                <label class="white" for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control">
            </div>
            <div class="form-group">
                <label class="white" for="email">E-mail</label>
                <input type="text" id="email" name="email" class="form-control">
            </div>
            <div style="display: none">
                <input type="text" id="cookie" name="cookie" class="form-control">
            </div>
            <div class="form-group">
                <label class="white" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            {{--<div class="form-group">--}}
                {{--<input type="checkbox"  required name="terms"><a href="#"> Accept Terms & Conditions of Use</a><span id="term"> *</span>--}}
            {{--</div>--}}
            <button type="submit" class="btn btn-primary">Sign Up</button>
            {{ csrf_field() }}
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
