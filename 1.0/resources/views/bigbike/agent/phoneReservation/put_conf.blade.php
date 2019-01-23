@extends('layouts.master')

@section('title')
    Checkout
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/checkout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('css/demo-events.css') }}" />
@endsection

@section('content')
    @if(Session::has('error'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('error') }}
                    {{ Session::forget('error') }}
                </div>
            </div>
        </div>



    @endif

    <div class="row">
        <form method="POST" id="con_invoice" action="{{URL::to('invoice/check_conf') }}">
            <h3 style="color:white">PLEASE ENTER YOUR REFERENCE NUMBER</h3><input type="text" id="reference" name="reference" required />
            <button>SEND</button>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

        </form>
    </div>
 </div>
@endsection


@section('scripts')

    <script>
        $("form").submit(function(e) {
            var ref = $(this).find("[required]");
            $(ref).each(function(){
                if ( $(this).val() == '' )
                {
                    alert("Required field should not be blank.");
                    $(this).focus();
                    e.preventDefault();
                    return false;
                }
            });
            return true;
        });
    </script>
@endsection
