@extends('layouts.master')

@section('title')
    big bike agent
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
    <br><br>
    @if(Session::has('rent_price_error'))
        <br>
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('rent_price_error') }}
                    {{ Session::forget('rent_price_error') }}
                </div>
            </div>
        </div><br>
    @endif

    @if(Session::has('error'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('error') }}
                    {{ Session::forget('error') }}
                </div>
            </div>
        </div>

    @endif

    <br><br><br>

    <div style="display: none;" >
        <div class="col-md-11 mr-top" >
            <div class="mk-fancy-table mk-shortcode table-style1">
                {{--<h4>BICYCLE RENTAL</h4>--}}
                <span>
                  Choose Hours First <span style="color:red">*</span><br>
                    Click Button to Add Bike<span style="color:red"> *</span>
                </span>

                <table width="100%" style="margin-top: 10px;">
                    <thead>
                    <tr class="bl">
                        <th >HOURS</th>
                        <th >Adult Bike</th>
                        <th >Child Bike</th>
                        <th >Tandem Bike</th>
                        <th >Road Bike</th>
                        <th >Mountain Bike</th>
                        <th >Basket</th>
                        <th >Trailer</th>
                        <th >Baby Seat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 0;?>
                    @foreach($agent_rent_table as $item)
                        <tr>
                            <td class="cell-size" >
                                <button class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" value="@if($item->title!='all day'){{ $item->title }}@else{{'All Day (8am-9pm)'}}@endif" onclick="">{{ $item->title }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}1" id="{{ $row }}1" name="adult" value="{{ $item->adult }}" onclick="getPrice(this,'#adult');">{{ $item->adult }}</button>
                                {{--<h5 class="cell-item">${{ $item->adult }}</h5>--}}
                                {{--<button class="cell-item" name="adult" value="{{ $item->adult }}">+</button>--}}
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}2" id="{{ $row }}2" name="child" value="{{ $item->child }}" onclick="getPrice(this,'#child');">{{ $item->child }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}3" id="{{ $row }}3" name="tandem" value="{{ $item->tandem }}" onclick="getPrice(this,'#tandem');">{{ $item->tandem }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}4" id="{{ $row }}4" name="road" value="{{ $item->road }}" onclick="getPrice(this,'#road');">{{ $item->road }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}5" id="{{ $row }}5" name="mountain" value="{{ $item->mountain }}" onclick="getPrice(this,'#mountain');">{{ $item->mountain }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}6" id="{{ $row }}6" name="basket" value="{{ $item->basket }}" onclick="getPrice(this,'#basket');">{{ $item->basket }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}7" id="{{ $row }}7" name="trailer" value="{{ $item->trailer }}" onclick="getPrice(this,'#trailer');">{{ $item->trailer }}</button>
                            </td>
                            <td class="cell-size">
                                <button class="cell-item btn btn-default {{ $row }}8" id="{{ $row }}8" name="seat" value="{{ $item->seat }}" onclick="getPrice(this,'#seat');">{{ $item->seat }}</button>
                            </td>
                        </tr>
                        <?php $row++; ?>
                    @endforeach
                    </tbody>
                </table><br>
            </div>
        </div>
    </div>
    @include('bigbike.agent.rent-main-order')


@endsection


@section('scripts')
@endsection