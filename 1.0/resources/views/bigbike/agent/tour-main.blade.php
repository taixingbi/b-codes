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
    @if(Session::has('tour_price_error'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('tour_price_error') }}
                    {{ Session::forget('tour_price_error') }}
                </div>
            </div>
        </div>

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
    <br><br>
    <div style="display: none;">
        <div class="col-md-9 mr-top">
            <div class="mk-fancy-table mk-shortcode table-style1">

                <span>
                  Choose Tour Type First <span style="color:red">*</span><br>
                    Click Button to Add Bike<span style="color:red"> *</span>
                </span>
                <table width="100%" style="margin-top: 10px;">
                    <thead>
                    <tr class="bl2">
                        <th >Tour Type</th>
                        <th >Adult</th>
                        <th >Child</th>
                        <th> Seat</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 0;?>
                    @foreach($agent_tour_table as $item)
                        <tr>
                            <td class="cell-size" >
                                <button class="cell-item btn btn-default btn-green tour-title " id="{{ $row }}0" value="{{ $item->title }}">{{ $item->title }}</button>
                            </td>
                            <td class="cell-size" >
                                <button class="cell-item btn btn-default btn-green " id="{{ $row }}1" value="{{ $item->adult }}" onclick="getPrice(this,'#adult');">${{ $item->adult }}</button>
                            </td>
                            <td class="cell-size" >
                                <button class="cell-item btn btn-default btn-green " id="{{ $row }}2" value="{{ $item->child }}" onclick="getPrice(this,'#child');">${{ $item->child }}</button>
                            </td>
                            <td class="cell-size" >
                                <button class="cell-item btn btn-default btn-green " id="{{ $row }}3" value="{{ $item->seat }}" onclick="getPrice(this,'#seat');">${{ $item->seat }}</button>
                            </td>
                        </tr>
                        <?php $row++; ?>
                    @endforeach
                    </tbody>
                </table><br>
            </div>
        </div>
    </div>

    @include('bigbike.agent.tour-main-order')

@endsection


@section('scripts')
@endsection