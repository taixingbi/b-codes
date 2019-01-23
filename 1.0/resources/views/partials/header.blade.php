<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div>
                <a href="@if(Session::has('cashierEmail') && Session::get('cashierEmail')=='test@testtestestest.com') {{ route('agent.phoneReservation') }} @else{{ route('agent.rentOrder') }}@endif"><img src="{{ URL::to('images/logo-bike-rent.svg') }}" alt="" style="float: left;width:100px;height:50px"></a>
                <a class="navbar-brand logo"  href="@if(Session::has('cashierEmail') && Session::get('cashierEmail')=='test@testtestestest.com') {{ route('agent.phoneReservation') }} @else{{ route('agent.rentOrder') }}@endif" >BIKE<font color="#676767">RENT</font><font color="green">.</font><font color="black">NYC</font></a>
            </div>

        </div>

        <?php
            //set headers to NOT cache a page
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        ?>


        <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user-circle-o" aria-hidden="true"></i>
                         User Management <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::check())
                            <li><a href="{{ route('user.logout') }}">Logout</a></li>
                        @else
                            {{--<li><a href="{{ route('user.signup') }}">Sign up</a></li>--}}
                            <li><a href="{{ route('user.signin') }}">Sign in</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
            <span style="float: right; margin-top:14px;">
                @if(Session::has('location'))
                    <span style="margin-right: 20px;">Location: {{ Session::get('location') }}</span>
                @endif
                @if(Session::has('title'))
                    {{  Session::get('title') }}:
                @endif
                @if(Session::has('cashier'))
                    {{ Session::get('cashier') }}
                @endif

            </span>

        </div><!-- /.navbar-collapse -->
        </div>
    </div><!-- /.container-fluid -->
</nav>