@extends('layouts.master')

@section('title')
    Agent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <div style="display:flex;">
                <div class="text-right" ><button class="btn btn-success"  onclick="window.print() ">Print Receipt</button> </div>
                <div class="text-right" style="margin-left: 30%"><button class="btn btn-success"  onclick="window.location='{{ route('agent.tourTicket') }}' ">Print Ticket</button> </div><br>
            </div>
            <div id="charge-message" class="">


                {{--<img src="data:image/png;base64,{{DNS1D::getBarcodeSVG($barcode, 'PHARMA2T')}}" alt="barcode" /><br><br>--}}
                {{--<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($barcode, "C39+") }}" alt="barcode" /><br><br>--}}

                Bike Tour: <br><br>

                Order Complated At: {{ $agent_tours_order['completed_at'] }}<br><br>
                Cashier Email: {{ $agent_tours_order['cashier_email'] }}<br><br>
                Agent: {{ $agent_tours_order['agent_name'] }}<br><br>
                Payment Type: {{ $agent_tours_order['payment_type'] }}<br><br>
                {{--Total: ${{ $agent_tours_order['total_price_before_tax'] }}<br><br>--}}
                {{--Tax: ${{ number_format(floatval($agent_tours_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
                Total after Tax: ${{ $agent_tours_order['total_price_after_tax'] }}<br><br>
                Agent charged: ${{ $agent_tours_order['agent_price_after_tax'] }}<br><br>
                Balance due: ${{ floatval($agent_tours_order['total_price_after_tax'])-floatval($agent_tours_order['agent_price_after_tax']) }}<br><br>



                Customer: {{ $agent_tours_order['customer_name'].' '.$agent_tours_order['customer_lastname'] }}<br><br>
                Email: {{ $agent_tours_order['customer_email'] }}<br><br>

                Tour Type: {{ $agent_tours_order['tour_type'] }}<br><br>
                Rent Date: {{ $agent_tours_order['date'] }}<br><br>
                Rent Time: {{ $agent_tours_order['time'] }}<br><br>

                Adult: {{ $agent_tours_order['adult'] }}<br><br>
                Children: {{ $agent_tours_order['child'] }}<br><br>
                Total: {{ $agent_tours_order['total_people'] }}<br><br>
            </div>

        </div>
    </div>

@endsection

