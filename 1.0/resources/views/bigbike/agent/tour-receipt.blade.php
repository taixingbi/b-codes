@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
    <style>
        .receipt-text{

            font-size: 10px;
            line-height:1.0rem;
        }
        #main-text{
            font-size:12px;
            line-height:1.2rem;

        }
    </style>
@endsection

@section('content')

    @if(Session::has('tour_success'))
{{--        {{ Session::forget('tour_success') }}--}}
        <div class="text-center col-md-12" >

            <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
            <button style="margin-left: 100px;" class="btn btn-danger shak"  onclick="window.location.href='/bigbike/agent/return'">Go to return page</button>
            <button style="margin-left: 100px;" class="btn btn-success shak2"  onclick="window.location='{{ route('agent.tourTicket') }}' ">Print Ticket</button>
            

            <div id="main-text">
                <div class="text-center " ><p><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                            <br>Toll Free Number: 1-800-772-7174

                        </strong><br>

                    @if($caisher_name) Served by: {{ $caisher_name }}<br></p> @endif

                    @if( $agent_tours_order['sequantial'])Receipt#: {{ $agent_tours_order['sequantial'] }}<br>@endif


                    {{--@if($agent_tours_order['completed_at'])Order Complated At: {{ $agent_tours_order['completed_at'] }}<br>@endif--}}
                    @if($agent_tours_order['payment_type']) Payment Type: {{ $agent_tours_order['payment_type'] }}<br> @endif


                    <?php
                    //                        $tmp = ;
