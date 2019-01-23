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
    <div>
        <div class="col-md-11 mr-top" >
            <div class="mk-fancy-table mk-shortcode table-style1">
                <div style="width:350px">

                    <input type="text" id="search" placeholder="Search...">
                    <span class="glyphicon glyphicon-search" ></span>


                </div>
                <table width="100%" style="margin-top: 10px;">
                    <caption>BIKE RENT</caption>
                    <thead>
                    <tr class="bl">
                        <th >#</th>
                        <th >Date</th>
                        <th >Hours</th>
                        <th >First Name</th>
                        <th >Last Name</th>
                        <th >Bike Quantity</th>
                        <th >Amount</th>
                        <th >Paypal ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($agent_rent_table as $item)
                        <tr href='{{route("agent.reserveShowEditPage",['id'=>$item->id])}}'>
                        {{--<tr href='{{route("agent.showReservationDetail",['id'=>$item->id])}}'>--}}
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->date }}" >{{ $item->date }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->duration }}" >{{ $item->duration }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->customer_name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->customer_lastname }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->total_bikes }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >${{ $item->total_price_after_tax }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->order_id }}</span>
                            </td>
                        </tr>
                        <?php $row++; ?>
                    @endforeach
                    </tbody>
                </table><br>

                {{--bike tour--}}
                <table width="100%" style="margin-top: 10px;">
                    <caption>BIKE TOUR</caption>
                    <thead>
                    <tr class="gr">
                        <th >#</th>
                        <th >Date</th>
                        <th >Time</th>
                        <th >Location</th>
                        <th >Tour Type</th>
                        <th >First Name</th>
                        <th >Last Name</th>
                        <th >Bike Quantity</th>
                        <th >Amount</th>
                        <th >Paypal ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($agent_tour_table as $item)
                        <tr href='{{route("agent.reserveTourShowEditPage",['id'=>$item->id])}}'>
                            {{--<tr href='{{route("agent.showReservationDetail",['id'=>$item->id])}}'>--}}
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->date }}" >{{ $item->date }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->time }}" >{{ $item->time }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->tour_place }}" >{{ $item->tour_place }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->tour_type }}" >{{ $item->tour_type }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->customer_name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->customer_lastname }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->total_people }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >${{ $item->total_price_after_tax }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="{{ $item->total_price_after_tax }}" >{{ $item->order_id }}</span>
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
    <script src="{{ URL::to('js/agent-reservation.js') }}"></script>
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
    </script>

@endsection

