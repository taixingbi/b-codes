<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://tickets.bikerent.nyc/css/agent-order.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        body{ background-color: #f5f8fa;}
        .logo{
            padding-top: 15px;
            padding-left: 5px;
            display: inline-block;
            text-decoration: none;
            min-width: 100px;

        }
    </style>
</head>
<div class="header">
    <a href="http://tickets.bikerent.nyc/bigbike/agent/main"><img src="http://tickets.bikerent.nyc/images/favicon-small.png" alt="" style="float: left;"></a>
    <a class="logo" href="http://tickets.bikerent.nyc/bigbike/agent/main"><font color="#61982d">BIKE</font><font color="#676767">RENT</font><font color="green">.</font><font color="black">NYC</font></a>


</div>
<body>

<div class="logo">
    <img src="{{ URL::to('images/favicon.png') }}" width="200px" height="200px"></div>

<h3>Welcome to {!! $name !!} website!</h3><br>
<h3>Congratulations, your order has been complete!</h3>
<div>


    {{--<img src="data:image/png;base64,{{DNS1D::getBarcodeSVG($barcode, 'PHARMA2T')}}" alt="barcode" /><br><br>--}}
    {{--<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode, "C39+") }}" alt="barcode" /><br><br>--}}

    Bike Tour<br><br>

    Order Complated At: {{ $completed_at }}<br><br>
    Agent Email: {{ $agent_email }}<br><br>
    Customer Email: {{ $customer_email }}<br><br>
    Payment Type: {{ $payment_type }}<br><br>
    {{--Total: ${{ $agent_tours_order['total_price_before_tax'] }}<br><br>--}}
    {{--Tax: ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
    Total after Tax: ${{ $total_price_after_tax }}<br><br>
    Agent charged: ${{ $agent_price_after_tax }}<br><br>
    Balance due: ${{ floatval($total_price_after_tax)-floatval($agent_price_after_tax) }}<br><br>

    Barcode:
    <img src={{ URL::to('images/barcode/tour/'.$barcode.'.png') }} />

<?php

//    echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($barcode, "C39") . '" alt="barcode"   />';

    //                    echo DNS1D::getBarcodeHTML($agent_tours_order['barcode'], "C39");
    //                    echo DNS1D::getBarcodeSVG($agent_tours_order['barcode'], "PHARMA2T");

    ?>
    <br><br>

    Customer: {{ $customer_name }}<br><br>
    Email: {{ $customer_email }}<br><br>

    Tour Type: {{ $tour_type }}<br><br>
    Rent Date: {{ $date }}<br><br>
    Rent Time: {{ $time }}<br><br>

    Adult: {{ $adult }}<br><br>
    Children: {{ $child }}<br><br>
    Total: {{ $total_people }}<br><br>
</div>




</body>
</html>
