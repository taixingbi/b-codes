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
            <h3>Cashier Summary</h3>
            
            <form action="{{ route('agent.showReport') }}" id="payment-form" method="post">
                {{--<div class="col-md-4">--}}

                    {{--<label id="start_date_label" style="margin-right:60px;">--}}
                        {{--<h4>Start Date</h4><br>--}}

                        {{--<input name="start_date" id="start_date" class="datepicker field is-empty"/>--}}
                    {{--</label>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                    <label id="end_date_label">
                        <h4>Choose a date</h4><br>

                        <input name="end_date" id="end_date" class="datepicker" type="text" />
                    </label><br>
                {{--</div>--}}
                {{ csrf_field() }}
                {{--<div class="col-md-4">--}}
                    <br><br>
                    <button type="submit" class="btn btn-primary" id="submit" >Search</button>
                {{--</div>--}}
            </form>

        </div>



    </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>
    <script src="{{ URL::to('js/agent-rent-order.js') }}"></script>
    <script src="{{ URL::to('js/agent-barcode.js') }}"></script>

    
@endsection