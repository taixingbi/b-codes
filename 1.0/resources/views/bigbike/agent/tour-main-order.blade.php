
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
@endsection

<div class="row">
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
</div>
<br>
<div class="col-md-10">
    <form action="@if( empty($isEdit)) {{ route('agent.tourOrderSubmit') }} @else {{ route('agent.tourEditSubmitForm') }} @endif" id="payment-form" method="post">
        <div><h4>CUSTOMER INFO</h4></div>
        <label id="tour_date_label">
            <span>First Name</span><br>
            <input name="tour_customer_first" id="tour_customer_first" class="field is-empty" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_name'] }} @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['customer_name'] }} @endif"/>
        </label>
        <label id="rent_date_label">
            <span>Last Name</span><br>
            <input name="tour_customer_last" id="tour_customer_last" class=" is-empty" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_lastname'] }} @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['customer_lastname'] }} @endif"/>
        </label>
        <label id="tour_time_label">
            <span>Email</span><br>
            <input name="tour_email" id="tour_email" class="field is-empty" type="email" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_email'] }} @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['customer_email'] }} @endif"/>
        </label>
        <label class="shak" id="rent_time_label">
            <span>Country/State</span><br>
            <input name="tour_country" id="tour_country" class="is-empty" type="text" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_country'] }} @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['customer_country'] }} @endif"/>
        </label>
        <div @if(Session::has('location') && (Session::get('location')!='203W 58th Street' && Session::get('location')!='145 Nassau Street' && Session::get('location')!='117W 58th Street' && Session::get('location')!='40W 55th Street')) style="display: none;" @endif>
        <div class="shak" style="display: inline-block;margin-top:15px;margin-bottom: 10px">
            <span class="padding-sp">Coupon:
            <input name="tour_coupon" id="tour_coupon" type="checkbox" value="coupon" @if($agent_tours_order['payment_type']=='coupon') checked @endif @if($agent_tours_order_cc['payment_type']=='coupon') checked @endif>
            </span>
        </div>
        </div>
        <br>


        <label id="rent_date_label">
            <span>Address & Phone</span><br>
            {{--<input name="rent_customer_address" id="rent_customer_address" class=" is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['customer_lastname'] }} @endif"/>--}}
            <textarea rows="2" cols="35" form="payment-form" name="tour_customer_address_phone" id="tour_customer_address_phone" class=" is-empty" >@if(!empty($agent_tours_order)) {{ $agent_tours_order['customer_address_phone'] }}  @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['customer_address_phone'] }}  @endif</textarea>
        </label>

        <label id="" class="extra"><br>
            <span>Comment</span><br>
            <textarea rows="2" cols="35" form="payment-form" name="comment" id="comment" class=" is-empty" >@if(!empty($agent_tours_order)) {{ $agent_tours_order['comment'] }}  @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['comment'] }}  @endif</textarea>

            {{--<input style="font-size:16px;font-weight: bold" name="rent_comment" id="rent_comment" class=" is-empty"  />--}}
        </label><br><br>

        <h4>ORDER INFO</h4>
        <label id="tour_date_label">
            <span><span>Date </span></span><br>
            <input name="tour_date" class="datepicker" id="tour_date" class="field is-empty" placeholder="" />
        </label>
        <label id="tour_time_label">
            <span><span>Tour Start Time </span></span><br>
            <select  class="agent-order-duration form-control" name="tour_time" id="tour_time" >
                <option @if(!empty($agent_tours_order) && strtoupper($agent_tours_order['time'])=="9AM") selected @endif @if(!empty($agent_tours_order_cc) && strtoupper($agent_tours_order_cc['time'])=="9AM") selected @endif>9AM</option>
                <option @if(!empty($agent_tours_order) && strtoupper($agent_tours_order['time'])=="10AM") selected @endif @if(!empty($agent_tours_order_cc) && strtoupper($agent_tours_order_cc['time'])=="10AM") selected @endif>10AM</option>
                <option @if(!empty($agent_tours_order) && strtoupper($agent_tours_order['time'])=="12PM") selected @endif @if(!empty($agent_tours_order_cc) && strtoupper($agent_tours_order_cc['time'])=="12PM") selected @endif>12PM</option>
                <option @if(!empty($agent_tours_order) && strtoupper($agent_tours_order['time'])=="1PM") selected @endif @if(!empty($agent_tours_order_cc) && strtoupper($agent_tours_order_cc['time'])=="1PM") selected @endif>1PM</option>
                <option @if(!empty($agent_tours_order) && strtoupper($agent_tours_order['time'])=="4PM") selected @endif @if(!empty($agent_tours_order_cc) && strtoupper($agent_tours_order_cc['time'])==="4PM") selected @endif>4PM</option>
            </select>
        </label>
        <label>
            <span><span>Place </span></span>
            {{--<select class="agent-order-place form-control" name="tour_place" id="tour_place" onchange="checkTourType(this);">--}}
            <select class="agent-order-place form-control" name="tour_place" id="tour_place" >
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Central Park") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Central Park") selected @endif>Central Park Bike Tour</option>
                <option value="walking" @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Central Park Walking Tour") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Central Park Walking Tour") selected @endif>Central Park Walking Tour</option>
                <option value="pedicab" @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Pedicab Tour") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Pedicab Tour") selected @endif>Pedicab Tour</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Brooklyn Bridge") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Brooklyn Bridge") selected @endif>Brooklyn Bridge Bike Tour</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Brooklyn Bridge Walking Tour") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Brooklyn Bridge Walking Tour") selected @endif>Brooklyn Bridge Walking Tour</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Movies & Film") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Movies & Film") selected @endif>Movies & Film</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Arts & Architecture") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Arts & Architecture") selected @endif>Arts & Architecture</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Uptown NYC") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Uptown NYC") selected @endif>Uptown NYC</option>
                <option @if(!empty($agent_tours_order) && $agent_tours_order['tour_place']=="Downtown NYC") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_place']=="Downtown NYC") selected @endif>Downtown NYC</option>
            </select>
        </label>

        <label>
            <span><span>Type of Tour </span></span>
            <select class="agent-order-place form-control" name="tour_type" id="tour_type">
                <option id="public" value="public(2h)" @if(!empty($agent_tours_order) && $agent_tours_order['tour_type']=="public(2h)") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_type']=="public(2h)") selected @endif>public(2h)</option>
                <option value="private(2h)" @if(!empty($agent_tours_order) && $agent_tours_order['tour_type']=="private(2h)") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_type']=="private(2h)") selected @endif>private(2h)</option>
                <option value="private(3h)" @if(!empty($agent_tours_order) && $agent_tours_order['tour_type']=="private(3h)") selected @endif @if(!empty($agent_tours_order_cc) && $agent_tours_order_cc['tour_type']=="private(3h)") selected @endif>private(3h)</option>
            </select>
        </label><br>
        <label >
            <span><span>Number of adult tours </span></span><br>
            <input name="adult_tour" id="adult_tour" class="tour-spinner field is-empty" value="@if(!empty($agent_tours_order)) {{  $agent_tours_order['adult'] }} @endif @if(!empty($agent_tours_order_cc)) {{  $agent_tours_order_cc['adult'] }} @endif" placeholder="0"/>
        </label>
        <label id="child_tour_label">
            <span><span>Number of child tours </span></span><br>
            <input name="child_tour" id="child_tour" class="tour-spinner field is-empty" value="@if(!empty($agent_tours_order)) {{  $agent_tours_order['child'] }} @endif @if(!empty($agent_tours_order_cc)) {{  $agent_tours_order_cc['child'] }} @endif" placeholder="0" />
        </label><br>
        <label id="child_tour_label">
            <span><span>Baskets </span></span><br>
            <input name="basket_tour" id="basket_tour" class="tour-spinner field is-empty" value="@if(!empty($agent_tours_order)) {{  $agent_tours_order['basket'] }} @endif @if(!empty($agent_tours_order_cc)) {{  $agent_tours_order_cc['basket'] }} @endif" placeholder="0" />
        </label>
        <label id="child_tour_label">
            <span><span>Baby Seat </span></span><br>
            <input name="seat_tour" id="seat_tour" class="tour-spinner field is-empty" value="@if(!empty($agent_tours_order)) {{  $agent_tours_order['seat'] }} @endif @if(!empty($agent_tours_order_cc)) {{  $agent_tours_order_cc['seat'] }} @endif  " placeholder="0" />
        </label>
        <div style="display: inline-block;margin-top:15px;margin-bottom: 10px">
            <span class="padding-sp">Insurance: $2 each
                <input name="insurance" id="insurance" type="checkbox" @if($agent_tours_order['insurance']=='1') checked @endif @if($agent_tours_order_cc['insurance']=='1') checked @endif>
            </span>
        </div>
        <label class="c24hours-label" style="display: none">
            <span>isReservation</span><br>
            <input name="reservation_bike" id="reservation_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($reservation)){{ $reservation }}@endif"  placeholder="0"/>
        </label>
        {{--<label>--}}
        {{--<span><span>Total number of tours: </span></span><br>--}}
        {{--<input name="total_tours" class="readonly field is-empty" placeholder="0" readonly />--}}
        {{--</label>--}}
        <br><br>
        {{--<div><h4>Agent Info</h4></div>--}}
        {{--<label id="rent_adjust_label" >--}}
        {{--<span>Agent name</span><br>--}}
        {{--<input style="font-size:16px;font-weight: bold" name="tour_agent" id="tour_agent" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['agent_name'] }} @endif" class=" is-empty" type="text" />--}}
        {{--</label>--}}
        {{--<label id="rent_adjust_label" ><br>--}}
        {{--<span>Level</span><br />--}}
        {{--<input style="font-size:16px;font-weight: bold" name="tour_agent_level" id="tour_agent_level" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['agent_level'] }}  @endif" class="readonly is-empty" type="text" readonly/>--}}
        {{--</label><br>--}}
        {{--<label id=""><br>--}}
        {{--<span>Total Price</span><br>--}}
        {{--$<input style="font-size:16px;font-weight: bold" name="tour_total" id="tour_total" class="readonly field is-empty" type="text" readonly/>--}}
        {{--</label>--}}
        {{--<label id=""><br>--}}
        {{--<span>Tax</span><br>--}}
        {{--$<input style="font-size:16px;font-weight: bold;color:green;" name="tour_tax" id="tour_tax" class="readonly is-empty" type="text" readonly/>--}}
        {{--</label>--}}
        {{--<label id=""><br>--}}
        {{--<span>Total After Tax</span><br>--}}
        {{--$<input style="font-size:16px;font-weight: bold" name="tour_total_after_tax" id="tour_total_after_tax" class="readonly is-empty" type="text" readonly/>--}}
        {{--</label>--}}

        {{--<label id=""><br>--}}
        {{--<span>Cash Paid</span><br>--}}
        {{--<input style="font-size:16px;font-weight: bold;color:green;" name="cash_paid_label" id="cash_paid_label" class=" is-empty" type="text"/>--}}
        {{--</label>--}}
        {{--<label id="">--}}
        {{--<span>Cash Change</span><br>--}}
        {{--<input style="font-size:16px;font-weight: bold" name="cash_change_label" id="cash_change_label" class="readonly is-empty" type="text" readonly/>--}}
        {{--</label><br>--}}
        {{--<br><br>--}}

        

        <?php
            $iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            $Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
            $webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
            $isWeb = true;
            //do something with this information
            if( $iPod || $iPhone || $iPad || $Android){
                //browser reported as an iPhone/iPod touch -- do something here
                $isWeb =false;
            }else if($webOS){
                //browser reported as a webOS device -- do something here
                $isWeb = false;
            }else{
                $isWeb = true;
            }
        ?>

        @include('bigbike.agent.calculation-tour')
        <br><br>
        <h4>PAYMENT METHOD</h4>
        {{ csrf_field() }}
        <input type="submit" class="btn btn-success" id="credit_card" name="credit_card" value="Credit Card" onclick="return ccSubmitCheck()" />
        @if($isWeb)
        <input type="submit" class="btn btn-success shak" id="cashBtn" name="cash" value="Cash" onclick="return cashSubmitCheck()" /><br><br>
        @endif
    </form>
