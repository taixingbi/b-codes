@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/rent-main.css') }}" >
@endsection

@section('content')
    {{--<div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>--}}

    {{--<h2>Daily Report </h2>--}}
    {{--<div class="text-center">--}}
    {{--<h2>Location Summary</h2>--}}
    {{--</div>--}}
    {{--{{ dd($locations) }}--}}
    <div  >
        <div id="dropin-container"></div>
        <button id="submit-button">Request payment method</button>

    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.13.0/js/dropin.min.js"></script>
{{--    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>--}}
    {{--<script src="{{ URL::to('js/agent-report.js') }}"></script>--}}
    <script>
        var sandbox_tokenization_key = '<?php echo $sandbox_tokenization_key;?>'
        // For Drop-in...
        // braintree.dropin.create({
        //     authorization: tokenizationKey
        // }, function (err, dropinInstance) {
        //     // ...
        // });
        //
        // // For custom...
        // braintree.client.create({
        //     authorization: tokenizationKey
        // }, function (err, clientInstance) {
        //     // ...
        // });

        var button = document.querySelector('#submit-button');

        braintree.dropin.create({
            authorization: sandbox_tokenization_key,
            container: '#dropin-container'
        }, function (createErr, instance) {
            button.addEventListener('click', function () {
                instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                    // Submit payload.nonce to your server
                });
            });
        });
    </script>
@endsection
