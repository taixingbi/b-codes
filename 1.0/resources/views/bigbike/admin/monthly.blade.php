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

    <div class="row">

        <h3>Month Report</h3><br>

        <div class="col-md-6">
            <form action="{{ route('admin.monthlyReport')}}" method="post">
                <label id="admin_date_label">
                    <span>Choose by Month</span>
                    <input name="admin_date" id="admin_date" class="datepicker field is-empty"/>
                </label><br><br>
                {{ csrf_field() }}

                {{--<button type="submit" class="btn btn-primary" id="submit" >Submit</button>--}}
                <button type="submit" class="btn btn-primary" id="submit" >Get Report</button>

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
