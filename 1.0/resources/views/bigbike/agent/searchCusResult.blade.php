@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
    {{--<link rel="stylesheet" type="text/css" href="{{ URL::to('css/esignature.css') }}" >--}}

@endsection

@section('content')

    @if($info=='else')
        <div class="text-center col-md-12" >
            <br>

            <div style="font-size: 18px">
                <div class="text-center " >
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
                    {{ $test[2] }}<br><br>
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
                    @if( $agent_rents_order['customer_address_phone'])Contact Info: {{ $agent_rents_order['customer_address_phone'] }}<br>@endif
                    @if( $agent_rents_order['customer_country'])Customer ID Info: {{ $agent_rents_order['customer_country'] }}<br>@endif

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
                        @if($agent_rents_order['extra_service_payment_type']=='credit_card') <p>Paypal Transaction #:{{ $agent_rents_order['extra_order_id'] }}

                        @endif
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
                </div>
            </div>
        </div>
    @else
        <p>No Customer found</p>
    @endif
@endsection

