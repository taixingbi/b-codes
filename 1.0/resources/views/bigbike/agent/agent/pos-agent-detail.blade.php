@extends('layouts.master')

@section('title')
    Agent Commision Today
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin-pos.css') }}" >
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">


@endsection

@section('content')

    <div class="row" style="margin: auto; text-align: center;">

        @if(Session::has('success'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="charge-message" class="alert alert-success">
                        {{ Session::get('success') }}
                        {{ Session::forget('success') }}
                    </div>
                </div>
            </div>
        @endif

        <h3>POS Agent Summary ({{ $date }})</h3><br>
        <h3>Agent: {{ $agent->fullname }}</h3>
        <h3>Commission Percentage: {{ $agent->commission }}%</h3>

        <div class="col-md-12 " >
            <div class="mk-fancy-table mk-shortcode table-style1">

                <div>
                    <table width="100%" style="margin-top: 10px;">
                        <thead>
                        <tr class="gr">
                            <th >#</th>
                            <th>Date & Time</th>
                            <th >Sum After Tax</th>
                            <th >Sum Before Tax, Baskets, Insurance</th>
                            <th >Commission Fee</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;
                            $total = 0;

                        ?>
                        @foreach($agent_rents_orders as $item)

                            <?php
                                $com_fee = floatval($item->total_price_before_tax)*floatval($agent->commission)*0.01;
                                $total += $com_fee;
                            ?>
                            <tr >
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                </td>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item->time }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >${{ $item->total_price_after_tax }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >${{ $item->total_price_before_tax }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >${{ $com_fee }}</span>
                                </td>
                            </tr>
                            <?php $row++; ?>
                        @endforeach
                        Total: $<?php echo $total; ?>
                        </tbody>
                    </table><br>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.js"></script>
    <script src="{{ URL::to('js/admin-pos.js') }}"></script>



@endsection
