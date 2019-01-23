@extends('layouts.master')

@section('title')
    Checkout
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/checkout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/demo-events.css') }}" />
@endsection

@section('content')

    <div class="row">
        <form method="post" id="create_invoice" action ={{ URL::to('bigbike/agent/phoneReservation/form-inv') }}>
            <h2 style="color:white">NEW INVOICE</h2>
           <span>Organization name</span><input type="text" id="organization" name="ogranization" required placeholder="Company name" />
            <span>Email </span><input type="text" name="email" id="email" required placeholder="name@gmail.com"/>
            <span>Description</span> <input type="text" id="description" name="description" required placeholder="2 Adult bikes for all day."/>
            <span>Total price after tax $ </span><input type="price" name="price" id="price" required placeholder="0.00"/>
            <button id="new_invoice">CREATE A NEW INVOICE</button>
            {{ csrf_field() }}

        </form>
    </div>
    @if (Session::has('invoice_id'))
        <div class="alert alert-info">Thanks, you created and send email, invoice number {{(Session::get('invoice_id'))}} </div>
    @endif



@endsection

<style>
    span{color:white;font-size: 16px}
</style>
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
    <script>

    </script>
@endsection
