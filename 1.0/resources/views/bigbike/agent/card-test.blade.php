@extends('layouts.master')


@section('title')
    Get Card Swipe Test Page
@endsection

@section('styles')

@endsection

@section('content')


<h3>Swipe when you're ready!</h3>
<form action="#">
    <div class="control-group">
        <label for="cc_num">Credit Card Number</label>
        <div class="controls">
            <input id="cc_number" name="cc_number" type="text">
        </div>
    </div>
    <div class="control-group">
        <label for="cc_exp_month">Exp Month</label>
        <div class="controls">
            <input id="cc_exp_month" name="cc_exp_month" type="text">
        </div>
    </div>
    <div class="control-group">
        <label for="cc_exp_year">Exp Year</label>
        <div class="controls">
            <input id="cc_exp_year" name="cc_exp_year" type="text">
        </div>
    </div>
</form>

@endsection

@section('scripts')

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="{{ URL::to('js/GetCardSwipe.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            console.log("Adding listeners to the document body.")
            addListeners();
            console.log("Good to go!")
        });
    </script>


@endsection