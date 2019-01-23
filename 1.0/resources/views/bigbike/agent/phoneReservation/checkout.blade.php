@extends('layouts.master')

@section('title')
    Checkout
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/checkout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/demo-events.css') }}" />


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

    <div id="link_invoice" style="    z-index: 100055;
    background: white;
    position: absolute;border-radius: 10px;
    padding:15%;">
        <h2> Would you like to create an invoice or  charge a customer?</h2>
        <button onclick="show_inv()">Charge a customer</button>
        <a href="{{ URL::to('bigbike/agent/phoneReservation/createInvoice') }}">
        <button >Create a new invoice</button></a>
    </div>
    <div class="wrap">

        <h2 class="pb" style="color:#ffffff;padding-bottom: 20px">Credit Card Payment</h2>

        <ul class="cc_images group">
            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/visa.png" class="cc_img" alt="Visa" id="visa"></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>

            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/mastercard.png" class="cc_img" alt="MasterCard" id="mastercard"></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>

            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/amex.png" class="cc_img" alt="American Express" id="amex"></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>

            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/discover.png" class="cc_img" alt="Discover" id="discover"></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>

            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/dinersclub.png" class="cc_img" alt="Diners Club" id=""></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>

            <div class="card">
                <div class="inside">
                    <div class="front"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/maestro.png" class="cc_img" alt="Maestro" id="Maestro"></div>
                    <div class="back"><img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/74196/credit.png" class="cc_img" alt="Back of Card"></div>
                </div>
            </div>
        </ul>


        <form action="@if(Session::has('rent')) {{ route('agent.ppCheckout') }} {{Session::forget('rent')}}
        @elseif(Session::has('tour')) {{ route('agent.ppTourCheckout') }} {{Session::forget('tour')}}
        @elseif(Session::has('member')) {{ route('agent.ppMemberCheckout') }} {{Session::forget('member')}}
        @elseif(Session::has('rent_reserve')) {{ route('agent.rentReserveCheckout') }} {{Session::forget('rent_reserve')}}
        @elseif(Session::has('rent_return')) {{ route('agent.rentReturnCheckout') }} {{Session::forget('rent_return')}}
        @elseif(Session::has('rent_edit')) {{ route('agent.rentReserveCheckout') }} {{Session::forget('rent_edit')}}
        @elseif(Session::has('tour_edit')) {{ route('agent.tourReserveCheckout') }} {{Session::forget('tour_edit')}}
        @elseif(Session::has('deposit')) {{ route('agent.rentDepositCheckout') }} {{Session::forget('deposit')}}
        @elseif(Session::has('tour_deposit')) {{ route('agent.tourDepositCheckout') }} {{Session::forget('deposit')}}
        @elseif(Session::has('inventory')) {{ route('agent.inventoryCheckout') }} {{Session::forget('inventory')}}
        @elseif(Session::has('phoneReservation')) {{ route('agent.phoneReservationCheckout') }} {{Session::forget('phoneReservation')}}

        @endif" id="payment-form" class="cc_form" method="post">
            <div class="input-wrap group">
                <label for="cc_number"><i class="fa fa-credit-card"></i></label>
                <input type="text" maxlength = "16"
                       name="cc_number" id="cc_number" placeholder="Card Number..">
            </div>
            <div class="input-wrap group expiration-wrap" style="width: 50%;">
                <label for="cc_firstname"><i class="fa fa-user"></i></label>
                <input type="text" name="cc_firstname" id="cc_firstname" placeholder="First Name.." value="">
            </div>
            <div class="input-wrap group cvc-wrap" style="width: 48%;">
                <label for="cc_lastname"><i class="fa fa-user"></i></label>
                <input type="text" name="cc_lastname" id="cc_lastname" placeholder="Last Name.." value="">
            </div>
            <div class="input-wrap group expiration-wrap" style="width: 50%;">
                <label for="cc_phone"><i class="fa fa-phone-square"></i></label>
                <input type="text" name="cc_phone" id="cc_phone" placeholder="Phone.." value="">
            </div>

            <div class="input-wrap group cvc-wrap" style="width: 48%;">
                <label for="cc_email"><i class="fa fa-envelope-open-o"></i></label>
                <input type="text" name="cc_email" id="cc_email" placeholder="Email.." value="">
            </div>
            <div class="group exp-cvc">
                {{--<div class="input-wrap group expiration-wrap">--}}
                {{--<label for="cc_expiration"><i class="fa fa-calendar"></i></label>--}}
                {{--<input type="text" name="cc_expiration" id="cc_expiration" placeholder="Expiration..">--}}
                {{--</div>--}}

                <div class="input-wrap group expiration-wrap" >
                    <label for="cc_expiration"><i class="fa fa-calendar"></i></label>
                    <input type="text" maxlength = "2"
                           name="cc_exp_month" id="cc_exp_month" placeholder="Exp month..">
                </div>
                <div class="input-wrap group expiration-wrap" id="expiration-wrap3" >
                    <label for="cc_expiration"><i class="fa fa-calendar"></i></label>
                    <input type="text" name="cc_exp_year" maxlength = "2"
                           id="cc_exp_year" placeholder="Exp year..">
                </div>
                <div class="input-wrap group cvc-wrap" >
                    <label for="cc_cvc"><i class="fa fa-lock"></i></label>
                    <input type="text" required name="cc_cvc" id="cc_cvc" maxlength = "4"
                           placeholder="CVV..">
                </div>
            </div>
            <div class="group exp-cvc">
                <div class="input-wrap group ">
                    {{--<label for="cc_expiration"><i class="fa fa-calendar"></i></label>--}}
                    <input type="hidden" name="cc_amount" id="cc_amount" value="">
                </div>
            </div>
            <div class="group exp-cvc">
                <div class="input-wrap group ">
                    {{--<label for="cc_expiration"><i class="fa fa-calendar"></i></label>--}}
                    <input type="hidden" name="cc_type" id="cc_type" >
                </div>
            </div>
            <div class="group exp-cvc" style="margin-top: 10px;">
                <div class="input-wrap group ">
                    {{--<label for="cc_expiration"><i class="fa fa-calendar"></i></label>--}}
                    <select style="text-align: center;font-weight: bold;width: 480px;
    height: 30px;" name="served" id="served">
                        <option>Andrea Scott</option>
                        {{--<option>Melissa Suso</option>--}}
                        <option>Joe Camerota</option>
                    </select>
                </div>
            </div>

            <div class="group exp-cvc" style="margin-top: 10px;">
                <div class="input-wrap group ">
                    {{--<label for="cc_expiration"><i class="fa fa-calendar"></i></label>--}}
                    <input type="text" style="text-align: center;font-weight: bold" name="price"  id="price" pattern="[0-9.]+" placeholder="0.00">
                </div>
            </div>
                <input style="width: 5%; float: left;" type="checkbox" name="tax_checkbox" id="tax_checkbox">
                <div class="custom-control-description" style="color: white;padding-top: 14px;text-align: center;vertical-align: middle;float: left;">With NYC Tax</div>

            <br>
            <div style="width: 100%;">
                <textarea rows="2" cols="45" form="payment-form" name="description" id="description" class="field is-empty" placeholder="Description" style="opacity: 1;background-color: white;"></textarea>
            </div><br>
            {{ csrf_field() }}
            {{--<button type="submit" id='ppBtn' onclick="return checkValid()">submit</button>--}}
            <button type="submit" id='ppBtn' >submit</button>


        </form>

    </div>
