@extends('layouts.master')

@section('title')
    Report
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin.css') }}" >
@endsection

@section('content')

    <div class="row">


        <h3>Agent Report</h3><br>

        <div class="col-md-6">
            <form action="{{ route('admin.agent')}}" method="post">
                <label id="admin_date_label">
                    <span>Choose by Month</span>
                    <input name="admin_date" id="admin_date" class="datepicker field is-empty"/>
                </label><br><br>

                <label id="admin_agent_label">
                    <span>Agent</span>
                    <select name="admin_agent" id="admin_agent" class=" field is-empty">
                    @foreach($agent_list as $agent)
                        <option>{{ $agent->email }}</option>
                    @endforeach
                    </select>
                </label><br><br>


                {{--<label id="admin_payment_label">--}}
                    {{--<span>Agent</span>--}}
                    {{--<select name="admin_payment" id="admin_payment" class=" field is-empty">--}}
                        {{--<option>Cash</option>--}}
                        {{--<option>Credit Card</option>--}}
                        {{--<option>All</option>--}}
                    {{--</select>--}}
                {{--</label><br><br>--}}
                {{ csrf_field() }}

                {{--<button type="submit" class="btn btn-primary" id="submit" >Submit</button>--}}
            </form>
            <button type="submit" class="btn btn-primary" id="submit" onclick="getReport(); return false;">Get Report</button>

            <br><br><br>
        </div>

        <div class="col-md-4">
            <form action="{{ route('admin.agentDetail') }}" method="post" id="pay_form">
                <label id="date_pay_label">
                    <span>Date</span>
                    <input name="date_pay" id="date_pay" class="readonly field is-empty" readonly />
                </label><br><br>

                <label id="agent_pay_label">
                    <span>Agent</span>
                    <input name="agent_pay" id="agent_pay" class="readonly field is-empty" readonly />
                </label><br><br>

                <label id="agent_cc_pay_label">
                    <span>Cash</span>
                    <input name="agent_cash_pay" id="agent_cash_pay" class="readonly field is-empty" readonly />
                </label><br><br>

                <label id="agent_cc_pay_label">
                    <span>Credit Card</span>
                    <input name="agent_cc_pay" id="agent_cc_pay" class="readonly field is-empty" readonly />
                </label><br><br>

                <label id="agent_cc_pay_label">
                    <span>Commission Fee</span>
                    <input name="agent_commission_pay" id="agent_commission_pay" class="readonly field is-empty" readonly />
                </label><br><br>

                {{ csrf_field() }}

                <button type="submit" class="btn btn-primary" id="submit">Get Month Detail</button>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/admin.js') }}"></script>

@endsection
