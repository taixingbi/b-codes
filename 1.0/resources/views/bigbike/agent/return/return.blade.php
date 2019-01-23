@extends('layouts.master')

@section('title')
    BikeRent
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
    <div  >
        <div class="col-md-12 mr-top" >
            <div class="mk-fancy-table mk-shortcode table-style1">
                <div>
                    <input type="text" id="search" placeholder="Search...">
                    <span class="glyphicon glyphicon-search" ></span>

                </div>
                <table width="100%" class="table table-hover" >
                    <span style="position: absolute;padding-top: 42px;">
                    <button type="submit" class="btn btn-primary" id="deleteRent" name="deleteRent" value="deleteRent" onclick="return deleteRent()">Delete Rent</button>
                    </span>
                    <caption>BIKE RENT</caption>
                    <thead>
                    <tr class="bl">
                        @if(Session::get('cashier')=='Di Xu')
                        <th ><input type="checkbox" id="selectAllRent"></th>
                        @else
                        <th></th>
                        @endif

                        <th >#</th>
                        <th >First Name</th>
                        <th >Last Name</th>
                        <th >Out</th>
                        <th >In</th>
                        <th >Count Down</th>
                        <th >Bike Quantity</th>
                        <th >Security Deposit/ID</th>
                        <th >Late Fee</th>
                        <th >Drop Off</th>
                        <th >Country/<br>State</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($agent_rent_table as $item)
                        <?php

                        $dteStart = new DateTime(date('Y-m-d H:i:s',time()));
                        //                                dd($dteStart);
                        $dteEnd = new DateTime($item->end_time);
                        //                            $dteEnd = new DateTime(date('Y-m-d H:i:s'));
                        //                            $dteStart = new DateTime($item->end_time);


                        $days = $dteEnd->diff($dteStart)->format('%a');
                        $count_down =$dteEnd->diff($dteStart)->format("%H hours:%I mins");
                        $count_down = $days." days ".$count_down;


                        if($dteEnd<$dteStart){
                            $dteEnd2 = new DateTime($item->end_time);
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
                                $doubleBikes = $item->tandem + $item->road+$item->mountain;
                                $late_fee = intval($item->total_bikes+$doubleBikes)*$hours*10;
                            }else{
                                $late_fee = 0;
                            }


                        }else{
                            $late_fee = 0;
                        }
                        ?>
                        <tr href='{{route("agent.showReturnDetail",['id'=>$item->id])}}'>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  >
                                    <input class="delete_rent @if($late_fee>0) disabled @endif " @if(($late_fee>0 && Session::get('level')==3) &&  (Auth::user()->email!='bermudezcrystal@ymail.com')) disabled="disabled" @endif type="checkbox" id="{{$item->id}}" value="{{$item->id}}"  onclick="event.cancelBubble = true;">
                                </span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->customer_name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->customer_lastname }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration"  >{{ $item->time }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->end_time }}</span>
                            </td>

                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" id="count_down" @if($dteEnd<$dteStart) style="color:red;" @endif>{{ $count_down }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->total_bikes }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" @if($item->deposit!='ID' ) style="color:rgb(67, 134, 68);font-weight: 700" @endif>
                                    @if($item->deposit=='ID')
                                    @else
                                        $
                                    @endif
                                    {{ $item->deposit }}
                                </span>
                            </td>

                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" @if($dteEnd<$dteStart) style="color:red;" @endif>${{ $late_fee }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" @if($item->dropoff=='1') style="color:#001bff;font-weight: bold;" @endif>@if($item->dropoff=='1') Yes @else NO @endif</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->customer_country }}</span>
                            </td>
                        </tr>
                        <?php $row++; ?>
                    @endforeach
                    </tbody>
                </table><br>

                <table width="100%" class="table table-hover" >
                    <caption>BIKE TOUR</caption>
                    <span style="position: absolute;padding-top: 42px;">
                        <button type="submit" class="btn btn-success" id="deleteTour" name="deleteTour" value="deleteTour" onclick="return deleteTour()">Delete Tour</button>
                    </span>
                    <thead>
                    <tr class="gr ">
                        {{--<th ><input type="checkbox" id="selectAll"></th>--}}
                        <th></th>
                        <th >#</th>
                        <th >First Name</th>
                        <th >Last Name</th>
                        <th >Agent</th>
                        <th >Tour Type</th>
                        <th >Out</th>
                        <th >In</th>
                        <th >Bike Quantity</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($agent_tour_table as $item)
                        <tr href='{{route("agent.showTourReturnDetail",['id'=>$item->id])}}'>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  >
                                    <input class="delete_tour" type="checkbox" id="{{$item->id}}" value="{{$item->id}}" onclick="event.cancelBubble = true;">
                                </span>
                            </td>
                            <td class="cell-size">
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->customer_name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->customer_lastname }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->agent_name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->tour_type }}</span>
                            </td>
                            <?php
                            //                                $date = str_replace("/","-",$item->date);
                            $date = explode("/",$item->date);
                            $dateStr = $date[2].'-'.$date[0].'-'.$date[1];

                            ?>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration"  >{{ $dateStr.' '.$item->time }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $dateStr.' '.$item->end_time }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->total_people }}</span>
                            </td>
                        </tr>
                        <?php $row++; ?>
                    @endforeach
                    </tbody>
                </table><br>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{--<script src="{{ URL::to('js/agent-order.js') }}"></script>--}}
    <script src="{{ URL::to('js/agent-return.js') }}"></script>
    <script src="{{ URL::to('js/agent-barcode.js') }}"></script>
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

        $('#rent_barcode').focus();



    </script>
@endsection