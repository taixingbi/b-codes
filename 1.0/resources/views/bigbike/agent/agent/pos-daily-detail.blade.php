@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">

@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>POS Daily Report ({{ $date }})</h2>
        <div class="col-md-12 " >
            <div>
                <input type="text" id="search" placeholder="Search by Agent Name">
                <span class="glyphicon glyphicon-search" ></span>

            </div>
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
                            <th >Total Amount</th>
                            <th >Commission</th>
                            <th >
                                <input type="checkbox" class="cell-item btn duration-title checkbox-all"  name="duration" />
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;?>
                        @foreach($pos_rents as $item)

                            <tr href="{{route("agent.posDailyRent",['id'=>$item->id])}}">
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" @if($item->agent_name && $item->agent_paid!='1') style="color: red" @endif> @if($item->agent_name!=null && $item->agent_paid=='1') Paid @elseif(!empty($item->agent_name)) Unpaid @else @endif </span>
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
                                    <span class="cell-item btn duration-title "  name="duration" id="count_down" >@if($item->cashier_email){{ $cashierMap[$item->cashier_email] }} @endif</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->total_bikes }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >${{ $item->total_price_after_tax }}</span>
                                </td>

                                <?php
                                $com = 0;
//                                if($item->location=='203 W58th Street New York'){
//                                    if($item->agent_level==1){
//                                        $com = $item->total_price_after_tax*0.1;
//                                    }elseif($item->agent_level==2){
//                                        $com = $item->total_price_after_tax*0.3;
//                                    }elseif($item->agent_level==3){
//                                        $com = $item->total_price_after_tax*0.5;
//                                    }
//
//                                }elseif($item->location=='117 W58th Street, New York'){
//                                    if($item->original_price){
//                                        $com = $item->total_price_after_tax-$item->original_price/2;
//                                    }else{
//                                        $com = $item->total_price_after_tax/2;
//                                    }
//                                }


                                ?>

                                <td class="cell-size" >
                                <span class="cell-item btn duration-title"  name="duration" >@if($item->agent_name)${{ number_format($com,2) }} @endif</span>
                                </td>

                                <td class="cell-size" >
                                    <input type="checkbox" class="cell-item btn duration-title checkbox-single" id="{{ $item->id }}" name="duration" />
                                </td>

                            </tr>
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
                                    <span class="cell-item btn duration-title "  name="duration" id="count_down" >@if($item->cashier_email) {{ $cashierMap[$item->cashier_email] }} @endif</span>
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
                                    if($item->agent_level==1){
                                        $com = $item->total_price_after_tax*0.1;
                                    }elseif($item->agent_level==2){
                                        $com = $item->total_price_after_tax*0.3;
                                    }elseif($item->agent_level==3){
                                        $com = $item->total_price_after_tax*0.5;
                                    }

                                }elseif($item->location=='117W 58th Street'){
                                    if($item->original_price){
                                        $com = $item->total_price_after_tax-$item->original_price/2;
                                    }else{
                                        $com = $item->total_price_after_tax/2;
                                    }
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
        $("#search").keyup(function(){
            _this = this;
            // Show only matching TR, hide rest of them
            $.each($("table tbody tr"), function() {
                if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                    $(this).hide();
                else
                    $(this).show();
            });
        });

    </script>


@endsection