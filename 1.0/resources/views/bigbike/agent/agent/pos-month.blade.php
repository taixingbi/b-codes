@extends('layouts.master')

@section('title')
    Summary
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin.css') }}" >
@endsection

@section('content')

    <div class="row" style="margin: auto; text-align: center;">

        <h3>POS Month/Year Summary</h3><br>

        <div class="col-md-6" style="width: 100%;">
            <form action="{{ route('agent.posMonthDetail')}}" method="post">
                <label id="admin_date_label">
                    <h4>Location: </h4>
                    <select class="agent-order-place form-control readonly"  name="location" id="location" style="width: 100%;">
                        <option value="All">All locations</option>
                        @foreach($locations as $location)
                            <option value="{{$location->title}}">{{$location->title}}</option>
                        @endforeach
                    </select><br>

                    <span>Choose by Month</span>
                    <input type="checkbox" name="month" id="month" value="month"><br>
                    <span>Choose by Year</span>
                    <input type="checkbox" id="year" name="year" value="year"><br>

                    <input name="admin_date" id="admin_date" class="datepicker field is-empty"/><br>
                </label><br><br>
                {{ csrf_field() }}

                {{--<button type="submit" class="btn btn-primary" id="submit" >Submit</button>--}}
                <button type="submit" class="btn btn-primary" id="submit" onclick="return form_check()">Submit</button>

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
    <script src="{{ URL::to('js/admin.js') }}"></script>



@endsection
