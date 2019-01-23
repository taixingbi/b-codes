@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/agent-order.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

@endsection
@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="logo">
                <img src="{{ URL::to('images/favicon.png') }} " width="200px" height="200px"></div>
            <h2>Reset Password</h2>
            @if(count($errors)>0)
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('agent.resetPassword') }}" method="post">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password2">Confirm Password</label>
                    <input type="password" id="password2" name="password2" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" id="remember_token" name="remember_token" value="{{ $remember_token }}" class="form-control">
                </div>
                <div><button type="submit" style="margin-bottom: 7px" class="btn btn-primary" onclick="return checkPwd();">Submit</button></div>
                {{ csrf_field() }}
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/reset-pwd.js') }}"></script>

@endsection