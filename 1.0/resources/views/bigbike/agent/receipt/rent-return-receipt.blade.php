@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    {{--<link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">--}}
@endsection

@section('content')

    @if(Session::has('rent_success'))
        {{ Session::forget('rent_success') }}
        <div class="text-center col-md-12" >

            <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
            <button style="margin-left: 100px;" class="btn btn-success"  onclick="window.location.href='/bigbike/agent/rent/order'">Go to main page</button>

            {{--<div class="text-right" style="margin-left: 36%"><button class="btn btn-success"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button> </div><br>--}}
            <br>

            <div style="font-size: 18px">
                <div class="text-center " ><p><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}

                        Served by: {{ $caisher_name }}<br></p>



                    <?php
                    //                        $tmp = ;
                    $time = explode(" ",$agent_rents_order['time']);
                    $time = $time[1];

                    if($agent_rents_order['deposit']=='ID'){
                        $deposit = 0;
                    } else{
                        $deposit = floatval($agent_rents_order['deposit']);
                    }
                    if($agent_rents_order['late_fee']){
                        $late_fee = floatval($agent_rents_order['returned_total']);
                    } else{
                        $late_fee = 0;
                    }

                    $tmp = new DateTime($agent_rents_order['end_time']);
                    $test = explode(' ',$tmp->format('h:ia  Y-m-d'));
                    ?>

                    Late Hours: <strong>{{ $agent_rents_order['late_hours'] }}</strong><br>
                    Late Fee after Deposit: <strong>${{ $late_fee-$deposit }}</strong><br><br>

                    Must return by:<br><strong>{{ $test[0] }}</strong><br>
                    {{ $test[2] }}<br>
                    Cashier Email: {{ $agent_rents_order['returned_cashier'] }}<br>
                    Payment Type: {{ $agent_rents_order['payment_type'] }}<br>
                    @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br>@endif
                    Customer: <strong>{{ $agent_rents_order['customer_name'].' '.$agent_rents_order['customer_lastname'] }}</strong><br>

                    @if($agent_rents_order['agent_name'])<p>Agent: {{ $agent_rents_order['agent_name'] }}<br>@endif


                        Drop off: @if($agent_rents_order['dropoff']=='1') Yes @else No @endif<br>
                        Insurance: @if($agent_rents_order['insurance']=='1') Yes @else No @endif<br>

                        @if( $agent_rents_order['comment'])Comment: {{ $agent_rents_order['comment'] }}@endif<br><p></p>

                    Deposit/ID @if( $agent_rents_order['deposit_pay_type']){{ '('.$agent_rents_order['deposit_pay_type'].')' }}@endif: {{ $agent_rents_order['deposit'] }}<br>
                    Total: ${{ number_format(floatval($agent_rents_order['total_price_before_tax']),2) }}<br>
                    NYC Tax(8.875%): ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br>

                    Total after Tax({{ $agent_rents_order['payment_type'] }}):<strong> ${{ number_format(floatval($agent_rents_order['total_price_after_tax'])+$deposit,2) }}</strong> <br><br>



                    <?php
                    //                            echo '<img id="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39") . '" alt="barcode"  style="width: 40%;" />';
                    //                    $data = "data:image/png;base64," . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39");
                    //
                    //                    list($type, $data) = explode(';', $data);
                    //                    list(, $data)      = explode(',', $data);
                    //                    $data = base64_decode($data);

                    //                    file_put_contents('asassads.png', $data);
                    //                                            echo DNS1D::getBarcodeHTML($agent_rents_order['barcode'], "C39");

                    ?>
                    <br><br>
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
        $('.container').addClass('MyClass2');
        $('.container').removeClass('container');
        $('.row').removeClass('row');
    </script>
@endsection
