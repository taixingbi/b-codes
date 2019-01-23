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
        <h2>Month Details</h2>
        <div class="text-center">
            <h4>{{ $date }}</h4>
            <h4>Credit Card</h4><br></div>
        <div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Agent Email</th>
                    <th>Total Price</th>
                    <th>BigBike Revenue</th>
                    <th>Commission Fee</th>
                </tr>
                </thead>
                <tbody>
                <?php $idx = 1; ?>

                @foreach($cc_arr as $key => $item)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $key }}</td>
                        <td>${{ $item }}</td>
                        <td>${{ number_format((float)$item-0.3*(float)$item,2) }}</td>
                        <td>${{ number_format(0.3*(float)$item,2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="text-center">Cash</h3>
        <div >
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Agent Email</th>
                    <th>Total Price</th>

                </tr>
                </thead>
                <tbody>
                <?php $idx = 1; ?>

                @foreach($cash_arr as $key => $item)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $key }}</td>
                        <td>${{ $item }}</td>
                    </tr>
                @endforeach
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