</div>

@php
    if(Session::has("inv_cart")){
        $inv_price = Session::get("inv_cart")["price"];
    }else{
        $inv_price = 0;
    }


    if(isset($agent_tours_order['tix_agent']) && !empty($agent_tours_order['tix_agent'])){
        $tix_agent = $agent_tours_order['tix_agent'];
    }else{
        $tix_agent = null;
    }

@endphp
@section('scripts')
    <script>
        var tix_agent = '<?php echo $tix_agent; ?>';
        console.log("tix_agent: "+tix_agent);
        var payment_type = '<?php echo $agent_tours_order['payment_type']; ?>';
        console.log("payment_type: "+payment_type);
        var due = 0;
        if(payment_type=="Cash" && tix_agent){
            due = '<?php echo ($agent_tours_order['total_price_after_tax']-$agent_tours_order['agent_price_after_tax']); ?>';
            console.log("pay due in store ");
            swal('Payment Due in Store: $'+due);

            $("#rent_total_label").val(due);
            $("#rent_tax").val(0);

            $("#rent_total_after_tax").val(due);
            $("#rent_total_after_tax_deposit").val(due);

            // calculate();
        }else{
            // console.log("no");
        }


        var originalPriceBefore = '{{ $agent_tours_order['total_price_before_tax'] }}';
        if('{{ $agent_tours_order['original_price'] }}'.length!=0){
//            console.log('null');
            originalPriceBefore = '{{ $agent_tours_order['original_price'] }}';
        }
        if(tix_agent){
            originalPriceBefore = (originalPriceBefore/(0.7*1.08875)).toFixed(2)-due;
        }
        console.log("originalPriceBefore: "+originalPriceBefore);

        {{--var originalPrice = '{{ $agent_rents_order['total_price_after_tax'] }}';--}}
        //        var originalPriceBefore = 0;
        //        console.log('originalPriceBefore: '+originalPriceBefore);
        var agentList = [];
        var agentListMap = new Map();
        @foreach($agents as $agent)
            agentList.push('{{$agent->fullname}}');
        agentListMap.set('{{$agent->fullname}}', '{{$agent->level}}');
                @endforeach

                {{--var originalPriceBefore = '{{ $agent_rents_order['total_price_before_tax'] }}';--}}
                {{--if('{{ $agent_rents_order['original_price'] }}'.length!=0){--}}
                {{--console.log('null');--}}
                {{--originalPriceBefore = '{{ $agent_rents_order['original_price'] }}';--}}
                {{--}--}}
        var originalPrice = '{{ $agent_tours_order['total_price_after_tax'] }}';

        var levshak = '<?php echo $user->level ;?>';
        var inv_price = '<?php echo $inv_price ;?>';
        console.log("inv: "+inv_price);
        if (levshak==='4'){
            $('.shak').hide();
        }

    </script>
    <script
            src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
            crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script src="{{ URL::to('js/notify.min.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.min.js') }}"></script>
    <script src="{{ URL::to('js/agent-order-backup.js') }}"></script>
    <script src="{{ URL::to('js/agent-tour-backup.js') }}"></script>
    <script src="{{ URL::to('js/agent-barcode.js') }}"></script>
@endsection

