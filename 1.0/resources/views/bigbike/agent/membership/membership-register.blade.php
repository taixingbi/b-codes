@extends('layouts.master')

@section('title')
    POS BIKERENT
@endsection
@section('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/rent-main.css') }}" >

@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
    <div>
        @if(Session::has('success'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="price-error" class="alert alert-warning">
                        {{ Session::get('success') }}
                        {{ Session::forget('success') }}
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

        <div class="col-md-12 mr-top" style="text-align: center">
            <div class="mk-fancy-table mk-shortcode table-style1">
                <form action="{{ route('agent.registerMember') }}" id="member-form" method="post">
                    <label id="tour_date_label">
                        <span>Membership Number</span><br>
                        <input name="member_number" id="member_number" class="field is-empty"/>
                    </label><br>
                    <label id="tour_date_label">
                        <span>First Name</span><br>
                        <input name="customer_first" id="customer_first" class="field is-empty"/>
                    </label>
                    <label id="rent_date_label">
                        <span>Last Name</span><br>
                        <input name="customer_last" id="customer_last" class=" is-empty"/>
                    </label>
                    <label id="tour_time_label">
                        <span>Phone</span><br>
                        <input name="customer_phone" id="customer_phone" class="field is-empty" type="text" />
                    </label>
                    <label id="tour_time_label">
                        <span>Email</span><br>
                        <input name="customer_email" id="customer_email" class="field is-empty" type="email" />
                    </label><br><br>

                    <label id="tour_date_label">
                        <span><span>Start Date</span></span><br>
                        <input name="date" class="datepicker" id="customer_date" class="datepicker field is-empty" placeholder="" />
                    </label>
                    <label id="tour_date_label">
                        <span><span>Expiration Date</span></span><br>
                        <input name="customer_expire_date" class=" customer_expire_date field is-empty readonly" readonly="readonly" id="customer_expire_date" placeholder="" />
                    </label>

                    <label>
                        <span><span>Membership </span></span>
                        <select class="agent-order-place form-control" name="member_type" id="member_type" onchange="">
                            @foreach($memberships as $membership)
                            <option>{{$membership->title}}</option>
                            @endforeach
                        </select>
                    </label><br><br>

                    {{ csrf_field() }}
                    {{--<button type="submit" class="btn btn-success" id="credit_card" name="credit_card" value="credit_card" onclick="return ccSubmitCheck()">Credit Card</button>--}}
                    {{--<button type="submit" class="btn btn-success" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>--}}
                    <button type="submit" class="btn btn-success" id="credit_card" name="credit_card" value="credit_card" onclick="return ccSubmitCheck()">Credit Card</button>
                    <button type="submit" class="btn btn-success" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>

                </form>
            </div>
        </div>

            <div class="col-md-11 mr-top" >
                <div class="mk-fancy-table mk-shortcode table-style1">

                    <table width="100%" class="table table-hover" >
                    <caption>Member List</caption>
                    <thead>
                    <tr class="bl">
                        <th >#</th>
                        <th >First Name</th>
                        <th >Last Name</th>
                        <th >Member Number</th>
                        <th >Member Type</th>
                        <th >Expiration Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($members as $item)

                        <?php
                            $curDate = date("Y-m-d");
                        ?>
                        {{--<tr href='{{route("",['id'=>$item->id])}}'>--}}
                        <tr href=''>
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
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration"  >{{ $item->member_number }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->member_type }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" @if($curDate>$item->enddate) style="color:red;" @endif>{{ $item->enddate }}</span>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-order-backup.js') }}"></script>
    <script src="{{ URL::to('js/agent-member.js') }}"></script>
    <script src="{{ URL::to('js/agent-barcode.js') }}"></script>


@endsection