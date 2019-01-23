@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
@endsection

@section('content')

    @if(Session::has('rent_success'))
        {{ Session::forget('rent_success') }}
        <div class="text-center col-md-12" >

            <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
            <button style="margin-left: 100px;" class="btn btn-success shak"  onclick="window.location.href='/bigbike/agent/rent/order'">Go to main page</button>

            <br>

            <div style="font-size: 18px">
                <div class="text-center " ><p><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                          

                        Served by: {{ $caisher_name }}<br></p>
                    {{--<div id="charge-message" class="alert alert-success">--}}
                    @if( $agent_rents_order['sequantial'])Receipt#: {{ $agent_rents_order['sequantial'] }}<br>@endif

                    <p>Date & Time: {{ str_replace("/","-",$agent_rents_order['created_at']) }}<br>

                        @if($agent_rents_order['customer_name']!='')  Customer: <strong>{{ $agent_rents_order['customer_name'].' '.$agent_rents_order['customer_lastname'] }}</strong><br>@endif
                    @if($agent_rents_order['payment_type']=='Credit Card') <p>Paypal Transaction #: {{ $agent_rents_order['order_id'] }} <br>@endif


                    @foreach($products as $product)
                        <li class="">
                            <strong>Product: {{ $product->name }}</strong><br>
                            <span class="">Size: {{ $product->size }}</span><br>
                            <span class="">Quantity: {{ $product->quantity }}</span><br>
                            <span class="">Price: ${{ $product->price }}</span><br><br>
                        </li>
                    @endforeach
                    <br>

                    @if($agent_rents_order['adjust_price'])Adjusted Price: <strong>${{ $agent_rents_order['adjust_price'] }}</strong><br>@endif

                    @if( $agent_rents_order['comment'])Comment: {{ $agent_rents_order['comment'] }}@endif<br><p></p>



                    Total: ${{ number_format(floatval($agent_rents_order['total_price_before_tax']),2) }}<br>
                    NYC Tax(8.875%): ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br>
                    Total after Tax({{ $agent_rents_order['payment_type'] }}):<strong> ${{ number_format(floatval($agent_rents_order['total_price_after_tax']),2) }}</strong><br>


                    <div style="margin: 0 auto;left: 0;right: 0; width:300px;position:absolute">
                        <br>
                        <?php
                        echo '<img id="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39") . '" alt="barcode"  style="width: 60%;" />';

                        ?>
                    </div>
                    <br><br><br><br>                        <br><br>

                </div>
            </div>



            <div style="font-size:18px" class=""><p>
                    <u>Refund Policy:</u>
                    No cash refund on bike rentals or tours. Bike rentals or tours may be changed for use at a future date. For in store purchases of clothing or bicycle accessories, refunds or exchanges can be made within 15 days, provided the returned items are unused. No refund or exchange for used items. If you purchase used bike no refund or exchange.

                </p>


                {{--<div><div style="text-align: left">X</div><div style="text-align: right">Signature</div><hr style="border: 1px dashed grey;"></div>--}}
            </div>
            <br>

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

            window.onload = function() { window.print();

            }
        }

    </script>

    

@endsection
