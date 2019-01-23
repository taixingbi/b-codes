@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>
        <h2>Clock Summary ({{ $type.' '.$data }})</h2>
        <div class="col-md-12 " >
            <div class="mk-fancy-table mk-shortcode table-style1">

                <div>
                    <table width="100%" style="margin-top: 10px;">
                        <thead>
                        <tr class="bl">
                            <th >#</th>
                            <th >Employee</th>
                            <th >Hours</th>
                            <th >Clock In</th>
                            <th >Clock Out</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;
                        $amount = 0;
                        ?>

                        {{--{{ dd($pos_rents) }}--}}
                        @foreach($pos_rents as $item)
                            @if(strlen($item->name))
                                <tr href="{{route("agent.posDailyRent",['id'=>$item->id])}}">
                                    <td class="cell-size" >
                                        <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                    </td>

                                    <td class="cell-size" >
                                        <span class="cell-item btn duration-title "  name="duration" >{{ $item->name }}</span>
                                    </td>

                                    <td class="cell-size" >
                                        <span class="cell-item btn duration-title "  name="duration" >{{ $item->hours }}</span>
                                    </td>
                                    <td class="cell-size" >
                                        <span class="cell-item btn duration-title "  name="duration" >{{ $item->in_time }}</span>
                                    </td>
                                    <td class="cell-size" >
                                        <span class="cell-item btn duration-title "  name="duration" >{{ $item->out_time }}</span>
                                    </td>


                                    <?php
//                                    $com = 0;
//                                    if($item->location=='203W 58th Street'){
//                                        if($item->adjust_price){
//                                            $com = $item->adjust_price;
//                                        }else{
//                                            $com = floatval($item->total_price_before_tax);
//                                            if($item->insurance=='1'){
//                                                $double_bike = $item->tandem+$item->road+$item->mountain;
//                                                $com -= floatval($item->total_bikes+$double_bike)*2;
//                                            }
//                                            if($item->dropoff=='1'){
//                                                $com -= floatval($item->total_bikes)*5;
//                                            }
//                                            $com -= floatval($item->basket);
//                                        }
//                                    }elseif($item->location=='117W 58th Street'){
//                                        if($item->original_price){
//                                            $com = $item->total_price_after_tax-$item->original_price/2;
//                                        }else{
//                                            $com = $item->total_price_after_tax/2;
//                                        }
//                                    }
                                    $amount += $item->hours;
//                                    ?>

                                </tr>
                            @endif
                            <?php $row++; ?>
                        @endforeach


                        </tbody>
                    </table><br>
                    <div style="float: right;">Total: {{ number_format($amount,2) }} hrs</div><br>
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


@endsection