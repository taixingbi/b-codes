@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
@endsection


@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mr-top" style="text-align: center">
            <h3>Agent Summary</h3>

            {{--<form action="{{ route('agent.showAgentReport') }}" id="payment-form" method="post">--}}
                {{--<div class="col-md-4">--}}

                {{--<label id="start_date_label" style="margin-right:60px;">--}}
                {{--<h4>Start Date</h4><br>--}}

                {{--<input name="start_date" id="start_date" class="datepicker field is-empty"/>--}}
                {{--</label>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<label id="end_date_label">--}}
                    {{--<h4>Choose a date</h4><br>--}}
                    {{--<input name="end_date" id="end_date" class="datepicker" type="text" />--}}
                {{--</label><br>--}}
                {{--{{ csrf_field() }}--}}
                {{--<br><br>--}}
                {{--<button type="submit" class="btn btn-primary" id="submit" >Search</button>--}}
                {{--</div>--}}
            {{--</form>--}}
            {{--<br>--}}


            <div class="col-md-12" >
                <form action="{{ route('agent.getPosAgentDetail') }}" id="payment-form" method="get">

                <label class="member_checkbox_label shak">
                    <span><span>Agent</span></span>
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
        @foreach($agents as $agent)
            agentList.push('{{$agent->fullname}}');
        {{--agentListMap.set('{{$agent->fullname}}', '{{$agent->level}}');--}}

        @endforeach
        $( "#rent_agent" ).autocomplete({
            source: agentList
        });

    </script>

@endsection