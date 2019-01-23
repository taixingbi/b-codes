<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://tickets.bikerent.nyc/css/agent-order.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        body{ background-color: #f5f8fa;}
        .logo{
            padding-top: 15px;
            padding-left: 5px;
            display: inline-block;
            text-decoration: none;
            min-width: 100px;

        }
        .btn, .btn:link{
            border-radius: .3em;
            border-style: solid;
            border-width: 1px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            background: #0074D9;
            display: inline-block;
            font-family: avenir,helvetica,arial,sans-serif;
            letter-spacing: .15em;
            margin-bottom: .5em;
            padding: 1em .75em;
            text-decoration: none;
            text-transform: uppercase;
            -webkit-transition: color .4s,background-color .4s,border .4s;
            transition: color .4s,background-color .4s,border .4s;
        }
        

    </style>
</head>
<div class="header">
    <a href="http://tickets.bikerent.nyc/bigbike/agent/main"><img src="http://tickets.bikerent.nyc/images/favicon-small.png" alt="" style="float: left;"></a>
    <a class="logo" href="http://tickets.bikerent.nyc/bigbike/agent/main"><font color="#61982d">BIKE</font><font color="#676767">RENT</font><font color="green">.</font><font color="black">NYC</font></a>


</div>
<body>

<div class="logo2">
    <img src="{{ URL::to('images/favicon.png') }}" width="200px" height="200px"></div>

<h3>Welcome to {!! $name !!} website!</h3><br>
@if(count($errors)>0)
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>{{ $error }}}</p>
        @endforeach
    </div>
@endif
<h3>{{ $msg }}</h3>
<form action="{{ route('agent.showResetPasswordPage') }}" method="post">
    <div class="form-group">
        <input type="hidden" id="remember_token" name="remember_token" value="{{ $remember_token }}" class="form-control">
    </div>
    {{ csrf_field() }}
    <div><button type="submit" style="margin-bottom: 7px" class="btn btn-primary">Submit</button></div>
</form>

</body>
</html>


