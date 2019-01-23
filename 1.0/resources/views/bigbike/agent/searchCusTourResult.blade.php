@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
@endsection

@section('content')
    @if($info=='else')


        <div class="text-center col-md-12" >

            <div style="font-size: 18px">
                <div class="text-center " >

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
                        @if($agent_tours_order['customer_email']) Contact Info: {{ $agent_tours_order['customer_address_phone'] }}<br>@endif
                        @if($agent_tours_order['customer_country']) ID Info: {{ $agent_tours_order['customer_country'] }}<br>@endif<br>

                    @if($agent_tours_order['payment_type']=='Credit Card') <p>Paypal Transaction #: {{ $agent_tours_order['order_id'] }} <br>@endif

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
                            NYC Tax(8.875%): ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}<br>
                            Total after Tax ({{$agent_tours_order['payment_type']}}):<strong> ${{ number_format(floatval($agent_tours_order['total_price_after_tax']),2) }}</strong> <br><br>
                    @endif


                    {{--@if(!empty($agent_rents_order['extra_service_payment_type']))--}}
                    {{--Extra Service: {{ $agent_rents_order['extra_service_payment_type'] }}<br>--}}
                    {{--Extra Total after tax: {{ $agent_rents_order['extra_service_total_after_tax'] }}<br>--}}
                    {{--@if($agent_rents_order['extra_service_payment_type']=='cash')--}}
                    {{--Paid: {{ $agent_rents_order['extra_service_rendered_cash'] }}<br>--}}
                    {{--Change: {{ floatval($agent_rents_order['extra_service_rendered_cash'])-floatval($agent_rents_order['extra_service_total_after_tax']) }}<br><br>--}}
                    {{--@endif--}}
                    {{--@endif--}}


                </div>
            </div>

        </div>
    @else
        <p>No Customer found</p>
    @endif
@endsection




@section('scripts')

@endsection


