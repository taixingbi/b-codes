{{--@extends('layouts.master')--}}

{{--@section('styles')--}}
    {{--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">--}}
{{--@endsection--}}

{{--@section('content')--}}
    {{--<div class="row text-center">--}}
        {{--<div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>--}}

        {{--<h2>POS Month Summary ({{ $end_date }}):</h2>--}}
        {{--<h3>Location: @if(!empty($location)){{ $location }} @else All Stores @endif</h3>--}}
        {{--<h3>Total : ${{ number_format($sum,2) }}</h3>--}}
        {{--<div>--}}
            {{--<table class="table table-striped table-bordered table-hover col-md-8">--}}
                {{--<caption></caption>--}}
                {{--<tr class="gr" style="color: white">--}}
                    {{--<th >Date</th>--}}
                    {{--<th >Numbers</th>--}}
                    {{--<th >Amount</th>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Cash</th>--}}
                    {{--<td class="cell-size" >--}}
                            {{--<span class="cell-item " >{{$cash_num }} </span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                            {{--<span class="cell-item " >${{$cash_sum,2 }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Credit Card</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $cc_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($cc_sum,2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Paypal</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $paypal_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($paypal_sum,2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Coupon</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $coupon_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($coupon_sum,2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}

                {{--<tr >--}}
                    {{--<th >Total</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " ></span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($sum, 2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--</table>--}}

            {{--<table class="table table-striped table-bordered table-hover col-md-8">--}}
                {{--<caption></caption>--}}
                {{--<tr class="gr" style="color: white">--}}
                    {{--<th >Date</th>--}}
                    {{--<th >Numbers</th>--}}
                    {{--<th >Amount</th>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Insurance</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $ins_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($ins_sum,2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Basket</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $basket_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($basket_sum, 2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Dropoff</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $dropoff_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($dropoff_sum, 2)}}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Late Fee</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $latefee_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($latefee_sum, 2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
                {{--<tr >--}}
                    {{--<th >Deposit</th>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item " >{{ $deposit_num }}</span>--}}
                    {{--</td>--}}
                    {{--<td class="cell-size" >--}}
                        {{--<span class="cell-item ">${{ number_format($deposit_sum, 2) }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}
            {{--</table>--}}

        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}

{{--@section('scripts')--}}
    {{--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>--}}
    {{--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}

    {{--<script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>--}}
    {{--<script src="{{ URL::to('js/agent-report.js') }}"></script>--}}


{{--@endsection--}}


@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>POS Month Summary(missing pedicab && reservatoin) ({{ $year }}):</h2>
        {{--<h3>Location: @if(!empty($location)){{ $location }} @else All Stores @endif</h3>--}}
        <h3>Total : ${{ number_format($sum,2) }}</h3>
        @foreach($locations as $key=>$item)
            <div>
                {{--<table class="table  table-hover col-md-8">--}}
                <table class="table  table-hover col-md-8" style="width: 48.66667%!important;">

                    <caption style="font-weight: bold;">{{  $key }}</caption>
                    @php
                        $month_dic = array("Jan", "Feb", "March", "Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
                        $month_int_dic = array("01", "02", "03", "04","05","06","07","08","09","10","11","12");
                    @endphp
                    @foreach($item as $key_sub=>$item_sub)

                        @php
                            $payment_type = null;
                            if(in_array($key_sub,$month_dic)){
                                $idx = array_search($key_sub, $month_dic);
                                //$month =$month_int_dic[$idx];
                            }else{
                                $payment_type = $key_sub;
                            }
                            $url = 'bigbike/agent/pos/month-detail-breakdown/'.rawurlencode($key).'/'.$year.'/'.$month.'/'.rawurlencode($payment_type);
                        @endphp
                        <tr class='clickable-row' data-href='{{  url($url)  }}'>
                            <th >{{ $key_sub }}</th>
                            <td class="" >
                                <span class=" " >${{ number_format($item_sub,2) }}</span>
                            </td>

                        </tr>
                    @endforeach
                </table>

            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>
    <script>
        $(".clickable-row").click(function() {
            window.open($(this).data("href"), 'name');

            // window.location = $(this).data("href");
        });

    </script>


@endsection