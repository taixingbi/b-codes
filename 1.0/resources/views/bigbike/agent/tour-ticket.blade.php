@extends('layouts.master')

@section('title')
    Agent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
    <style>
        footer{
            display: none;
        }
    </style>
@endsection

@section('content')

    @if(Session::has('tour_success'))
        {{ Session::forget('tour_success') }}
        <div class="container">
            <div class="row">
                <span style="position:absolute; zoom: 140%"><img src="{{ URL::to('images/central.jpg')}}" /></span>

                <div class="ticket-rec" style="position: absolute;" >

                    <div style="display: inline">
                        <span style="float:right"><img src="{{ URL::to('images/favicon.png')}}" height="90px" width="90px" ></span>

                    </div>
                    <span style="font-size:18px" class="font700">Bike Tour Ticket</span><br><br>

                    <div>
                        <div style="max-width:650px;" class="table-ticket" style="z-index:15;">
                            <div class="col-md-6 col-sm-6">
                                <div class="font700">Payment :</div>
                                {{--<div>Order Complated At: {{ $agent_tours_order['completed_at'] }}</div>--}}
                                {{--<div>Agent Email: {{ $agent_tours_order['cashier_email'] }}</div>--}}
                                @if($agent_tours_order['payment_type']=='credit_card') <p>Paypal Transaction #: {{ $agent_tours_order['order_id'] }} <br>@endif
                                <div>Payment Type: {{ $agent_tours_order['payment_type'] }}</div>
                                {{--<div>Total: ${{ $agent_tours_order['total_price_before_tax'] }}</div>--}}
                                {{--<div>Tax: ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}</div>--}}
                                {{--<div>Total after Tax: ${{ $agent_tours_order['total_price_after_tax'] }} </div>--}}
                                {{--<div>Agent charged: ${{ $agent_tours_order['agent_price_after_tax'] }}</div>--}}
                                {{--<div>Balance due: ${{ floatval($agent_tours_order['total_price_after_tax'])-floatval($agent_tours_order['agent_price_after_tax']) }}<br><br>--}}
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-6">
                                <div class="font700">Tour Info :</div>
                                <div>Customer: {{ $agent_tours_order['customer_name'] }}  </div>
                                <div>Email: {{ $agent_tours_order['customer_email'] }}  </div>
                                <div>Tour Date: {{ $agent_tours_order['date'] }}</div>
                                <div>Tour Time: {{ $agent_tours_order['time'] }}</div>
                                <div>Tour Type: {{ $agent_tours_order['tour_type'] }}</div>
                                <div>Adult: {{ $agent_tours_order['adult'] }}</div>
                                <div>Children: {{ $agent_tours_order['child'] }}</div>
                                @if( $agent_tours_order['comment'])Comment: {{ $agent_tours_order['comment'] }}<br>@endif
                                Insurance: @if($agent_tours_order['insurance']=='1') Yes @else No @endif<br>

                                Total: ${{ number_format(floatval($agent_tours_order['total_price_before_tax']),2) }}<br>
                                NYC Tax(8.875%): ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}<br>
                                Total after Tax ({{$agent_tours_order['payment_type']}}):<strong> ${{ number_format(floatval($agent_tours_order['total_price_after_tax']),2) }}</strong> <br><br>



                            </div>
                        </div>
                        {{--<table style="max-width:650px;" class="table-ticket" style="z-index:15;">--}}
                        {{--<tr>--}}
                        {{--<th>Payment :</th>--}}
                        {{--<th>Date & time : </th>--}}
                        {{--<th>Order :</th>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Order Complated At: {{ $agent_tours_order['completed_at'] }}</td>--}}
                        {{--<td>Customer: {{ $agent_tours_order['customer_name'] }}  </td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Agent Email: {{ $agent_tours_order['agent_email'] }}</td>--}}
                        {{--<td>Rent Date: {{ $agent_tours_order['date'] }}</td>--}}
                        {{--<td>Email: {{ $agent_tours_order['customer_email'] }}</td>--}}
                        {{--@if ( $agent_tours_order['child']!='0')<td>Children Bike: {{ $agent_tours_order['child'] }}</td>@endif--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Payment Type: {{ $agent_tours_order['payment_type'] }}</td>--}}
                        {{--<td>Tour Type: {{ $agent_tours_order['tour_type'] }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Total price: {{ $agent_tours_order['total_price_after_tax'] }} </td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Rent Date: {{ $agent_tours_order['date'] }}</td>--}}
                        {{--<td>Rent Time: {{ $agent_tours_order['time'] }}</td>--}}

                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td>Agent charged: {{ $agent_tours_order['agent_price_after_tax'] }}</td>--}}
                        {{--<td> Adult: {{ $agent_tours_order['adult'] }}</td>--}}
                        {{--</tr>--}}



                        {{--<tr>--}}
                        {{--<td></td>--}}
                        {{--</tr>--}}
                        {{--</table>--}}

                    </div>


                    <div class="barcode">
                        <?php
                        //                        echo DNS1D::getBarcodeHTML($agent_rents_order['barcode'], "C39");

                        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_tours_order['barcode'], "C39") . '" alt="barcode"   />';

                        ?>
                    </div>

                </div>
            </div>
        </div>
    @else
        <div>No Transaction!</div>
    @endif
@endsection
@section('scripts')
    <script>
        window.onload = function() { window.print(); }
    </script>

@endsection