@endsection

@section('scripts')
    {{--<script src="https://js.stripe.com/v3/"></script>--}}
    {{--<script src="{{ URL::to('js/checkout.js') }}"></script>--}}

    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>--}}
    {{--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>--}}
    {{--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.0.2/jquery.payment.min.js'></script>--}}

    {{--<script src="{{ URL::to('js/paypal.js') }}"></script>--}}


    <script src="{{ URL::to('js/jquery.cardswipe.js') }}"></script>

    <script type="text/javascript">
        $("form").submit(function(e) {

            var ref = $(this).find("[required]");

            $(ref).each(function(){
                if ( $(this).val() == '' )
                {
                    alert("Required field should not be blank.");

                    $(this).focus();

                    e.preventDefault();
                    return false;
                }$('#ppBtn').attr('disabled','true');

            });  return true;
        });

        // Called by plugin on a successful scan.
        var complete = function (data) {
            // Is it a payment card?

            document.activeElement.blur();


            if (data.type == "generic")
                return;
            // Copy data fields to form
            $("#cc_firstname").val(data.firstName);
            $("#cc_lastname").val(data.lastName);
            $("#cc_number").val(data.account);
            $("#cc_exp_month").val(data.expMonth);
            $("#cc_exp_year").val(data.expYear);
            $("#cc_type").val(data.type);

//            console.log('data: '+data);

            $('.cc_img').css({"opacity":"0.1"});

            if((data.type)=='visa')  {
                $(".cc_images #visa ").css({"opacity":"1","filter":"inherit"});
//                console.log('EEEEEE');
            }else if((data.type)=='mastercard')  {
                $(".cc_images #mastercard ").css({"opacity":"1","filter":"inherit"});
//                console.log('EEEEEE');
            }else if((data.type)=='discover')  {
                $(".cc_images #discover ").css({"opacity":"1","filter":"inherit"});
//                console.log('EEEEEE');
            }else if((data.type)=='amex')  {
                $(".cc_images #amex ").css({"opacity":"1","filter":"inherit"});
//                console.log('EEEEEE');
            }
//            else{
//                $(".cc_images .card").css("background", "white");
////                console.log('EEEEEE');
//            }

//            console.log($("#cc_number").is(":focus"));
//            if($("#cc_number").is(":focus")){
//
//            }
        };

        // Event handler for scanstart.cardswipe.
        var scanstart = function () {
            $("#overlay").fadeIn(200);
        };
        // Event handler for scanend.cardswipe.
        var scanend = function () {
            console.log('OEE');
            $("#overlay").fadeOut(200);
        };
        // Event handler for success.cardswipe.  Displays returned data in a dialog
        var success = function (event, data) {
            $("#properties").empty();
            // Iterate properties of parsed data
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                    var text = key + ': ' + data[key];
                    $("#properties").append('<div class="property">' + text + '</div>');
                }

            }
        }
        var failure = function () {
            $("#failure").fadeIn().delay(1000).fadeOut();
        }
        // Initialize the plugin with default parser and callbacks.
        //
        // Set debug to true to watch the characters get captured and the state machine transitions
        // in the javascript console. This requires a browser that supports the console.log function.
        //
        // Set firstLineOnly to true to invoke the parser after scanning the first line. This will speed up the
        // time from the start of the scan to invoking your success callback.
        $.cardswipe({
            firstLineOnly: true,
            success: complete,
            parsers: ["visa", "amex", "mastercard", "discover", "generic"],
            debug: false
        });

        // Bind event listeners to the document
        $(document)
            .on("scanstart.cardswipe", scanstart)
            .on("scanend.cardswipe", scanend)
            .on("success.cardswipe", success)
            .on("failure.cardswipe", failure)
        ;




        $('#cc_number').keyup(function() {
            //VISA
            re = new RegExp("^4");
            if($('#cc_number').val().match(re) != null){
                $('.cc_images #visa').css({"opacity":"1","filter":"inherit"});
                $('#cc_type').val('visa');
            }
            else{
                $('.cc_images #visa').css({"opacity":".1","filter":"grayscale(100%)"});

            }

            //MASTERCARD
            re = new RegExp("^5[1-5]");
            if($('#cc_number').val().match(re) != null){
                $('.cc_images #mastercard').css({"opacity":"1","filter":"inherit"});
                $('#cc_type').val('mastercard');
            }
            else{
                $('.cc_images #mastercard').css({"opacity":".1","filter":"grayscale(100%)"});

            }
            //AMEX
            re = new RegExp("^3[47]");
            if($('#cc_number').val().match(re) != null){
                $('.cc_images #amex').css({"opacity":"1","filter":"inherit"});
                $('#cc_type').val('amex');


            }
            else{
                $('.cc_images #amex').css({"opacity":".1","filter":"grayscale(100%)"});

            }
            // Discover
            re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
            if($('#cc_number').val().match(re) != null){
                $('.cc_images #discover').css({"opacity":"1","filter":"inherit"});
                $('#cc_type').val('discover');
            }
            else{
                $('.cc_images #discover').css({"opacity":".1","filter":"grayscale(100%)"});
            }

        });
        $(document).on("keydown", "input", function(e) {
            if (e.which==13){
                e.preventDefault();
            }

        });

        $("#tax_checkbox").change(function() {
            if($(this).prop('checked') == true) {
                //console.log("Checked Box Selected");
                $('#price').val((parseFloat($('#price').val().trim())*1.08875).toFixed(2));
                $('#cc_amount').val($('#price').val().trim());

            } else {
                //console.log("Checked Box deselect");
                $('#price').val((parseFloat($('#price').val().trim())/1.08875).toFixed(2));
                $('#cc_amount').val($('#price').val().trim());
            }
        });

        $("#price").keyup(function() {
            $('#cc_amount').val((parseFloat($('#price').val().trim())).toFixed(2));
        });
$('#cc_email').attr('required','true');
function show_inv() {
    $('#link_invoice').hide();
}
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
    {{--<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>--}}
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.0.2/jquery.payment.min.js'></script>
    {{--<script src="{{ URL::to('js/notify.js') }}"></script>--}}

    <script src="{{ URL::to('js/paypal.js') }}"></script>


@endsection