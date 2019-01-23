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
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div style="display:flex;">

                    <div class="text-right" ><button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
                        <button style="margin-left: 100px;" class="btn btn-success"  onclick="window.location.href='/bigbike/agent/rent/order'">Go to main page</button>
                    </div>
                    {{--<div class="text-right" style="margin-left: 36%"><button class="btn btn-success"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button> </div><br>--}}
                </div><br>

                <div id="charge-message">
                    <div class="text-center"><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                            
                        Served by: {{ $caisher_name }}<br><br>
                        {{--<div id="charge-message" class="alert alert-success">--}}
                        <div id="charge-message">

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
                                $late_fee = floatval($agent_rents_order['late_fee']);
                            } else{
                                $late_fee = 0;
                            }
                            ?>


                            Deposit Released: ${{ $deposit-$late_fee }}<br>


                            Rent Date: {{ str_replace("/","-",$agent_rents_order['date']) }}<br>
                            Rent Time: {{ $time }}<br>
                            Duration:{{ $agent_rents_order['duration'] }}<br>
                            Must return by:<br><strong>{{ $agent_rents_order['end_time'] }}</strong><br><br>
                            Order Complated At: {{ $agent_rents_order['completed_at'] }}<br>
                            Agent Email: {{ $agent_rents_order['cashier_email'] }}<br>
                            Payment Type: {{ $agent_rents_order['payment_type'] }}<br>
                            Balance due: ${{ floatval($agent_rents_order['total_price_after_tax'])-floatval($agent_rents_order['agent_price_after_tax']) }}<br>
                            Agent charged: ${{ $agent_rents_order['agent_price_after_tax'] }}<br>
                            @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br>@endif
                            Customer: <strong>{{ $agent_rents_order['customer_name'] }}</strong><br>

                            Drop off: @if($agent_rents_order['dropoff']=='1') Yes @else No @endif<br>
                            Insurance: @if($agent_rents_order['insurance']=='1') Yes @else No @endif<br><br>

                            @if( $agent_rents_order['comment'])Comment: {{ $agent_rents_order['comment'] }}@endif<br><p></p>

                            Deposit/ID @if($agent_rents_order['deposit_pay_type']){{ '('.$agent_rents_order['deposit_pay_type'].')' }}@endif : {{ $agent_rents_order['deposit'] }}<br>
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