//                    $time = explode(" ",$agent_tours_order['real_time']);
//                    $time = $time[1];

                    $tmp = new DateTime('9AM '.$agent_tours_order['date']);
                    if (strpos($agent_tours_order['tour_type'], '2') != false) {
                        $tmpHour = 2;
                    }elseif (strpos($agent_tours_order['tour_type'], '3') !== false){
                        $tmpHour = 3;
                    }

                    //                    $tmpHour = $agent_tours_order['tour_type'];
                    //                    dd($tmpHour);

                    $tmp->add(new DateInterval("PT{$tmpHour}H"));

                    ?>
                    {{--                    Test: {{ $tmp->format('h:i:sA m/d/Y') }}<br><br>--}}

                    @if($agent_tours_order['customer_name']) Customer: <stong>{{ $agent_tours_order['customer_name'].' '.$agent_tours_order['customer_lastname'] }}</stong><br> @endif
                    @if($agent_tours_order['customer_email']) Email: {{ $agent_tours_order['customer_email'] }}<br>@endif

                    @if($agent_tours_order['payment_type']=='Credit Card') <p>Paypal Transaction #: {{ $agent_tours_order['order_id'] }} <br>@endif
                        @if($agent_tours_order['tour_place']) Tour : {{ $agent_tours_order['tour_place'] }}<br> @endif

                    @if($agent_tours_order['tour_type']) Tour Type: {{ $agent_tours_order['tour_type'] }}<br> @endif
                    @if($agent_tours_order['date']) Tour Date: {{ $agent_tours_order['date'] }}<br> @endif
                    @if($agent_tours_order['time']) Tour Start at:<strong> {{ $agent_tours_order['time'] }}</strong><br><br> @endif
                    {{--Must return by:<br><strong>{{ $tmp->format('h:i:s A')  }}</strong><br><br>--}}
                    {{--Order Complated At: {{ $agent_tours_order['completed_at'] }}<br>--}}

                    @if($agent_tours_order['adult']) Adult: {{ $agent_tours_order['adult'] }}<br> @endif
                    @if($agent_tours_order['child']) Children: {{ $agent_tours_order['child'] }}<br> @endif
                    @if($agent_tours_order['basket'])Basket: {{ $agent_tours_order['basket'] }}<br>@endif
                    @if($agent_tours_order['seat']) Seat: {{ $agent_tours_order['seat'] }}<br>@endif


                    @if($agent_tours_order['agent_name'])Agent: <strong>{{ $agent_tours_order['agent_name'] }}</strong><br>@endif
                    @if($agent_tours_order['adjust_price'])Adjusted Price: <strong>${{ $agent_tours_order['adjust_price'] }}</strong><br>@endif
                    @if( $agent_tours_order['comment'])Comment: {{ $agent_tours_order['comment'] }}<br>@endif

                    {{--Balance due: ${{ floatval($agent_rents_order['total_price_after_tax'])-floatval($agent_rents_order['agent_price_after_tax']) }}<br>--}}
                    {{--Agent charged: ${{ $agent_rents_order['agent_price_after_tax'] }}<br>--}}

                    Insurance: @if($agent_tours_order['insurance']=='1') Yes @else No @endif<br>
                    Deposit/ID @if( $agent_tours_order['deposit_pay_type']!='ID'){{ '('.$agent_tours_order['deposit_pay_type'].')' }}@endif: @if($agent_tours_order['deposit']=='ID') {{ $agent_tours_order['deposit'] }} @else ${{ $agent_tours_order['deposit'] }} @endif<br>

                    @if(!empty($agent_tours_order['extra_service_payment_type']))
                            Extra Service Payment Type: {{ $agent_tours_order['extra_service_payment_type'] }}<br>
                        @if($agent_tours_order['extra_service_payment_type']=='Credit Card') <p>Paypal Transaction #:{{ $agent_tours_order['extra_order_id'] }} @endif
                        @if(!empty($agent_tours_order['extra_deposit_pay_type'])) <p>Extra deposit:{{ $agent_tours_order['extra_deposit'] }} <br>@endif

                        <?php
                            if(!empty($agent_tours_order['deposit_pay_type']) && $agent_tours_order['deposit_pay_type']!='ID' ){
                                $extraDip = floatval($agent_tours_order['deposit']);
                            }else{
                                $extraDip = 0;
                            }
                        ?>
                        Extra Total after tax: ${{ $agent_tours_order['extra_service_total_after_tax'] }}<br>
                        @if($agent_tours_order['extra_service_payment_type']=='Cash')
                            Extra Paid: ${{ $agent_tours_order['extra_service_rendered_cash'] - $extraDip}}<br>
                            {{--Change: ${{ floatval($agent_tours_order['extra_service_rendered_cash'])-floatval($agent_tours_order['extra_service_total_after_tax'])-$extraDip }}<br><br>--}}
                        @endif
                    @endif<br>
                    Total @if($agent_tours_order['payment_type']=='coupon')({{$agent_tours_order['payment_type']}})@endif: ${{ number_format(floatval($agent_tours_order['total_price_before_tax']),2) }}<br>
                    @if($agent_tours_order['payment_type']!='coupon')
                    {{--NYC Tax(8.875%): ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}<br>--}}
                    Grand Total ({{$agent_tours_order['payment_type']}}):<strong> ${{ number_format(floatval($agent_tours_order['total_price_after_tax']),2) }}</strong> <br><br>
                    @endif


                    {{--@if(!empty($agent_rents_order['extra_service_payment_type']))--}}
                    {{--Extra Service: {{ $agent_rents_order['extra_service_payment_type'] }}<br>--}}
                    {{--Extra Total after tax: {{ $agent_rents_order['extra_service_total_after_tax'] }}<br>--}}
                    {{--@if($agent_rents_order['extra_service_payment_type']=='cash')--}}
                    {{--Paid: {{ $agent_rents_order['extra_service_rendered_cash'] }}<br>--}}
                    {{--Change: {{ floatval($agent_rents_order['extra_service_rendered_cash'])-floatval($agent_rents_order['extra_service_total_after_tax']) }}<br><br>--}}
                    {{--@endif--}}
                    {{--@endif--}}
                    <div>
                        @if(Session::has('inv_cart') && Session::get("inv_cart")["price"]>0)
                            {{--                {{ Session::forget('inventory_success') }}--}}
                            <div class="text-center" >

                                <div id="main-text">
                                    <div class="text-center " >

                                        {{--<div id="charge-message" class="alert alert-success">--}}
                                        Customer: <strong>{{ $agent_tours_order['customer_name'].' '.$agent_tours_order['customer_lastname'] }}</strong><br>
                                        @php
                                            $cart = Session::get("inv_cart");
                                        @endphp
                                        @foreach($cart as $key => $value)
                                            @if($key!="price" && $key!="firstname" && $key!="lastname")
                                                Item: {{$value["title"]}}.<br>
                                                Number: {{ $value["qty"] }}<br>
                                            @endif
                                            @if($key=="price")
                                                Total: <strong> {{ "$".$value }}</strong>
                                            @endif
                                        @endforeach
                                        @php
                                            Session::forget("inv_cart");
                                        @endphp

                                        <br><br><br><br><br>
                                        <div>Bike Tour & Bike Accessories</div>
                                        @php
                                            $total_tmp = number_format(Session::get("inv_cart")["price"]+floatval($agent_tours_order['total_price_after_tax']),2);
                                        @endphp
                                        <div>Total after Tax: ${{$total_tmp}}</div>
                                    </div>
                                </div>

                                <div style="margin: 0 auto;left: 0;right: 0; width:300px;position:absolute">

                                    <?php
                                    echo '<img id="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_tours_order['barcode'], "C39") . '" alt="barcode"  style="width: 70%;" />';
                                    //                    $data = "data:image/png;base64," . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39");
                                    //
                                    //                    list($type, $data) = explode(';', $data);
                                    //                    list(, $data)      = explode(',', $data);
                                    //                    $data = base64_decode($data);

                                    //                    file_put_contents('asassads.png', $data);
                                    //                                            echo DNS1D::getBarcodeHTML($agent_rents_order['barcode'], "C39");

                                    ?>
                                </div>
                                <br>
                                <script>window.print();</script>
                            </div>
                        @else
                            {{--<div>No Transaction!</div>--}}
                        @endif
                    </div>

                    <br>
                </div>
            </div>

    @endif



    @if(Session::has('tour_success'))
        {{ Session::forget('tour_success') }}

                <div class="receipt-text">
                <u>Activity:</u><p>
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
                    park rules</p>
                <br>
                <u>Liability:</u><p>
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
                    for the Activity</p>
                <br>
                <u>Bike Rental Insurance:</u><p>
                    Bike rental insurance is available at additional cost.  Customer who didn't purchase bike rental insurance, have to pay full amount $400 for a lost or stolen bike. Customers who had purchased Bike Rental Insurance are indemnified and protected against 100% of the cost of damages and repairs; and 50% of replacement cost. Customers are not responsible for costs of repairs to damages bicycles during normal use, wear and tear when they purchase Bike Rental Insurance. Bike Rental Insurance does not indemnify for any cost or liability that arises as a result of personal injury, coverage shall apply only to property damage. Bike Rental Insurance includes damaged bike-pick up within Central Park only.
                </p>
                <br>
                <u>Late Fee:</u><p>
                    A 15-minute grace period shall be allowed for the return of bicycles following the cessation
                    of bike rental period, with no late fee charged. If you do not return any bicycle or child
                    seat for any reason before that 15-minute grace period, the hourly late fee begins calculating
                    and you will be required to pay an appropriate late fee. Late Fee Prices: Adult Bikes, Child
                    Bikes and Child Bike Trailers = $10 per bike-per hour; Tandem Bikes, Road Bikes, Mountain
                    Bikes and Hand-cycles = $20 per hour-per bike; Child Seat = $5 per hour-per seat. Late fees
                    are not prorated and any minute-used of an hour, constitutes full use of that hour. Late fee
                    may not be waived except by cause or emergency, and only with approval of Manager.</p>
                <br>
                <u>All Sales Are Final:</u><p>
                    No bicycle may be rented without signature and liability acceptance of a responsible adult.
                    No cash refund for any reason; nor may the store credit be applied for unused bicycle during
                    rental time.</p>

                <div><div style="text-align: left">X</div><div style="text-align: right">Signature</div><hr style="border: 1px dashed grey;"></div>
            </div>
        </div>
    @else
        <div>No Transaction!</div>
    @endif
