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

    <?php

    $dteStart = new DateTime(date('Y-m-d H:i:s',time()));
    //                                dd($dteStart);
    $dteEnd = new DateTime($agent_rents_order['end_time']);
    //                            $dteEnd = new DateTime(date('Y-m-d H:i:s'));
    //                            $dteStart = new DateTime($item->end_time);

    $days = $dteEnd->diff($dteStart)->format('%a');
    $count_down =$dteEnd->diff($dteStart)->format("%H hours:%I mins");
    $count_down = $days." days ".$count_down;


    if($dteEnd<$dteStart){
        $dteEnd2 = new DateTime($agent_rents_order['end_time']);
        $dteEnd2->add(new DateInterval('PT20M'));
//                            dd($dteEnd2);
//                            $dteStart2 = new DateTime(date("Y-m-d H:m:s", strtotime('+20 minutes')));
//                            $dteStart2 = date("Y-m-d H:m:s", strtotime('+20 minutes',time()));

//                            //                                    dd($dteStart2);
//                            $count_down =$dteEnd->diff($dteStart2)->format("%H:%I");
//                            $count_down = $days." days ".$count_down;
//                            $hours = 0;
//
//                            $hours += (intval(explode(':',explode(' ', $count_down)[2])[0])+1);
//                            if($days>'0'){
//                                $hours += 24*intval($days);
//                            }
//                            //                                dd($hours);
//                            $late_fee = $item->total_bikes*$hours*10;
        $count_down2 = 0;

        $count_down2 =$dteEnd2->diff($dteStart)->format("%H hours:%I mins");
        $count_down2 = $days." days ".$count_down2;

//                            var_dump($dteEnd);
//                            var_dump($dteStart2);
//                            var_dump($count_down);

        if($dteEnd2 < $dteStart){
            $hours = 0;

//                                    explode(' ', $count_down)[2];
            //                                dd(explode(':',explode(' ', $count_down)[2])[0]);
            $hours += (intval(explode(':',explode(' ', $count_down2)[2])[0])+1);
//                                $hours += (intval(explode(' ', $count_down)[2]))+1;
//                                dd($hours);

            if($days>'0'){
                $hours += 24*intval($days);
            }
            //                                dd($hours);
            $doubleBikes = $agent_rents_order['tandem'] + $agent_rents_order['road']+$agent_rents_order['mountain'];
            $late_fee = intval($agent_rents_order['total_bikes']+$doubleBikes)*$hours*10;
        }else{
            $late_fee = 0;
        }


    }else{
        $late_fee = 0;
    }
    ?>

    <div class="col-md-12">
        <div id="" class=" col-md-6">

            {{--{{ dd($agent_rents_order) }}--}}
            <div class="col-sm-6 col-md-6 ">
                <h4>Bike Rent Return: </h4>
                @if( !empty($user['first_name'])) Cashier: {{ $user['first_name'].' '.$user['last_name'] }}<br><br>@endif

                <?php
                $time = explode(" ",$agent_rents_order['time'])[1];
                ?>
                {{--@if( $agent_rents_order['payment_type']=='credit_card' )Order Complated At: {{ $agent_rents_order['completed_at'] }}<br><br>@endif--}}
                Payment Type:<strong> {{ $agent_rents_order['payment_type'] }}</strong><br><br>
                {{--Total: ${{ $agent_rents_order['total_price_before_tax'] }}<br><br>--}}
                {{--Tax: ${{ number_format(floatval($agent_rents_order['total_price_before_tax'])*.08875,2) }}<br><br>--}}
                Total after Tax:<strong> ${{ $agent_rents_order['total_price_after_tax'] }} </strong><br><br>
                @if($agent_rents_order['extra_service_total_after_tax']) Extra Service Fee:<strong> ${{ $agent_rents_order['extra_service_total_after_tax'] }} </strong><br><br> @endif
                @if($agent_rents_order['extra_service_total_after_tax']) Grand Total:<strong> ${{ floatval($agent_rents_order['extra_service_total_after_tax'])+floatval($agent_rents_order['total_price_after_tax']) }} </strong><br><br> @endif

                Deposit($):<strong> {{ $agent_rents_order['deposit'] }}</strong><br><br>
                @if($agent_rents_order['deposit']!='ID')Deposit Payment Type:<strong> {{ $agent_rents_order['deposit_pay_type'] }}</strong><br><br>@endif

                {{--Balance due: ${{ floatval($agent_rents_order['total_price_after_tax'])-floatval($agent_rents_order['agent_price_after_tax']) }}<br><br>--}}
                @if( $agent_rents_order['customer_type']!='No')Membership: {{ $agent_rents_order['customer_type'] }}<br><br>@endif
                Customer:<strong> {{ $agent_rents_order['customer_name'].' '.$agent_rents_order['customer_lastname'] }}</strong><br><br>
                @if( !empty($agent_rents_order['customer_email'])) Customer Email: {{ $agent_rents_order['customer_email'] }}<br><br>@endif
                @if( $agent_rents_order['customer_country'])Country/State: <span style="color:rgb(138, 126, 58)"><strong>{{ $agent_rents_order['customer_country'] }}</strong></span><br><br>@endif
                @if( !empty($agent_rents_order['customer_address_phone'])) Contact Info: <strong>{{ $agent_rents_order['customer_address_phone'] }}</strong><br><br>@endif
                @if( !empty($agent_rents_order['agent_name'])) Agent: {{ $agent_rents_order['agent_name'] }}<br><br>@endif
                <button style="margin-left: 0px;" class="btn btn-success shak2"  onclick="window.location='{{ route('agent.printReceiptFromReturn',['id'=>$agent_rents_order['id']]) }}' ">Print Receipt</button>

            </div>

            <div class="col-sm-6 col-md-6 ">

                <?php
                    if($agent_rents_order['deposit']=='ID'){
                        $deposit = 0;
                    }else{
                        $deposit = intval($agent_rents_order['deposit']);
                    }

                    $total = intval($late_fee)-$deposit;
                ?>

                @if($late_fee>0) <h4><p style="color: red">Late Fee: $ {{ $total }}</p></h4> <br><br> @endif
                {{--@if( $agent_rents_order['adult']!='0')Cashier: {{ $agent_rents_order['date'] }}<br><br>@endif--}}

                Rent Date: {{ $agent_rents_order['date'] }}<br><br>
                Rent Time: {{ $time }}<br><br>
                Duration: {{ $agent_rents_order['duration'] }}<br><br>
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


                    <form action="{{ route("agent.showEditPage") }}" method="post">
                    <input type="hidden" name="edit_id" id="edit_id" value="{{ $agent_rents_order['id'] }}">
                    {{ csrf_field() }}


                    {{--@if(($user['level']==1 || $user['level']==2 || $user['level']==3) && (Auth::user()->email!='bermudezcrystal@gmail.com') && (Auth::user()->email!='josesimen@yahoo.com'))--}}
                    @if(($user['level']==1 || $user['level']==2 || $user['level']==3) )

                        @if(empty($agent_rents_order['extra_cashier_email']))
                            <input type="password"  id="editpwd"  value="" placeholder="edit password..." />
                            <button type="submit" class="btn btn-primary" name="edit" id="editbutt"  value="edit" onclick="return passCheck()">Edit</button>
                        @else
                            <p>No more edit for this transaction. Contact Technical support if you still want to do it.</p>
                        @endif
                        @if($total<0 && $agent_rents_order['deposit_pay_type']=='Credit Card')<br>$<input style="margin-top: 20px;margin-bottom: 10px;" type="text" name="refund_amt" id="refund_amt">
                        <button type="submit" class="btn btn-primary" name="release_pp"  value="release_pp" onclick="return releasePPCheck()">Refund Paypal Deposit</button>
                        @endif
                    @endif

                    @if($total<=0)
                        <button type="submit" class="btn btn-primary" name="delete"  value="delete" >Delete</button><br>
                    @endif
                    <br>
                </form>

            </div>
        </div>
        <input type="hidden" id="bike_total" value="{{ $agent_rents_order['total_bikes'] }}">
        <input type="hidden" id="bike_insurance" value="{{ $agent_rents_order['insurance'] }}">


        <div class="col-md-5">
            <form action="{{ route('agent.finishReturn') }}" id="payment-form" method="post">
                <label class="c24hours-label">
                    <span>Deposit</span><br>
                    $<input name="deposit_bike" id="deposit_bike" class="c24hours field is-empty readonly" value="@if($agent_rents_order['deposit'] != "ID"){{ $agent_rents_order['deposit'] }}@endif"  placeholder="0" readonly/>
                </label><br>

                <label class="c24hours-label">
                    <span>Late Hours</span><br>
                    <input name="late_hours_bike" id="late_hours_bike" class="c24hours field is-empty readonly" value="@if($dteEnd<$dteStart) {{ $count_down }} @endif" @if($dteEnd<$dteStart) style="color:red;" @endif placeholder="0" readonly/>
                </label><br>
                <label class="c24hours-label">
                    <span>Late Fee</span><br>
                    $<input name="late_fee_bike" id="late_fee_bike" class="c24hours field is-empty readonly" value="{{ $late_fee }}" @if($dteEnd<$dteStart) style="color:red;" @endif placeholder="0" readonly/>
                </label><br>


                <label class="c24hours-label">
                    <span>Total</span><br>
                    $<input name="rent_total_after_tax" id="rent_total_after_tax" class="c24hours field is-empty @if($user['level']==3)readonly @endif" value="@if($total !=0 ){{ $total }} @else 0 @endif" @if($dteEnd<$dteStart && $total>0) style="color:red;" @endif placeholder="0" @if($user['level']==3) readonly @endif/>
                </label>

                @if($total>=0)
                <label class="c24hours-label">
                    <span>Total After Tax</span><br>
                    $<input name="rent_total_after_after_tax" id="rent_total_after_after_tax" class="c24hours field is-empty @if($user['level']==3)readonly @endif" value="@if($total !=0 ){{ intval($total*(1.08875)*100)/100 }} @else 0 @endif" @if($dteEnd<$dteStart && $total>0) style="color:red;" @endif placeholder="0" @if($user['level']==3) readonly @endif/>
                </label>
                @endif
                @if($total<0 && $agent_rents_order['deposit_pay_type']!='Credit Card')<button style="margin-left: 10px;" type="submit" name="release" value="release" class="btn btn-primary">Release Deposit</button>@endif
                <br>
                <div style="display: inline-block;margin-top:15px;margin-bottom: 10px"></div>

                <label id="" class=""><br>
                    <span>Rendered Cash</span><br>
                    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_rendered" id="rent_rendered" class=" is-empty" type="text" value="" placeholder="0" />
                </label>
                <label id="" ><br>
                    <span>Change</span><br>
                    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_change" id="rent_change" class="readonly is-empty" type="text" value="0" placeholder="0" readonly />
                </label>

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

                {{--@include('bigbike.agent.calculation')--}}

                <h3>Payment method</h3>
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary" name="credit_card" id="mer" value="Credit Card" onclick="return ccSubmitCheck()">Credit Card</button>
                <button type="submit" class="btn btn-primary" name="cash" value="Cash" onclick="return cashSubmitCheck()">Cash</button><br><br>
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
    <script src="{{ URL::to('js/agent-return.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.js"></script>

    <script>
//        $('#editbutt').click(function () {
//
//            var password1 = '123';
//            var password2 = '12345';
//            var ans= prompt('Do you really know manager password?');
//            if (ans!=password1 ){
//                return false;
//            }
//
//        });
        var cashier = '<?php echo Session::get('cashier') ;?>';

        function passCheck(){
            if(cashier=="Crystal Bermudez"){
                var password1 = '1289';
            }else {
                var password1 = '123';
            }

            if($('#editpwd').val()==password1){
                return true;
            }else{
                swal("Wrong Password");
                return false;
            }

        }
    </script>


@endsection
