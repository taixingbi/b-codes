@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>
        <h2>POS Agent Report ({{ $type.' '.$data }})</h2>
        <div class="col-md-12 " >
            <div class="mk-fancy-table mk-shortcode table-style1">

                <div>
                    <table width="100%" style="margin-top: 10px;">
                        <thead>
                        <tr class="bl">
                            <th >#</th>
                            <th >Paid</th>
                            <th >Customer  Name</th>
                            <th >Agent</th>
                            <th >Locations</th>
                            <th >Out</th>
                            <th >In</th>
                            <th >Cashier</th>
                            <th >Bike Quantity</th>
                            @if(Session::get("location")!="117W 58th Street")
                            <th >Total Amount</th>
                            @endif
                            <th >Total before Insurance, Baskets, Dropoff and etc</th>
                            <th >
                                <input type="checkbox" class="cell-item btn duration-title checkbox-all"  name="duration" />
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;
                            $amount = 0;
                            $commission = 0;
                            $balance = 0;
                        ?>

                        {{--{{ dd($pos_rents) }}--}}
                        @foreach($pos_rents as $item)
                            @if(strlen($item->agent_name))
                            <tr href="{{route("agent.posDailyRent",['id'=>$item->id])}}">
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" @if($item->agent_name && $item->agent_paid!='1') style="color: red" @endif> @if($item->agent_name!=null && $item->agent_paid=='1') Paid @elseif(!empty($item->agent_name))  Unpaid @else @endif </span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->customer_name.' '.$item->customer_lastname }}</span>
                                </td>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >{{ $item->agent_name }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->location }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->time }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->end_time }}</span>
                                </td>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" id="count_down" >{{ $cashierMap[$item->cashier_email]}}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->total_bikes }}</span>
                                </td>
                                @if(Session::get("location")!="117W 58th Street")
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >${{ $item->total_price_after_tax }}</span>
                                </td>
                                @endif
                                <?php
                                    $com = 0;
                                    if($item->location=='203W 58th Street'){
                                        if($item->adjust_price){
                                            $com = $item->adjust_price;
                                        }else{
                                            $com = floatval($item->total_price_before_tax);
                                            if($item->insurance=='1'){
                                                $double_bike = $item->tandem+$item->road+$item->mountain;
                                                $com -= floatval($item->total_bikes+$double_bike)*2;
                                            }
                                            if($item->dropoff=='1'){
                                                $com -= floatval($item->total_bikes)*5;
                                            }
                                            $com -= floatval($item->basket);
                                        }
                                    }elseif($item->location=='117W 58th Street'){
//                                        if($item->original_price){
//                                            $com = $item->total_price_after_tax-$item->original_price/2;
//                                        }else{
//                                            $com = $item->total_price_after_tax/2;
//                                        }
                                        if($item->adjust_price){
                                            $com = $item->adjust_price;
                                        }else{
                                            $com = floatval($item->total_price_before_tax);
                                            if($item->insurance=='1'){
                                                $double_bike = $item->tandem+$item->road+$item->mountain;
                                                $com -= floatval($item->total_bikes+$double_bike)*2;
                                            }
                                            if($item->dropoff=='1'){
                                                $com -= floatval($item->total_bikes)*5;
                                            }
                                            $com -= floatval($item->basket);
                                        }
                                    }
                                    $commission += $com*$commision_rate;
                                    $amount += $com;
                                    if($item->agent_paid==0){
                                        $balance += $com*$commision_rate;
                                    }
                                ?>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title"  name="duration" >@if($item->agent_name)${{ $com}}@endif</span>
                                </td>

                                <td class="cell-size" >
                                    <input type="checkbox" class="cell-item btn duration-title checkbox-single" id="{{ $item->id }}" name="duration" />
                                </td>

                            </tr>
                            @endif
                            <?php $row++; ?>
                        @endforeach

                        @foreach($pos_tours as $item)
                            <tr href="{{route("agent.posDailyTour",['id'=>$item->id])}}">
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" @if($item->agent_name && $item->agent_paid!='1') style="color: red" @endif>@if($item->agent_name!=null && $item->agent_paid=='1') Paid @elseif(!empty($item->agent_name)) Unpaid @else @endif</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->customer_name.' '.$item->customer_lastname }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >{{ $item->agent_name }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->location }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->real_time }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->end_time }}</span>
                                </td>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" id="count_down" >{{ $item->cashier_email }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->total_people }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >${{ $item->total_price_after_tax }}</span>
                                </td>

                                <?php
                                    $com = 0;
                                    if($item->location=='203W 58th Street'){
                                        if($item->adjust_price){
                                            $com = $item->adjust_price;
                                        }else{
                                            $com = floatval($item->total_price_before_tax);
                                            if($item->insurance=='1'){
                                                $com -= floatval($item->total_bikes)*2;
                                            }
                                            $com -= floatval($item->seat)*10;
                                            $com -= floatval($item->basket);
                                        }

                                    }elseif($item->location=='117W 58th Street'){
                                        if($item->original_price){
                                            $com = $item->total_price_after_tax-$item->original_price/2;
                                        }else{
                                            $com = $item->total_price_after_tax/2;
                                        }
                                    }
                                    $commission += $com*$commision_rate;

                                    $amount += $com;
                                    if($item->agent_paid==0){
                                        $balance += $com*$commision_rate;
                                    }
                                ?>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title"  name="duration" >@if($item->agent_name)${{ number_format($com,2) }} @endif</span>
                                </td>
                                <td class="cell-size" >
                                    <input type="checkbox" class="cell-item btn duration-title checkbox-single" tour_id="{{ $item->id }}" name="duration" />
                                </td>

                            </tr>
                            <?php $row++; ?>
                        @endforeach

                        </tbody>
                    </table><br>
                    <div id="prepaid" style="float: right;">Total: ${{ number_format($amount,2) }}</div><br>
                    <div id="topay" style="float: right;">To pay: ${{number_format($commission,2) }} </div><br>
                    <div id="" style="float: right;">Balance: ${{$balance }} </div><br><br>

                    <button class="btn btn-success" id="set-paid" style="float: right;">Set Paid</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.js"></script>

    <script src="{{ URL::to('js/agent-report.js') }}"></script>
    <script>
        {{--var commission_rate = "<?php echo $commision_rate; ?>"--}}
        {{--$(function() {--}}
{{--//            var $b = $('#prepaid').text().replace(/[^0-9]/gi, '.');--}}
            {{--var price = $('#prepaid').text();--}}
            {{--var parsedPrice = parseFloat(price.replace(/([^0-9\.])/g, ''));--}}
            {{--var $c = (parsedPrice * commission_rate).toFixed(2);--}}
            {{--$('#topay').text('To pay: $'+$c);--}}
        {{--});--}}
    </script>


@endsection