@endsection




@section('scripts')
    <script>
        var levshak = '<?php echo $user->level ;?>';

        if (levshak==='4'){
            $('.shak').hide();
        }
        if (levshak != 4){
            $('.shak2').hide();


        }
    </script>
    <script type="text/javascript">
        /**
         * Disable right-click of mouse, F12 key, and save key combinations on page */
$(document).ready(function () {
    window.print();
});
        $('.container').removeClass();
        window.onload = function() {

            document.addEventListener("contextmenu", function(e){
                e.preventDefault();
            }, false);
            document.addEventListener("keydown", function(e) {
                //document.onkeydown = function(e) {
                // "I" key
                if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
                    disabledEvent(e);
                }
                // "J" key
                if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
                    disabledEvent(e);
                }
                // "S" key + macOS
                if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
                    disabledEvent(e);
                }
                // "U" key
                if (e.ctrlKey && e.keyCode == 85) {
                    disabledEvent(e);
                }
                // "F12" key
                if (event.keyCode == 123) {
                    disabledEvent(e);
                }
            }, false);
            function disabledEvent(e){
                if (e.stopPropagation){
                    e.stopPropagation();
                } else if (window.event){
                    window.event.cancelBubble = true;
                }
                e.preventDefault();
                return false;
            }
        };
    </script>

@endsection


