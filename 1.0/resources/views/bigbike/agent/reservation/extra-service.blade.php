@extends('layouts.master')

@section('title')
    big bike agent
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/rent-main.css') }}" >
@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
<div class="row">
    @if(Session::has('error'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="charge-message" class="alert alert-warning">
                    {{ Session::get('error') }}
                    {{ Session::forget('error') }}
                </div>
            </div>
        </div>
    @endif
</div>
<br><br>

<div class="col-md-12">
<div id="" class=" col-md-6">

    {{--{{ dd($agent_rents_order) }}--}}
    <h4>Bike Rent Reservation: </h4>
    <div class="col-sm-6 col-md-4 ">
            @if( $agent_rents_order['payment_type']=='credit_card' )Order Complated At: {{ $agent_rents_order['completed_at'] }}<br><br>@endif
            Payment Type: {{ $agent_rents_order['payment_type'] }}<br><br>
            {{--Total: ${{ $agent_rents_order['total_price_before_tax'] }}<br><br>--}}
            {{--Tax: ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
            Total after Tax: ${{ $agent_rents_order['total_price_after_tax'] }}<br>
            {{--Balance due: ${{ floatval($agent_rents_order['total_price_after_tax'])-floatval($agent_rents_order['agent_price_after_tax']) }}<br><br>--}}

            <?php
//                echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39") . '" alt="barcode"   />';
                $time = explode(" ",$agent_rents_order['time'])[1];
            ?>

            <br>
            @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br><br>@endif
            Customer: {{ $agent_rents_order['customer_name'] }}
            @if( !empty($agent_rents_order['customer_email'])) Customer Email: {{ $agent_rents_order['customer_email'] }}<br><br>@endif
        </div>

        <div class="col-sm-6 col-md-4 ">

            Rent Date: {{ $agent_rents_order['date'] }}<br><br>
            Rent Time: {{ $time }}<br><br>
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
        </div>
</div>
    <input type="hidden" id="bike_total" value="{{ $agent_rents_order['total_bikes'] }}">
    <input type="hidden" id="bike_insurance" value="{{ $agent_rents_order['insurance'] }}">
    <input type="hidden" id="bike_dropoff" value="{{ $agent_rents_order['dropoff'] }}">

    <div class="col-md-5">
    <form action="{{ route('agent.updateReservation') }}" id="payment-form" method="post">
        <label class="c24hours-label">
            <span>Basket</span><br>
            <input name="basket_bike" id="basket_bike" class="c24hours spinner field is-empty" value="0"  placeholder="0"/>
        </label>
        <label class="c24hours-label">
            <span>Locks</span><br>
            <input name="lock_bike" id="lock_bike" class="c24hours spinner field is-empty" value="0"  placeholder="0"/>
        </label><br>
        <div style="display: inline-block;margin-top:15px;margin-bottom: 10px">
            <span class="padding-sp" id="insurance_label">Insurance: $2 each
                <input name="insurance" id="insurance" type="checkbox">
            </span>
            <span class="padding-sp" id="drop_off_label">Drop off: $5 each
                <input name="dropoff" id="dropoff" type="checkbox">
            </span></div><br>

        <label class="c24hours-label">
            <input type="hidden" name="id_bike" id="id_bike" class="c24hours field is-empty" value="{{$agent_rents_order['id']}}" />
        </label>
        <label id="rent_date_label" style="display: none;">
            <span>First Name</span><br>
            <input type="hidden" name="rent_customer" id="rent_customer" class=" is-empty" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['customer_name'] }} @endif"/>
        </label>
        <label id="rent_date_label" style="display: none;">
            <span>Last Name</span><br>
            <input type="hidden" name="rent_customer_last" id="rent_customer_last" class=" is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['customer_lastname'] }} @endif"/>
        </label>

        @include('bigbike.agent.calculation')

        <h3>Payment method</h3>
        {{ csrf_field() }}
        <button type="submit" class="btn btn-primary" name="credit_card" id="mer" value="credit_card" onclick="return ccSubmitCheck()">Credit Card</button>
        <button type="submit" class="btn btn-primary" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>
    </form>
</div>
</div>
@endsection

@section('scripts')
    <script>
        var agentList = [];
    </script>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-order.js') }}"></script>
    {{--<script src="{{ URL::to('js/agent-rent.js') }}"></script>--}}
    <script src="{{ URL::to('js/agent-reservation.js') }}"></script>

@endsection
