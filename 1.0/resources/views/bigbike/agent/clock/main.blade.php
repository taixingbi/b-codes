@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
@endsection


@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
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
    <div class="row">
        <div class="col-md-12 mr-top" style="text-align: center">
            <h3>Clock System</h3>

            <div class="col-md-12" >
                <form action="{{ route('agent.getClockSummary') }}" id="payment-form" method="get">
                    <label class="member_checkbox_label shak">
                        <span><span>Employee</span></span>
                        <input class="agent-order-place form-control"  name="agent_name" id="rent_agent" onchange="" />
                        {{--<option value="All">All</option>--}}
                        {{--@foreach($agents as $user)--}}
                        {{--<option value="{{$user->fullname}}">{{$user->fullname}}</option>--}}
                        {{--@endforeach--}}

                    </label><br>
                    {{--<form action="{{ route('admin.posMonthDetail')}}" method="post">--}}
                    <label id="admin_date_label">
                        <span>Choose by Day</span>
                        {{--<input type="text" name="datepickerday" id="datepickerday" class=" field is-empty" onchange="window.location='/bigbike/agent/pos/agent-detail/day/'+$('#datepickerday').val().replace(new RegExp('/', 'g'),'-')"/>--}}
                        <input type="text" name="datepickerday" id="datepickerday" class=" field is-empty" />
                        <input type="checkbox" name="day_checkbox" id="day_checkbox" class="custom-control-input">
                    </label><br><br>

                    <label id="admin_date_label">
                        <span>Choose by Week</span>
                        <input type="text" name="datepickerweek" id="datepickerweek" class=" field is-empty" />
                        <input type="checkbox" name="week_checkbox" id="week_checkbox" class="custom-control-input">
                    </label><br><br>

                    <label id="admin_date_label">
                        <span>Choose by Month</span>
                        <input name="datepickermonth" id="datepickermonth" class=" field is-empty" />
                        <input type="checkbox" name="month_checkbox" id="month_checkbox" class="custom-control-input">
                    </label><br><br>
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-primary" id="submit" onclick="return check();">Search</button>
                    {{--<button type="submit" class="btn btn-primary" id="submit" >Submit</button>--}}

                    {{--</form>--}}
                    {{--<button type="submit" class="btn btn-primary" id="submit" onclick="getReport(); return false;">Get Report</button>--}}

                    <br><br><br>
                </form>
            </div>

            <h3>Add New Employee to Clock System</h3>
            <p>Make sure to take one clear picture of the employee's face and update to the database</p>

            <div class="col-md-12" >
                <form action="{{ route('agent.clockAdd') }}" id="payment-form" method="post">
                    <label id="rent_date_label">
                        <span>First Name</span><br>
                        <input name="rent_customer" id="rent_customer" class="field is-empty" value="" placeholder="first name..."/>
                    </label>
                    <label id="rent_date_label">
                        <span>Last Name</span><br>
                        <input name="rent_customer_last" id="rent_customer_last" class="field is-empty" value="" placeholder="last name..."/>
                    </label><br>
                    <label id="rent_time_label">
                        <span>Phone</span><br>
                        <input name="rent_phone" id="rent_phone" class="field is-empty" type="text" value="" placeholder="phone number..."/>
                    </label><br>
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-primary" id="submit" >Add</button>

                </form>
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
    {{--<script src="{{ URL::to('js/agent-report.js') }}"></script>--}}
    <script src="{{ URL::to('js/agent-rent-order.js') }}"></script>
    <script src="{{ URL::to('js/agent-barcode.js') }}"></script>
    <script src="{{ URL::to('js/admin-agent-pos.js') }}"></script>

    <script>

        var agentList = [];
        @foreach($users as $agent)
            agentList.push('{{$agent->fullname}}');
        {{--agentListMap.set('{{$agent->fullname}}', '{{$agent->level}}');--}}

        @endforeach
        $( "#rent_agent" ).autocomplete({
            source: agentList
        });

    </script>

@endsection