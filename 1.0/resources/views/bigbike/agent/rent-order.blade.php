@extends('bigbike.agent.rent-main')

{{--@section('title')--}}
    {{--big bike agent order--}}
{{--@endsection--}}

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
@endsection
@section('content')

    <div class="row">
        <h2>Bike Rental Registration</h2>
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



        <div class="col-md-6">
            <form action="" id="payment-form" method="post">
                <h3>Customer Info</h3><br>
                <label id="rent_date_label">
                    <span>Name</span><br>
                    <input name="rent_customer" id="rent_customer" class="field is-empty"/>
                </label><br><br>
                <label id="rent_time_label">
                    <span>Email</span><br>
                    <input type="email" name="rent_email" id="rent_email" class="field is-empty" />
                </label><br><br>

                <h3>Date & Time</h3><br>
                <label id="rent_date_label">
                    <span>Date</span><br>
                    <input name="date" id="rent_date" class="datepicker field is-empty"/>
                </label><br><br>
                <label id="rent_time_label">
                    <span>Time</span><br>
                    <input name="test" id="rent_time" class="timepicker" type="text" />
                </label><br><br>
                <div id="" class="agent-order-duration form-group">
                    <label for="rent_duration">
                        <span>Duration</span>
                        <select class="agent-order-duration form-control" id="rent_duration">
                            <option>1 hour</option>
                            <option>2 hours</option>
                            <option>3 hours</option>
                            <option>5 hours</option>
                            <option>All Day (8am-9pm)</option>
                            <option>24 hours</option>
                        </select>
                    </label>
                </div>
                <h3>Type of Bikes</h3><br>
                <table>
                    <tr>
                        <td><label>
                    <span>Adult Bike</span><br>
                    <input name="adult_bike" id="adult_bike" class="spinner field is-empty" value="0" placeholder="0"/>
                    </label><br><br></td>
                        <td>
                            <label class="c24hours-label">
                                <span>Tandem Bike</span><br>
                                <input name="tandem_bike" id="tandem_bike" class="c24hours spinner field is-empty" value="0" placeholder="0" />
                            </label><br><br>
                        </td>
                        </tr>
                        <tr>
                            <td>
                    <label>
                        <span>Child Bike</span><br>
                        <input name="child_bike" id="child_bike" class="spinner field is-empty" value="0" placeholder="0" />
                    </label><br><br></td>
                            <td>
                                <label class="c24hours-label">
                                    <span> Child Bike Trailers</span><br>
                                    <input name="trailer" id="trailer" class="c24hours spinner field is-empty" value="0" placeholder="0" />
                                </label><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td>
                    <label class="c24hours-label">
                        <span>Road Bike</span><br>
                        <input name="road_bike" id="road_bike" class="c24hours spinner field is-empty" value="0" placeholder="0" />
                    </label><br><br>
                            </td>
                            <td>
                            <label class="c24hours-label">
                                <span>Mountain Bike</span><br>
                                <input name="mountain_bike" id="mountain_bike" class="c24hours spinner field is-empty" value="0" placeholder="0" />
                            </label><br><br></td>
                            </tr>
                        <tr>
                            <td>
                    <label class="c24hours-label">
                        <span>Child Seat: </span><br>
                        <input name="seat" id="seat" class="c24hours spinner field is-empty" value="0" placeholder="0"/>
                    </label></td></tr>
                </table>
                <h3>Basket & Insurance</h3>
                <div style="display: flex">
                    <label class="c24hours-label">
                        <span>Basket: </span><br>
                        <input name="basket" id="basket" class="c24hours spinner field is-empty" value="0" placeholder="0"/>
                    </label><br><br>
                </div>
                <div>
                    <label>
                        <span> Insurance</span>
                        <input type="checkbox" name="insurance" id="insurance" class="field is-empty"  />
                    </label>
                    <label>
                        <span style="padding-left: 10px;">Drop Off</span>
                        <input type="checkbox" name="dropoff" id="dropoff" class="field is-empty" />
                    </label><br><br>
                </div>

                {{ csrf_field() }}
            </form>
            <button type="submit" class="btn btn-primary" id="rent-submit" onclick="calculateRent(); return false;">Calculate</button>

        </div>

        <div class="col-md-4">
            {{--<form action="{{ route('agent.rentOrderSubmit') }}" id="rent-pay-form" method="post">--}}
            <form action="{{ route('agent.rentOrderSubmit') }}" id="rent-pay-form" method="post">

                <h3>Check your order</h3><br>
                <label id="rent_date_label">
                    <span>Name</span>
                    <input name="rent_customer_pay" id="rent_customer_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label id="rent_time_label">
                    <span>Email</span>
                    <input name="rent_email_pay" id="rent_email_pay" class="readonly field is-empty" type="email" readonly/>
                </label><br><br>

                <label>
                    <span>Date: </span>
                    <input name="rent_date_pay" id="rent_date_pay" class="readonly field is-empty" readonly />
                </label><br><br>
                <label>
                    <span>Time: </span>
                    <input name="rent_time_pay" id="rent_time_pay" class="readonly" type="text" readonly/>
                </label><br><br>
                <label>
                    <span>Adult Bike: </span>
                    <input name="adult_bike_pay" id="adult_bike_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Child Bike: </span>
                    <input name="child_bike_pay" id="child_bike_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Tandem Bike: </span>
                    <input name="tandem_bike_pay" id="tandem_bike_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Road Bike: </span>
                    <input name="road_bike_pay" id="road_bike_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Mountain Bike: </span>
                    <input name="mountain_bike_pay" id="mountain_bike_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Child Bike Trailer: </span>
                    <input name="trailer_pay" id="trailer_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Child Seat: </span>
                    <input name="seat_pay" id="seat_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label class="c24hours-label">
                    <span>Basket: </span>
                    <input name="basket_pay" id="basket_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Insurance</span>
                    <input type="checkbox" name="insurance_pay" id="insurance_pay" class="readonly field is-empty" onclick="return false;" />
                </label><br><br>
                <label>
                    <span>Drop Off</span>
                    <input type="checkbox" name="dropoff_pay" id="dropoff_pay" class="readonly field is-empty" onclick="return false;" />
                </label><br><br>
                <label>
                    <span >Duration: </span>
                    <input name="rent_duration_pay" id="rent_duration_pay" class="readonly field is-empty" readonly/>
                </label><br><br>
                <label>
                    <span>Total: </span>
                    <input name="rent_total_pay" id="rent_total_pay" class="readonly field is-empty" readonly/>
                </label>
                {{--<label class="agent_total_pay_label">--}}
                <label class="" id="rent_agent_total_label">
                    <span>Agent Cash Charge: </span>
                    <input name="rent_agent_total_pay" id="rent_agent_total_pay" class="field is-empty" />
                    {{--<div class="alert alert-info">If customer pay cahsh, the total amount agent charges can not be more than 30% of total price</div>--}}
                </label>      <h3>Payment method</h3>

                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary" name="credit_card" id="mer" value="credit_card">Credit Card</button>
                <button type="submit" class="btn btn-primary" name="cash" value="cash" onclick="return cashSubmitCheck()">Cash</button><br><br>
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
    <script src="{{ URL::to('js/agent-rent-order.js') }}"></script>

@endsection
