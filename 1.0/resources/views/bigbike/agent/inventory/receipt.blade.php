@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/esignature.css') }}" >

    {{--<link rel="stylesheet" type="text/css" href="{{ URL::to('css/style_canvasreceipt.css ') }}" >--}}

    <style>

        .receipt-text{

            font-size: 12px;
            line-height:1.4rem;
        }
        #main-text{
            font-size:15px;
            line-height:1.5rem;

        }
    </style>
@endsection

@section('content')

    <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
    <button style="margin-left: 100px;" class="btn btn-danger shak"  onclick="window.location.href='/bigbike/agent/return'">Go to return page</button>
    <button style="margin-left: 100px;" class="btn btn-success shak2"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button>

    @if(Session::has('rent_success'))
        {{ Session::forget('rent_success') }}
        <div class="text-center" >

            {{--<button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>--}}
            {{--<button style="margin-left: 100px;" class="btn btn-danger shak"  onclick="window.location.href='/bigbike/agent/return'">Go to return page</button>--}}
            {{--<button style="margin-left: 100px;" class="btn btn-success shak2"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button>--}}

            <br>

            <div id="main-text">
                <div class="text-center " ><p><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                        </strong><br>

                        Served by: {{ $caisher_name }}<br></p>
                    {{--<div id="charge-message" class="alert alert-success">--}}
                    @if( $agent_rents_order['sequantial'])Receipt#: {{ $agent_rents_order['sequantial'] }}<br>@endif
                    <?php
                    //$tmp = ;
                    $time = explode(" ",$agent_rents_order['time']);
                    $time = $time[1];
                    $hour = intval(explode(':',$time)[0]);
                    $tmp = new DateTime($agent_rents_order['time']);
                    $endTime = new DateTime($agent_rents_order['end_time']);

                    $test = explode(' ',$endTime->format('h:ia  Y-m-d'));

                    ?>

                    <p>Rent Date: {{ str_replace("/","-",$agent_rents_order['date']) }}<br>
                        Duration:{{ $agent_rents_order['duration'] }}<br>
                        Rent Time: <?php echo $tmp->format('h:ia '); ?><br></p>

                    <p> Must return by:<br><strong style="font-size:19px;">{{ $test[0] }}</strong><br></p>
                    {{ $test[2] }}<br>
                    {{--Order Complated At: {{ $agent_rents_order['completed_at'] }}<br>--}}
                    {{--Agent Email: {{ $agent_rents_order['cashier_email'] }}<br>--}}
                    {{--@if(!empty($agent_rents_order['extra_service_payment_type']))--}}
                    {{--Extra Service Payment Type: {{ $agent_rents_order['extra_service_payment_type'] }}<br>--}}
                    {{--@if($agent_rents_order['extra_service_payment_type']=='credit_card') <p>Paypal Transaction #:{{ $agent_rents_order['extra_order_id'] }} @endif--}}
                    {{--Extra Total after tax: ${{ $agent_rents_order['extra_service_total_after_tax'] }}<br>--}}
                    {{--@if($agent_rents_order['extra_service_payment_type']=='cash')--}}
                    {{--Paid: ${{ $agent_rents_order['extra_service_rendered_cash'] }}<br>--}}
                    {{--Change: ${{ floatval($agent_rents_order['extra_service_rendered_cash'])-floatval($agent_rents_order['extra_service_total_after_tax']) }}<br><br>--}}
                    {{--@endif--}}
                    {{--@endif--}}

                    {{--Balance due: ${{ floatval($agent_rents_order['total_price_after_tax'])-floatval($agent_rents_order['agent_price_after_tax']) }}<br>--}}
                    {{--Agent charged: ${{ $agent_rents_order['agent_price_after_tax'] }}<br>--}}
                    @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br>@endif
                    Customer: <strong>{{ $agent_rents_order['customer_name'].' '.$agent_rents_order['customer_lastname'] }}</strong><br>
                    @if( $agent_rents_order['customer_email'])Customer Email: {{ $agent_rents_order['customer_email'] }}<br>@endif
                    @if( $agent_rents_order['customer_address_phone'])Customer Address & Phone: {{ $agent_rents_order['customer_address_phone'] }}<br>@endif
                    @if($agent_rents_order['payment_type']=='Credit Card') <p>Paypal Transaction #: {{ $agent_rents_order['order_id'] }} <br>@endif

                        @if( $agent_rents_order['adult']!='0')Adult Bike: <strong>{{ $agent_rents_order['adult'] }}</strong><br>@endif
                        @if( $agent_rents_order['child']!='0')Children Bike: <strong>{{ $agent_rents_order['child'] }}</strong><br>@endif
                        @if( $agent_rents_order['tandem']!='0')Tandem Bike: <strong>{{ $agent_rents_order['tandem'] }}</strong><br>@endif
                        @if( $agent_rents_order['road']!='0')Road Bike: <strong>{{ $agent_rents_order['road'] }}<strong><br>@endif
                                @if( $agent_rents_order['mountain']!='0')Mountain Bike: <strong>{{ $agent_rents_order['mountain'] }}</strong><br>@endif
                                @if( $agent_rents_order['trailer']!='0')Trailer: <strong>{{ $agent_rents_order['trailer'] }}</strong><br>@endif
                                @if( $agent_rents_order['seat']!='0')Baby Seat:{{ $agent_rents_order['seat'] }}<br>@endif
                                @if( $agent_rents_order['basket']!='0')Basket: <strong>{{ $agent_rents_order['basket'] }}</strong><br>@endif
                                @if( $agent_rents_order['lock']!='0')Lock: {{ $agent_rents_order['lock'] }}<br>@endif

                                Drop off: @if($agent_rents_order['dropoff']=='1') Yes @else No @endif<br>
                                Insurance: @if($agent_rents_order['insurance']=='1') Yes @else No @endif<br>

                                @if($agent_rents_order['agent_name'])Agent: {{ $agent_rents_order['agent_name'] }}<br>@endif
                                @if($agent_rents_order['adjust_price'])Adjusted Price: <strong>${{ $agent_rents_order['adjust_price'] }}</strong><br>@endif

                                @if( $agent_rents_order['comment'])Comment: {{ $agent_rents_order['comment'] }}@endif<br><p></p>

                    Deposit/ID @if( $agent_rents_order['deposit_pay_type']!='ID'){{ '('.$agent_rents_order['deposit_pay_type'].')' }}@endif: @if($agent_rents_order['deposit']=='ID') {{ $agent_rents_order['deposit'] }} @else ${{ $agent_rents_order['deposit'] }} @endif<br>

                    @if(!empty($agent_rents_order['extra_service_payment_type']))
                        Extra Service Payment Type: {{ $agent_rents_order['extra_service_payment_type'] }}<br>
                        @if($agent_rents_order['extra_service_payment_type']=='credit_card') <p>Paypal Transaction #:{{ $agent_rents_order['extra_order_id'] }} @endif
                            Extra Total after tax: ${{ $agent_rents_order['extra_service_total_after_tax'] }}<br>
                            @if($agent_rents_order['extra_service_payment_type']=='cash')
                                Paid: ${{ $agent_rents_order['extra_service_rendered_cash'] }}<br>
                                Change: ${{ floatval($agent_rents_order['extra_service_rendered_cash'])-floatval($agent_rents_order['extra_service_total_after_tax']) }}<br><br>
                            @endif
                            @endif

                            @if(!empty($agent_rents_order['extra_deposit_pay_type']) )
                                Extra Deposit/ID: @if($agent_rents_order['extra_deposit_pay_type']!='ID')$@endif{{ $agent_rents_order['extra_deposit'] }}<br>
                            @endif


                            Total @if($agent_rents_order['payment_type']=='coupon')({{$agent_rents_order['payment_type']}})@endif: ${{ number_format(floatval($agent_rents_order['total_price_before_tax']),2) }}<br>
                            @if($agent_rents_order['payment_type']!='coupon')
                                NYC Tax(8.875%): ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br>
                            @endif
                            <?php
                            if($agent_rents_order['deposit']=='ID'){
                                $deposit = 0;
                            } else{
                                $deposit = floatval($agent_rents_order['deposit']);
                            }
                            ?>
                            @if($agent_rents_order['payment_type']!='coupon')
                                Total after Tax({{ $agent_rents_order['payment_type'] }}):<strong> ${{ number_format(floatval($agent_rents_order['total_price_after_tax']),2) }}</strong><br>
                        @endif
                        {{--<p>Payment Type: {{ $agent_rents_order['payment_type'] }}<br>--}}


                        <div style="margin: 0 auto;left: 0;right: 0; width:300px;position:absolute">
                            <br>
                            <?php
                            echo '<img id="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39") . '" alt="barcode"  style="width: 60%;" />';
                            //                    $data = "data:image/png;base64," . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39");
                            //
                            //                    list($type, $data) = explode(';', $data);
                            //                    list(, $data)      = explode(',', $data);
                            //                    $data = base64_decode($data);

                            //                    file_put_contents('asassads.png', $data);
                            //                                            echo DNS1D::getBarcodeHTML($agent_rents_order['barcode'], "C39");

                            ?>
                        </div>
                        <br><br><br><br><br>

                </div>
            </div>



            <div class="receipt-text"><p>
                    <u>Activity:</u>
                    I have chosen to rent and participate in bike rental services (hereinafter referred to
                    as “the Activity”, which is organized by Central Park Bike Tours (hereinafter referred
                    to as “CPBT”) I understand that the Activity is inherently hazardous and I may be exposed
                    to dangers and hazards, including some of the following: falls, injuries associated with a
                    fall, injuries from lack of fitness, death, equipment failures and negligence of others.
                    As a consequence of these risks, I may be seriously hurt or disabled or may die from the
                    resulting injuries and my property may also be damaged.
                    In consideration of the permission to participate in the Activity, I agree to the terms
                    contained in this contract. I agree to follow the rules and directions for the Activity,
                    including any New York State traffic laws and
                    park rules
                </p>
                <p><u>Liability:</u>
                    All adult customers assume full liability and ride at their own risk. If you feel that you
                    or anyone in your party cannot operate a bicycle safely and competently, that person should
                    not rent or ride a bicycle. All children are to be supervised at all times by their parents
                    or an adult over the age of 18.
                    Children under the age of 14 must wear a helmet pursuant to New York State Law.
                    With the purchase of bicycle services, you hereby release and hold harmless from all
                    liabilities, causes of action, claims and demands that may arise in any way from injury,
                    death, loss or harm that may occur. This release does not extend to any claims for gross
                    negligence, intentional or reckless misconduct.
                    I acknowledge that CPBT has no control over and assumes no responsibility for the actions
                    of any independent contractors providing any services
                    for the Activity
                </p>
                <u>Bike Rental Insurance:</u>
                Bike rental insurance is available at additional cost.  Customer who didn't purchase bike rental insurance, have to pay full amount $400 for a lost or stolen bike. Customers who had purchased Bike Rental Insurance are indemnified and protected against 100% of the cost of damages and repairs; and 50% of replacement cost. Customers are not responsible for costs of repairs to damages bicycles during normal use, wear and tear when they purchase Bike Rental Insurance. Bike Rental Insurance does not indemnify for any cost or liability that arises as a result of personal injury, coverage shall apply only to property damage. Bike Rental Insurance includes damaged bike-pick up within Central Park only.


                <p>
                    <u>Late Fee:</u>
                    A 15-minute grace period shall be allowed for the return of bicycles following the cessation
                    of bike rental period, with no late fee charged. If you do not return any bicycle or child
                    seat for any reason before that 15-minute grace period, the hourly late fee begins calculating
                    and you will be required to pay an appropriate late fee. Late Fee Prices: Adult Bikes, Child
                    Bikes and Child Bike Trailers = $10 per bike-per hour; Tandem Bikes, Road Bikes, Mountain
                    Bikes and Hand-cycles = $20 per hour-per bike; Child Seat = $5 per hour-per seat. Late fees
                    are not prorated and any minute-used of an hour, constitutes full use of that hour. Late fee
                    may not be waived except by cause or emergency, and only with approval of Manager.
                </p>
                <p><u>All Sales Are Final:</u>
                    No bicycle may be rented without signature and liability acceptance of a responsible adult.
                    No cash refund for any reason; nor may the store credit be applied for unused bicycle during
                    rental time.</p>

                <div><div style="text-align: left">X</div><div style="text-align: right">Signature</div><hr style="border: 1px dashed grey;"></div>
            </div>
            <br>
            <script>window.print();</script>

            @if(Session::has('cashierEmail'))
                {{--{{Session::get('cashierEmail')}}--}}
                @if(Session::has('rent_id'))
                    {{Session::get('rent_id')}}
                @endif




                @if(Session::get('cashierEmail')=='s.tcukanov@gmail.com' || Session::get('cashierEmail')=='xdrlmadrid@gmail.com')
                    <div class="signn">
                        <div><div class="wrapper">
                                <img src="{{ URL::to('images/white.jpg') }}" width=800 height=300 />
                                <canvas id="signature-pad" class="signature-pad" width=800 height=300></canvas>
                                <span id="save" class="" style="position: absolute;top: 45%;right:30px;font-size:26px;cursor: pointer">Done</span>
                                <span id="clear" class="btnn" style="position: absolute;top: 10px;right: 20px;cursor: pointer;font-size:38px">X</span>
                            </div>
                        </div></div>

                    <?php
                    if($agent_rents_order['esignature']!=null){
                        $imgPath = 'images/esignature/'.$agent_rents_order['esignature'];
                    }else{
                        $imgPath = null;
                    }
                    ?>
                    <img src="{{ URL::to($imgPath) }}" alt="" style="float: left;width:100px;height:50px">
                    <div style="text-align:center"><img id="img" class="signature-img" src="" style="width:30%" /></div>







                    {{--<span id="sendBtn" onclick="onSendMessage()">CLICK</span>--}}

                    {{--<div id="overlay" style="display: none;">--}}
                    {{--<div id="nowPrintingWrapper">--}}
                    {{--<section id="nowPrinting">--}}
                    {{--<h1>Now Printing</h1>--}}
                    {{--<p><img src="./StarWebPRNT Sample(Canvas Receipt)_files/icon_loading.gif"></p>--}}
                    {{--</section>--}}
                    {{--</div>--}}
                    {{--<div id="nowLoadingWrapper" style="display: none;">--}}
                    {{--<section id="nowLoading">--}}
                    {{--<h1>Now Loading</h1>--}}
                    {{--<p><img src="./StarWebPRNT Sample(Canvas Receipt)_files/icon_loading.gif"></p>--}}
                    {{--</section>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<form onsubmit="return false;" id="form" style="display: block;">--}}
                    {{--<div class="container">--}}
                    {{--<div class="wrapper">--}}
                    {{--<div id="canvasBlock">--}}
                    {{--<div id="canvasFrame">--}}
                    {{--<canvas id="canvasPaper" width="384" height="555" style="width: 700px;">--}}
                    {{--Your browser does not support Canvas!--}}
                    {{--</canvas>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div id="optionBlock" style="display:none;">--}}
                    {{--<dl>--}}
                    {{--<dt>Font</dt>--}}
                    {{--<dd>:--}}
                    {{--<select id="font" onchange="onDrawReceipt(); refocusFontSelectbox();">--}}
                    {{--<option selected="selected">Arial</option>--}}
                    {{--<option>Cambria</option>--}}
                    {{--<option>Comic Sans MS</option>--}}
                    {{--<option>Constantia</option>--}}
                    {{--<option>Gabriola</option>--}}
                    {{--<option>Georgia</option>--}}
                    {{--<option>Segoe UI</option>--}}
                    {{--<option>Fixedsys</option>--}}
                    {{--<option>MS Serif</option>--}}
                    {{--</select>--}}
                    {{--&nbsp;<input id="italic" type="checkbox" onclick="onDrawReceipt()">Italic--}}
                    {{--</dd>--}}
                    {{--</dl>--}}
                    {{--<dl>--}}
                    {{--<dt>Paper Width</dt>--}}
                    {{--<dd>:--}}
                    {{--<select id="paperWidth" onchange="onResizeCanvas(); refocusWidthSelectbox();">--}}
                    {{--<option value="inch2" selected="selected">2 Inch</option>--}}
                    {{--<option value="inch3">3 Inch</option>--}}
                    {{--<option value="inch4">4 Inch</option>--}}
                    {{--</select>--}}
                    {{--</dd>--}}
                    {{--</dl>--}}
                    {{--</div>--}}
                    {{--<hr>--}}
                    {{--<footer>--}}
                    {{--<dl>--}}
                    {{--<dt>URL</dt>--}}
                    {{--<dd>:--}}
                    {{--<input id="url" type="text" value="https://localhost:8001/StarWebPRNT/SendMessage"></dd>--}}
                    {{--</dl>--}}
                    {{--<d1>--}}
                    {{--<dt>Paper Type</dt>--}}
                    {{--<dd>:--}}
                    {{--<select id="papertype">--}}
                    {{--<option value="" selected="selected">-</option>--}}
                    {{--<option value="normal">Normal</option>--}}
                    {{--<option value="black_mark">Black Mark</option>--}}
                    {{--<option value="black_mark_and_detect_at_power_on">Black Mark and Detect at Power On</option>--}}
                    {{--</select>--}}
                    {{--</dd>--}}

                    {{--<d1>--}}
                    {{--<dt>Black Mark Sensor</dt>--}}
                    {{--<dd>:--}}
                    {{--<select id="blackmark_sensor">--}}
                    {{--<option value="front_side" selected="selected">Front side</option>--}}
                    {{--<option value="back_side">Back side</option>--}}
                    {{--<option value="hole_or_gap">Hole or Gap</option>--}}
                    {{--</select>--}}
                    {{--</dd>--}}
                    {{--</d1>--}}
                    {{--<input id="sendBtn" type="button" value="Send" onclick="onSendMessage()">--}}
                    {{--</div>--}}
                    {{--</form>--}}


                @endif
            @endif

        </div>

    @else
        {{--<div>No Transaction!</div>--}}
    @endif


    @if(Session::has('inventory_success'))
        {{ Session::forget('inventory_success') }}
        <div class="text-center" >

            <div id="main-text">
                <div class="text-center " ><p><strong>Central Park Bike Rent<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                        </strong><br>

                        Served by: {{ $caisher_name }}<br></p>
                    {{--<div id="charge-message" class="alert alert-success">--}}
                    <p>Customer: {{ Session::get('inv_cart')["firstname"]." ".Session::get('inv_cart')["lastname"] }}</p>
                    @php
                        $cart = Session::get("inv_cart");
                    @endphp
                    @foreach($cart as $key => $value)
                        @if($key!="price" && $key!="firstname" && $key!="lastname")
                        Item: {{$value["title"]}}.<br>
                        Number: {{ $value["qty"] }}<br>
                        @endif
                        @if($key=="price")
                        Total: {{ "$".$value }}
                        @endif
                    @endforeach

                        <br><br><br><br><br>

                </div>
            </div>




            <br>
            <script>window.print();</script>
        </div>
        {{Session::forget("inv_cart")}}
    @else
        {{--<div>No Transaction!</div>--}}
    @endif

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <script>
        var id = '<?php echo Session::get('rent_id'); ?>';
        console.log('id: '+id);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });
        var wrapper = document.getElementsByClassName('wrapper');
        var saveButton = document.getElementById('save');

        saveButton.addEventListener('click', function (event) {
            var dataURL = signaturePad.toDataURL();
            document.getElementById('img').setAttribute( 'src', dataURL );

//            console.log(dataURL);



// Send data to server instead...
//            window.open(data);
            console.log("img: "+dataURL);
            $.ajax({
                type: "POST",
                cache: false,
                url: '/bigbike/agent/esignature/store',
                data: {"dataURL":dataURL,
                    'id':id,
                },
                success: function(data) {
                    swal({
                        title: "Signature was uploaded",
                        timer: 1500,
                        showConfirmButton: true
                    });
                    console.log("Success");
                    console.log("success: "+data['type']);
//                    $(".signn").delay(2000).fadeOut('5000',function () {
////                        window.onload=function(){self.print();}
//                        window.print();
//                    });

                    if(data['type']=='error'){
                        console.log("error: "+data['response']);
//                        swal(data['response']);
                    } else if(data['type']=='reservation' || data['type']=='return'|| data['type']=='good'){
//                        window.location.replace(data['url']);
                        console.log("no error: "+data['response']);

                    }

                }

            });
        });

        $('.btnn').on('click', function (event) {
            signaturePad.clear();
        });



        function  reloadPage() {
            $('html,body').animate({scrollTop: document.body.scrollHeight},"slow");
            $('#signature-pad').click();
        }
        reloadPage();

    </script>

    <script type="text/javascript">
        $('.container').removeClass();
        $('#save').on('click',function(){
            $('.wrapper').hide();
            window.print();
        });




        //        window.onload = function() {
        //
        //            document.addEventListener("contextmenu", function(e){
        //                e.preventDefault();
        //            }, false);
        //            document.addEventListener("keydown", function(e) {
        //                //document.onkeydown = function(e) {
        //                // "I" key
        //                if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        //                    disabledEvent(e);
        //                }
        //                if (e.altKey && e.keyCode == 73 && e.keyCode == 91) {
        //                    disabledEvent(e);
        //                }
        //                // "J" key
        //                if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        //                    disabledEvent(e);
        //                }
        //
        //                // "S" key + macOS
        //                if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        //                    disabledEvent(e);
        //                }
        //                // "U" key
        //                if (e.ctrlKey && e.keyCode == 85) {
        //                    disabledEvent(e);
        //                }
        //                // "F12" key
        //                if (event.keyCode == 123) {
        //                    disabledEvent(e);
        //                }
        //            }, false);
        //            function disabledEvent(e){
        //                if (e.stopPropagation){
        //                    e.stopPropagation();
        //                } else if (window.event){
        //                    window.event.cancelBubble = true;
        //                }
        //                e.preventDefault();
        //                return false;
        //            }
        //        };

    </script>



@endsection
