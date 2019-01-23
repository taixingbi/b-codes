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
        <div class="text-center">
            <h4>{{ $date }}</h4>
        <div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>Service Type</th>
                    <th>Total Count Number</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $cnt = 0;
                @endphp

                @foreach($cntMap as $key => $item)
                    @php
                        $cnt += $item
                    @endphp
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $item }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td>Total</td>
                    <td>{{ $cnt }}</td>
                </tr>
                </tbody>
            </table>
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