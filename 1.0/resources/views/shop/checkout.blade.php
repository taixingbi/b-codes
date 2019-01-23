@extends('layouts.master')

@section('title')
    Checkout
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/checkout.css') }}">

@endsection

@section('content')
    {{--<div class="row">--}}
        {{--<div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">--}}
            {{--<h1>Checkout</h1>--}}
            {{--<h4>Your total: ${{ $total }}</h4>--}}
            {{--<form action="{{ route('checkout') }}" method="post" id="checkout-form">--}}
                {{--<div class="row">--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="name">Name</label>--}}
                            {{--<input type="text" id="name" class="form-control" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="address">Address</label>--}}
                            {{--<input type="text" id="address" class="form-control" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="card-name">Card Holder Name</label>--}}
                            {{--<input type="text" id="card-name" class="form-control" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="card-number">Credit Card Number</label>--}}
                            {{--<input type="text" id="card-number" class="form-control" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-12">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="card-expirate-month">Month</label>--}}
                                    {{--<input type="text" id="card-expirate-month" class="form-control" required>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-xs-6">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label for="card-expirate-year">Year</label>--}}
                                    {{--<input type="text" id="card-expirate-year" class="form-control" required>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    {{--<div class="col-xs-12">--}}
                        {{--<div class="form-group">--}}
                            {{--<label for="card-cvc">CVC</label>--}}
                            {{--<input type="text" id="card-cvc" class="form-control" required>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<!-- Used to display Element errors -->--}}
                    {{--<div id="card-errors" class="alter alter-danger {{ !Session::has('error')? 'hidden':'' }}">--}}
                        {{--{{ Session::get('error') }}--}}
                    {{--</div>--}}

                    {{--<label for="card-element">--}}
                        {{--Credit or debit card--}}
                    {{--</label>--}}
                    {{--<div id="card-element">--}}
                        {{--<!-- a Stripe Element will be inserted here. -->--}}
                    {{--</div>--}}

                {{--</div>--}}
                {{--{{ csrf_field() }}--}}
                {{--<button type="submit" class="btn btn-success">Buy now</button>--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}


    <form action="{{ route('checkout') }}" id="payment-form" method="post">
        <label>
            <input name="cardholder-name" class="field is-empty" placeholder="Jane Doe" />
            <span><span>Name{{ $total }}</span></span>
        </label>
        <label>
            <input name="address" class="field is-empty" placeholder="Jane Doe" />
            <span><span>Address</span></span>
        </label>
        <label>
            <input class="field is-empty" type="tel" placeholder="(123) 456-7890" />
            <span><span>Phone</span></span>
        </label>
        <label for="card-element">
            <div id="card-element" class="field is-empty"></div>
            <span><span>Card</span></span>
        </label>
        <div id='card-errors' class="error"></div>
        {{ csrf_field() }}
        {{--<button type="submit">{{ $total }}</button>--}}
        <button type="submit">submit</button>

        <div class="outcome">
            <div class="success">
                Success! Your Stripe token is <span class="token"></span>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ URL::to('js/checkout.js') }}"></script>
@endsection