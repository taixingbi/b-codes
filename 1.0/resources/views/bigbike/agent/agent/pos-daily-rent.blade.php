@extends('layouts.master')

@section('title')
    Bikerent Details
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <div style="display:flex;">
                <div class="text-right" ><button class="btn btn-success"  onclick="window.print() ">Print Receipt</button> </div>
                {{--<div class="text-right" style="margin-left: 36%"><button class="btn btn-success"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button> </div><br>--}}
            </div><br>
            <div id="charge-message" class="">

                {{--{{ dd($agent_rents_order) }}--}}
                Bike Rent: <br><br>

                @if($agent_rents_order['completed_at']) Order Complated At: {{ $agent_rents_order['completed_at'] }}<br><br>@endif
                Cashier Email: {{ $agent_rents_order['cashier_email'] }}<br><br>
                Payment Type: {{ $agent_rents_order['payment_type'] }}<br><br>
                Agent: {{ $agent_rents_order['agent_name'] }}<br><br>

                {{--Total: ${{ $agent_rents_order['total_price_before_tax'] }}<br><br>--}}
                {{--Tax: ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
                Total after Tax: ${{ $agent_rents_order['total_price_after_tax'] }}<br><br>

                <br><br>
                @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br><br>@endif
                Customer: {{ $agent_rents_order['customer_name'].' '.$agent_rents_order['customer_lastname'] }}<br><br>
                @if( $agent_rents_order['customer_email'])Customer Email: {{ $agent_rents_order['customer_email'] }}<br><br>@endif

                Rent Date: {{ $agent_rents_order['date'] }}<br><br>
                Rent Time: {{ $agent_rents_order['time'] }}<br><br>
                Duration:{{ $agent_rents_order['duration'] }}<br><br>

                @if( $agent_rents_order['adult']!='0')Adult Bike: {{ $agent_rents_order['adult'] }}<br><br>@endif
                @if( $agent_rents_order['child']!='0')Children Bike: {{ $agent_rents_order['child'] }}<br><br>@endif
                @if( $agent_rents_order['tandem']!='0')Tandem Bike: {{ $agent_rents_order['tandem'] }}<br><br>@endif
                @if( $agent_rents_order['road']!='0')Road Bike: {{ $agent_rents_order['road'] }}<br><br>@endif
                @if( $agent_rents_order['mountain']!='0')Mountain Bike: {{ $agent_rents_order['mountain'] }}<br><br>@endif
                @if( $agent_rents_order['trailer']!='0')Trailer: {{ $agent_rents_order['trailer'] }}<br><br>@endif
                @if( $agent_rents_order['seat']!='0')Baby Seat: {{ $agent_rents_order['seat'] }}<br><br>@endif
                @if( $agent_rents_order['basket']!='0')Basket: {{ $agent_rents_order['basket'] }}<br><br>@endif

                Drop off: @if($agent_rents_order['dropoff']=='1') Yes @else No @endif<br><br>
                Insurance: @if($agent_rents_order['insurance']=='1') Yes @else No @endif<br><br>


                @if(!empty($agent_rents_order['extra_service_payment_type']))
                    Extra Service: {{ $agent_rents_order['extra_service_payment_type'] }}<br><br>
                    Extra Total after tax: {{ $agent_rents_order['extra_service_total_after_tax'] }}<br><br>
                    @if($agent_rents_order['extra_service_payment_type']=='cash')
                        Paid: {{ $agent_rents_order['extra_service_rendered_cash'] }}<br><br>
                        Change: {{ floatval($agent_rents_order['extra_service_rendered_cash'])-floatval($agent_rents_order['extra_service_total_after_tax']) }}<br><br>
                    @endif
                @endif

            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection

