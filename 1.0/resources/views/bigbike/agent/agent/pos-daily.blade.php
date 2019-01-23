@extends('layouts.master')

@section('title')
    Summary
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin-pos.css') }}" >

@endsection

@section('content')

    <div class="row" style="margin: auto; text-align: center;">

        {{--<h3>POS Cashier Search</h3><br>--}}

        <div class="col-md-6" style="width: 80%;">
            <form action="{{ route('agent.getPosDailyReportDetail')}}" method="post">
                <label id="admin_date_label">
                    <span>Choose Day</span><br>
                    <input type="text" name="datepicker" class="datepicker field is-empty" />
                </label><br>

                <label id="admin_date_label">
                    <span>Receipt</span><br>
                    <input type="text" name="receipt" id="receipt" class=" field is-empty" />
                </label><br>

                <label id="admin_date_label">
                    <span>Location</span>
                    <select class="agent-order-place form-control "  name="location" id="location" style="width: 100%;">
                        <option value="">All Locations</option>
                        @foreach($locations as $location)
                            <option value="{{$location->title}}">{{$location->title}}</option>
                        @endforeach
                    </select><br>
                </label><br>


                <label id="admin_date_label">
                    <span>Agent</span>
                    <select class="agent-order-place form-control "  name="agent" id="agent" style="width: 100%;">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{$agent->fullname}}">{{$agent->fullname}}</option>
                        @endforeach
                    </select><br>
                </label><br>

                <label id="admin_date_label">
                    <span>Cashier</span>
                    <select class="agent-order-place form-control "  name="cashier" id="cashier" style="width: 100%;">
                        <option value="">All Cashiers</option>
                        @foreach($users as $user)
                            <option value="{{$user->email}}">{{$user->first_name.' '.$user->last_name}}</option>
                        @endforeach
                    </select><br>
                </label><br>
                <label id="admin_date_label">
                    <span>Choose Payment Type</span>
                    <select class="agent-order-place form-control "  name="payment_type" id="payment_type" style="width: 100%;">
                        <option value="">All Payments</option>
                        <option value="Cash">Cash</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="paypal">Paypal</option>
                        <option value="coupon">Coupon</option>
                    </select><br>
                </label><br>
                {{ csrf_field() }}
                {{--<button type="submit" class="btn btn-primary" id="submit" >Submit</button>--}}
                <button type="submit" class="btn btn-primary" id="submit" >Submit</button>
            </form>
            {{--<button type="submit" class="btn btn-primary" id="submit" onclick="getReport(); return false;">Get Report</button>--}}

            <br><br><br>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/admin-pos.js') }}"></script>



@endsection
