@extends('layouts.master')

@section('title')
    big bike agent order
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
@endsection
@section('content')

    <div class="row">
        <h2>Bike Tour Registration</h2>

        @if(Session::has('error'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="charge-message" class="alert alert-warning">
                        {{ Session::get('error') }}
                        {{ Session::forget('error') }}
                    </div>
                </div>
            </div>
        @endif

        @if(!empty($error))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="charge-message" class="alert alert-warning">
                        {{ $error }}
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-6">
            <form action="" id="payment-form" method="post">
                <h3>Customer Info</h3><br>
                <label id="tour_date_label">
                    <span>Name</span><br>
                    <input name="tour_customer" id="tour_customer" class="field is-empty"/>
                </label><br><br>
                <label id="tour_time_label">
                    <span>Email</span><br>
                    <input name="tour_email" id="tour_email" class="field is-empty" type="email" />
                </label><br><br>


                <label>
                    <span><span>Place </span></span>
                    <select class="agent-order-place form-control" id="tour_place" onchange="checkTourType(this);">
                        <option>Central Park</option>
                        <option>Brooklyn Bridge</option>
                        <option>Movies & Film</option>
                        <option>Arts & Architecture</option>
                        <option>Uptown NYC</option>
                        <option>Downtown NYC</option>
                    </select>
                </label><br><br>

                <label>
                    <span><span>Date </span></span><br>
                    <input name="date" class="datepicker" id="tour_date" class="field is-empty" placeholder="" />
                </label><br><br>
                <label>
                    <span><span>Tour Start </span></span><br>
                    <select  class="agent-order-duration form-control" id="tour_time">
                        <option>9AM</option>
                        <option>10AM</option>
                        <option>1PM</option>
                        <option>4PM</option>
                    </select>
                </label>
                <label>
                    <span><span>Type of Tour </span></span>
                    <select class="agent-order-place form-control" id="tour_type">
                        <option id="public" value="public(2h)">public(2h)</option>
                        <option value="private(2h)">private(2h)</option>
                        <option value="private(3h)">private(3h)</option>
                    </select>
                </label><br><br>
                <label>
                    <span><span>Number of adult tours </span></span>
                    <input name="tour_adult" id="tour_adult" class="tour-spinner field is-empty" placeholder="0"/>
                </label><br><br>
                <label>
                    <span><span>Number of child tours </span></span>
                    <input name="tour_child" id="tour_child" class="tour-spinner field is-empty" placeholder="0" />
                </label><br><br>
                {{--<label>--}}
                    {{--<span><span>Total number of tours: </span></span>--}}
                    {{--<input name="total_tours" class="readonly field is-empty" placeholder="0" readonly />--}}
                {{--</label><br><br>--}}
                <div id="" class="agent-order-duration form-group">
                    <label for="sel1">Duration </label>
                    <select class="agent-order-duration form-control" id="tour_duration">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>5</option>
                        <option>All Day (8am-9pm)</option>
                        <option>24 hours</option>
                    </select>
                </div><br><br>
                {{ csrf_field() }}
            </form>
            <button type="submit" id="tour-submit" class="btn btn-success" onclick="calculateTour(); return false;">Calculate</button>
        </div>



        <div class="col-md-6">
            <form action="{{ route('agent.tourOrderSubmit') }}" id="tour-pay-form" method="post">
                <h3>Check your order</h3>

                <label id="tour_date_label_pay">
                    <span>Name</span>
                    <input name="tour_customer_pay" id="tour_customer_pay" class="field is-empty"/>
                </label><br><br>
                <label id="tour_time_label_pay">
                    <span>Email</span>
                    <input name="tour_email_pay" id="tour_email_pay" class="field is-empty" type="email" />
                </label><br><br>
                <label>
                    <span><span>Place </span></span>
                    <input class="readonly field is-empty" id="tour_place_pay" readonly/>
                </label><br><br>
                <label>
                    <span><span>Date </span></span>
                    <input name="tour_date_pay" class="readonly field is-empty" id="tour_date_pay" readonly/>
                </label><br><br>
                <label>
                    <span><span>Tour Start Time </span></span>
                    <input name="tour_time_pay" class="readonly field is-empty" id="tour_time_pay" readonly/>
                </label><br><br>
                <label>
                    <span><span>Type of Tour </span></span>
                    <input name="tour_type_pay" class="readonly field is-empty" id="tour_type_tour_pay" readonly/>
                </label><br><br>
                <label>
                    <span><span>Number of adult tours </span></span>
                    <input name="tour_adult_pay" id="tour_adult_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span><span>number of child tours </span></span>
                    <input name="tour_child_pay" id="tour_child_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span><span>Total number of tours </span></span>
                    <input name="tour_total_pay" id="tour_total_people_pay" class="readonly field is-empty" readonly />
                </label><br><br>
                <label>
                    <span >Duration </span>
                    <input id="tour_duration_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span><span>Total </span></span>
                    <input name="tour_total_pay" id="tour_total_pay" class="readonly field is-empty" readonly />
                </label><br><br>
                <label class="" id="tour_agent_total_label">
                    <span>Agent Cash Charge: </span>
                    <input name="tour_agent_total_pay" id="tour_agent_total_pay" class="field is-empty" />
                    {{--<div class="alert alert-info">If customer pay cahsh, the total amount agent charges can not be more than 30% of total price</div>--}}
                </label>            <h3>Payment method</h3>

                {{ csrf_field() }}
                <button type="submit" class="btn btn-success" id="mer" name="credit_card" value="credit_card">Credit Card</button>
                <button type="submit" class="btn btn-success" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>
            </form>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-order.js') }}"></script>
    <script src="{{ URL::to('js/agent-tour-order.js') }}"></script>

@endsection

