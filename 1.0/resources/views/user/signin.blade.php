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
            <h2 class="white">Sign In</h2>
            @if(count($errors)>0)
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            @if(Session::has('msg'))
                <div class="alert alert-info">
                    {{ Session::get('msg') }}
                    {{ Session::forget('msg') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    {{ Session::forget('error') }}

                </div>
            @endif

            <form action="{{ route('user.signin') }}" method="post">
                <div class="form-group">
                    <label class="white" for="email">E-mail</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label class="white" for="email">Store Location</label><br>
                    <select class="agent-order-place form-control" name="location" id="location" >
                        <option value=""></option>
                        @foreach($locations as $location)
                            <option value="{{$location->title}}">{{$location->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="white" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div><button type="submit" style="margin-bottom: 7px" class="btn btn-primary" onclick="return checkForm()">Sign In</button>
                    <div class="pass"><a href="{{ route('agent.getResetPage') }}">Forgot password?</a></div>
                </div>
                {{ csrf_field() }}
            </form>
            {{--<span class="white">Don't have an account? <a href="{{ route('user.signup') }} ">Sign up</a></span>--}}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/vegas.min.js') }}"></script>
    <script src="{{ URL::to('js/notify.js') }}"></script>

    <script src="{{ URL::to('js/agent-signin.js') }}"></script>
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
