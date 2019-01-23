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
            <h3>Customer Info Search</h3>

            <div class="col-md-12" >
                <form action="{{ route('agent.getCustomerInfo') }}" id="payment-form" method="post">

                    <label class="member_checkbox_label shak">
                        <input class="agent-order-place form-control"  name="first_name" id="rent_agent" onchange="" placeholder="First Name" />

                    </label>
                    <label class="member_checkbox_label shak">
                        <input class="agent-order-place form-control"  name="last_name" id="rent_agent" onchange=""  placeholder="Last Name"/>

                    </label><br>
                    <label class="member_checkbox_label shak">
                        <select name="type">
                            <option value="Rent">Rent</option>
                            <option value="Tour">Tour</option>
                        </select>
                    </label><br>
                    <label id="admin_date_label">
                        <input type="text" name="datepickerday" id="datepickerday" class=" field is-empty" />
                    </label><br><br>


                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-primary" id="submit" >Search</button>
                    <br><br><br>
                </form>
            </div>

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
    <script src="{{ URL::to('js/admin-agent-pos.js') }}"></script>

    <script>



    </script>

@endsection