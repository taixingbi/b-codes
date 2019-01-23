@extends('layouts.master')

@section('title')
    big bike
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
            <div class="col-sm-6 col-md-6 ">
                <h4>Bike Tour Return: </h4>
                @if( !empty($user['first_name'])) Cashier: {{ $user['first_name'].' '.$user['last_name'] }}<br><br>@endif

                {{--@if( $agent_tours_order['payment_type']=='credit_card' )Order Complated At: {{ $agent_tours_order['completed_at'] }}<br><br>@endif--}}
                Payment Type: <strong>{{ $agent_tours_order['payment_type'] }}</strong><br><br>
                {{--Total: ${{ $agent_rents_order['total_price_before_tax'] }}<br><br>--}}
                {{--Tax: ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
                Total after Tax: <strong>${{ $agent_tours_order['total_price_after_tax'] }}</strong><br><br>
                @if($agent_tours_order['extra_service_total_after_tax']) Extra Service Fee: <strong>${{ $agent_tours_order['extra_service_total_after_tax'] }}</strong><br><br>@endif
                @if($agent_tours_order['extra_service_total_after_tax']) Grand Total: <strong>${{ floatval($agent_tours_order['total_price_after_tax'])+floatval($agent_tours_order['extra_service_total_after_tax']) }}</strong><br><br>@endif

                Customer: <strong>{{ $agent_tours_order['customer_name'].' '.$agent_tours_order['customer_lastname'] }}</strong><br><br>
                @if( !empty($agent_tours_order['customer_email'])) Customer Email: {{ $agent_tours_order['customer_email'] }}<br><br>@endif
                @if($agent_tours_order['customer_country'])Country/State: <strong>{{ $agent_tours_order['customer_country'] }}</strong><br><br>@endif
                @if($agent_tours_order['customer_address_phone'])Contact Info: <strong>{{ $agent_tours_order['customer_address_phone'] }}</strong><br><br>@endif
                <button style="margin-left: 0px;" class="btn btn-success shak2"  onclick="window.location='{{ route('agent.printReceiptFromTourReturn',['id'=>$agent_tours_order['id']]) }}' ">Print Receipt</button>

            </div>

            <div class="col-sm-6 col-md-6 ">
                <br><br>
                @if( $agent_tours_order['deposit'])
                    Deposit: @if($agent_tours_order['deposit']!='ID'){{'$'}}@endif{{ $agent_tours_order['deposit'] }}<br><br>
                    Deposit Payment Type: {{ $agent_tours_order['deposit_pay_type'] }}<br><br>
                @endif
                Rent Date: {{ $agent_tours_order['date'] }}<br><br>
                Rent Time: {{ $agent_tours_order['time'] }}<br><br>
                Duration: {{ $agent_tours_order['tour_type'] }}<br><br>
                @if( $agent_tours_order['adult']!='0')Adult Bike: {{ $agent_tours_order['adult'] }}<br><br>@endif
                @if( $agent_tours_order['child']!='0')Children Bike: {{ $agent_tours_order['child'] }}<br><br>@endif
                @if( $agent_tours_order['basket']!='0')Basket: {{ $agent_tours_order['basket'] }}<br><br>@endif

                Insurance: @if($agent_tours_order['insurance']=='1') Yes @else No @endif<br><br>

                @if( !empty($agent_tours_order['extra_service_total_after_tax']))Add-on Charge: {{ $agent_tours_order['extra_service_total_after_tax'] }}<br><br>@endif
@if  ((Auth::user()->email!='bermudezcrystal@gmail.com') && (Auth::user()->email!='josesimen@yahoo.com'))
                <form action="{{ route("agent.showTourEditPage") }}" method="post">
                    <input type="hidden" name="edit_id" value="{{ $agent_tours_order['id'] }}">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary" name="delete"  value="delete" >Delete</button>

                    @if($user['level']==1 || $user['level']==2)
                        <button type="submit" class="btn btn-primary" name="edit"  value="edit" >Edit</button>
                        <input style="margin-top: 20px;margin-bottom: 10px;" type="text" name="refund_amt" id="refund_amt">
                        <button type="submit" class="btn btn-primary" name="release_pp"  value="release_pp" onclick="return releasePPCheck()">Refund Paypal Deposit</button>
                    @endif

                </form>
                @endif
            </div>
        </div>
        <input type="hidden" id="bike_total" value="{{ $agent_tours_order['total_people'] }}">
        <input type="hidden" id="bike_insurance" value="{{ $agent_tours_order['insurance'] }}">


        {{--<div class="col-md-5">--}}
        {{--<form action="{{ route('agent.finishReturn') }}" id="payment-form" method="post">--}}

        {{--<label class="c24hours-label">--}}
        {{--<span>Total</span><br>--}}
        {{--$<input name="rent_total_after_tax" id="rent_total_after_tax" class="c24hours field is-empty readonly" value=""  style="color:red;"  placeholder="0" readonly/>--}}
        {{--</label>--}}
        {{--<label id="" class=""><br>--}}
        {{--<span>Rendered Cash</span><br>--}}
        {{--$<input style="font-size:18px;font-weight: bold;color:green;" name="rent_rendered" id="rent_rendered" class=" is-empty" type="text" value="" placeholder="0" />--}}
        {{--</label>--}}
        {{--<label id="" ><br>--}}
        {{--<span>Change</span><br>--}}
        {{--$<input style="font-size:18px;font-weight: bold;color:green;" name="rent_change" id="rent_change" class="readonly is-empty" type="text" value="0" placeholder="0" readonly />--}}
        {{--</label>--}}

        {{--<label class="c24hours-label">--}}
        {{--<input type="hidden" name="id_bike" id="id_bike" class="c24hours field is-empty" value="{{$agent_tours_order['id']}}" />--}}
        {{--</label>--}}
        {{--<label id="rent_date_label" style="display: none;">--}}
        {{--<span>First Name</span><br>--}}
        {{--<input type="hidden" name="rent_customer" id="rent_customer" class=" is-empty" value="@if(!empty($agent_tours_order)){{ $agent_tours_order['customer_name'] }} @endif"/>--}}
        {{--</label>--}}
        {{--<label id="rent_date_label" style="display: none;">--}}
        {{--<span>Last Name</span><br>--}}
        {{--<input type="hidden" name="rent_customer_last" id="rent_customer_last" class=" is-empty" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_lastname'] }} @endif"/>--}}
        {{--</label>--}}

        {{--@include('bigbike.agent.calculation')--}}

        {{--<h3>Payment method</h3>--}}
        {{--{{ csrf_field() }}--}}
        {{--<button type="submit" class="btn btn-primary" name="credit_card" id="mer" value="credit_card" onclick="return ccSubmitCheck()">Credit Card</button>--}}
        {{--<button type="submit" class="btn btn-primary" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>--}}
        {{--</form>--}}
        {{--</div>--}}
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
    <script src="{{ URL::to('js/agent-return.js') }}"></script>

@endsection
