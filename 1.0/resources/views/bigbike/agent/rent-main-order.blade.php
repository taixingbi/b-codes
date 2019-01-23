@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

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
</div>
<br>

<div class="col-md-11">
    {{--@role('admin')--}}
    <h4 class="shak">Type of Guest </h4>
    {{--@endrole--}}
    {{--<label id="rent_barcode_label" class="shak" style="margin-bottom: 15px;">--}}
        {{--<span>Barcode Scan</span><br>--}}
        {{--<input name="rent_barcode" id="rent_barcode" class=" is-empty"/>--}}
    </label><br>

    <form action="@if( empty($isEdit)){{ route('agent.rentOrderSubmit') }} @else {{ route('agent.editSubmitForm') }} @endif" id="payment-form" method="post">
        <label class="custom-control shak custom-checkbox">
            <input type="checkbox" name="member_checkbox" id="member_checkbox" class="custom-control-input">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Membership Number</span>
        </label>
        <label class="custom-control shak custom-checkbox">
            <input type="checkbox" name="member_guest_checkbox" id="member_guest_checkbox" class="custom-control-input" value="Member Guest">
            <span class="custom-control-indicator"></span>
            <span class="custom-control-description">Membership-Guest</span>
        </label>
        <label class="c24hours-label shak" @if(Session::has('location') && (Session::get('location')!='203W 58th Street' && Session::get('location')!='145 Nassau Street' && Session::get('location')!='117W 58th Street' && Session::get('location')!='40W 55th Street')) style="display: none;" @endif>
            <input type="checkbox" name="coupon_bike" id="coupon_bike" class="c24hours field is-empty" value="coupon" @if($agent_rents_order['payment_type']=='coupon') checked @endif @if($agent_rents_order_cc['payment_type']=='coupon') checked @endif/>
            <span>Coupon</span>
        </label><br>

        <label class="member_checkbox_label shak" id="rent_date_label">
            <span>Membership</span><br>
            <input name="rent_membership" id="rent_membership" class=" is-empty"/>
        </label>
        <button class="member_checkbox_label btn btn-primary"  type="submit" name="member_search" id="member_search" value="member_search" onclick="return memberSearch()">Search</button>
        <br>
        <label class="member_checkbox_label shak">
            <span><span>Type</span></span>
            <select disabled="disabled" class="agent-order-place form-control readonly"  name="member_type" id="member_type" onchange="" >
                <option></option>
                @foreach($memberships as $membership)
                    <option value="{{$membership->title}}">{{$membership->title}}</option>
                @endforeach
            </select>
        </label>
        {{--<label class="member_checkbox_label">--}}
            {{--<span><span>Type</span></span>--}}
            {{--@foreach($memberships as $membership)--}}
                {{--<input type="radio" name="member_type" id="member_type" value="{{$membership->title}}"/><label for="member_type">{{$membership->title}}</label>--}}
                {{--@endforeach--}}
                {{--</select>--}}
        {{--</label>--}}
        <label class="member_checkbox_label shak" id="rent_date_label">
            <span>Expiration Data</span><br>
            <input name="rent_membership_expire" id="rent_membership_expire" class="readonly is-empty" readonly/>
        </label>


        <div><h4>Customer Info</h4></div>
        <label id="rent_date_label">
            <span>First Name</span><br>
            <input name="rent_customer" id="rent_customer" class="field is-empty" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['customer_name'] }} @endif @if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['customer_name'] }} @endif"/>
        </label>
        <label id="rent_date_label">
            <span>Last Name</span><br>
            <input name="rent_customer_last" id="rent_customer_last" class="field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['customer_lastname'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['customer_lastname'] }} @endif"/>
        </label>
        <label id="rent_time_label">
            <span>Email</span><br>
            <input name="rent_email" id="rent_email" class="field is-empty" type="email" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['customer_email'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['customer_email'] }} @endif"/>
        </label>


        <label class="shak" id="rent_time_label">
            <span>Country/State</span><br>
            <input name="rent_country" id="rent_country" class="field is-empty" type="text" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['customer_country'] }}@endif @if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['customer_country'] }}@endif"/>
        </label>

        <br><br>


        <label id="" class="extra">
            <span>Address & Phone</span><br>
            <textarea rows="2" cols="35" form="payment-form" name="rent_customer_address_phone" id="rent_customer_address_phone" class="field is-empty" >@if(!empty($agent_rents_order)) {{ $agent_rents_order['customer_address_phone'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['customer_address_phone'] }} @endif</textarea>

        </label>
        <label id="rent_date_label">
            <span>Comment</span><br>
            <textarea rows="2" cols="35" form="payment-form" name="comment" id="comment" class="field is-empty" >@if(!empty($agent_rents_order)) {{ $agent_rents_order['comment'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['comment'] }}  @endif</textarea>
        </label><br><br>


        <div><h4>Order Info</h4></div>

        <label id="rent_date_label">
            <span>Date</span><br>
            <input name="rent_date" id="rent_date" class="datepicker is-empty" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['date'] }} @endif @if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['date'] }} @endif"/>
        </label>
        <label id="rent_date_label" style="display: none">
            <span>location</span><br>
            <input name="location" id="location" value="{{ Session::get('location') }}" class=" is-empty"/>
        </label><br>
        

        <label for="rent_duration">
            <span>Hours</span><br>
            <input type="radio" name="rent_duration" class="rent_duration" value="1 hour" checked>1 hour</input>
            <input type="radio" name="rent_duration" class="rent_duration" value="2 hours" @if($agent_rents_order['duration']=='2 hours') checked @endif @if($agent_rents_order_cc['duration']=='2 hours') checked @endif>2 hours</input>
            <input type="radio" name="rent_duration" class="rent_duration" value="3 hours" @if($agent_rents_order['duration']=='3 hours') checked @endif @if($agent_rents_order_cc['duration']=='3 hours') checked @endif>3 hours</input>
            <input type="radio" name="rent_duration" class="rent_duration" value="5 hours" @if($agent_rents_order['duration']=='5 hours') checked @endif @if($agent_rents_order_cc['duration']=='5 hours') checked @endif>5 hours</input>
            <input type="radio" name="rent_duration" class="rent_duration" value="All Day (8am-8pm)" @if($agent_rents_order['duration']=='All Day (8am-8pm)') checked @endif @if($agent_rents_order_cc['duration']=='All Day (8am-8pm)') checked @endif>All Day (8am-8pm)</input>
            <input type="radio" name="rent_duration" class="rent_duration" value="24 hours" @if($agent_rents_order['duration']=='24 hours') checked @endif @if($agent_rents_order_cc['duration']=='24 hours') checked @endif>24 hours</input>
        </label><br><br>


        <label id="adult_bike_label">
            <span>Adult</span><br>
            <input name="adult_bike" id="adult_bike" class="bike spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['adult'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['adult'] }} @endif"  placeholder="0"/>
        </label>
        <label>
            <span>Child Bike</span><br>
            <input name="child_bike" id="child_bike" class="bike spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['child'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['child'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label">
            <span>Tandem</span><br>
            <input name="tandem_bike" id="tandem_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['tandem'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['tandem'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label">
            <span>Road</span><br>
            <input name="road_bike" id="road_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['road'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['road'] }} @endif"  placeholder="0" />
        </label><br />
        <label class="c24hours-label">
            <span>Mountain</span><br>
            <input name="mountain_bike" id="mountain_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['mountain'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['mountain'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label">
            <span> Tagalong(previous trailer)</span><br>
            <input name="trailer_bike" id="trailer_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['trailer'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['trailer'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label" >
            <span>Child Trailer</span><br>
            <input name="kid_trailer" id="kid_trailer" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['kid_trailer'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['kid_trailer'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label" >
            <span>Giant  Electric</span><br>
            <input name="electric_bike" id="electric_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['electric_bike'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['electric_bike'] }} @endif"  placeholder="0" />
        </label><br>
        <label class="c24hours-label" >
            <span>Elliptigo</span><br>
            <input name="elliptigo" id="elliptigo" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['elliptigo'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['elliptigo'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label" >
            <span>Tricycle</span><br>
            <input name="tricycle" id="tricycle" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['tricycle'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['tricycle'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label" >
            <span>Electric Hand</span><br>
            <input name="electric_hand" id="electric_hand" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['electric_hand'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['electric_hand'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label" >
            <span>Snow</span><br>
            <input name="snow" id="snow" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['snow'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['snow'] }} @endif"  placeholder="0" />
        </label>
        <label class="c24hours-label">
            <span>Child Seat</span><br>
            <input name="seat_bike" id="seat_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['seat'] }}  @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['seat'] }} @endif"  placeholder="0"/>
        </label>
        <br>
        <label class="c24hours-label">
            <span>Locks</span><br>
            <input name="lock_bike" id="lock_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['lock'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['lock'] }} @endif"  placeholder="0"/>
        </label>
        <label class="c24hours-label">
            <span>Basket</span><br>
            <input name="basket_bike" id="basket_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($agent_rents_order)) {{ $agent_rents_order['basket'] }} @endif @if(!empty($agent_rents_order_cc)) {{ $agent_rents_order_cc['basket'] }} @endif"  placeholder="0"/>
        </label>
        <br>
        <div style="display: inline-block;margin-top:15px;margin-bottom: 10px">
            <span class="padding-sp">Insurance: $2 each
                <input name="insurance" id="insurance" type="checkbox" @if($agent_rents_order['insurance']=='1') checked @endif @if($agent_rents_order_cc['insurance']=='1') checked @endif>
            </span>
            <span class="padding-sp">Drop off: $5 each
                <input name="dropoff" id="dropoff" type="checkbox" @if($agent_rents_order['dropoff']=='1') checked @endif @if($agent_rents_order_cc['dropoff']=='1') checked @endif>
            </span>
        </div><br>
        <label class="c24hours-label" style="display: none">
            <span>isReservation</span><br>
            <input name="reservation_bike" id="reservation_bike" class="bike c24hours spinner field is-empty" value="@if(!empty($reservation)){{ $reservation }}@endif"  placeholder="0"/>
        </label>


        @include('bigbike.agent.calculation')


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
        <h4>Payment method</h4>
        {{ csrf_field() }}
        <input type="submit" class="btn btn-primary ppBtn" name="credit_card" id="mer" value="Credit Card" onclick="return ccSubmitCheck()" />
        {{--@if($isWeb)--}}
        <input type="submit" class="btn btn-primary ppBtn" id="cashBtn" name="cash" value="Cash" onclick="return cashSubmitCheck()" /><br><br>
        {{--@endif--}}


    </form>

</div>


@php
    if(Session::has("inv_cart")){
        if(isset(Session::get("inv_cart")["price"])){

            $inv_price = Session::get("inv_cart")["price"];
        }else{
            $inv_price = 0;
        }
    }else{
        $inv_price = 0;
    }

    if(isset($agent_rents_order['agent_email']) && !empty($agent_rents_order['agent_email'])){
        $tix_agent = $agent_rents_order['agent_email'];
    }else{
        $tix_agent = null;
    }
@endphp

@section('scripts')
    <script>

        var tix_agent = '<?php echo $tix_agent; ?>';
        // console.log("tix_agent: "+tix_agent);
        var payment_type = '<?php echo $agent_rents_order['payment_type']; ?>';
        // console.log("payment_type: "+payment_type);
        var due = 0;
        if(payment_type=="Cash" && tix_agent){
            due = '<?php echo ($agent_rents_order['total_price_after_tax']-$agent_rents_order['agent_price_after_tax']); ?>';
            // console.log("pay due in store ");
            swal('Payment Due in Store: $'+due);

            $("#rent_total_label").val(due);
            $("#rent_tax").val(0);
            
            $("#rent_total_after_tax").val(due);
            $("#rent_total_after_tax_deposit").val(due);

            // calculate();
        }else{
            // console.log("no");
        }

        var agentList = [];
        var agentListMap = new Map();
        @foreach($agents as $agent)
            agentList.push('{{$agent->fullname}}');
            {{--agentListMap.set('{{$agent->fullname}}', '{{$agent->level}}');--}}

        @endforeach


        var originalAdult = '{{ $agent_rents_order['adult'] }}';
        var originalChild = '{{ $agent_rents_order['child'] }}';
        var originalTandem = '{{ $agent_rents_order['tandem'] }}';
        var originalRoad = '{{ $agent_rents_order['road'] }}';
        var originalMountain = '{{ $agent_rents_order['mountain'] }}';

        var originalKidtrailer = '{{ $agent_rents_order['kid_trailer'] }}';
        var originalElectric = '{{ $agent_rents_order['electric_bike'] }}';

        var originalTotalBikes = '{{ $agent_rents_order['total_bikes'] }}';
        var originalTrailer = '{{ $agent_rents_order['trailer'] }}';
        var originalSeat = '{{ $agent_rents_order['seat'] }}';
        var originalBasket = '{{ $agent_rents_order['basket'] }}';
        var originalInsurance = '{{ $agent_rents_order['insurance'] }}';
        var originalDropoff = '{{ $agent_rents_order['dropoff'] }}';


        var originalPriceBefore = '{{ $agent_rents_order['total_price_before_tax'] }}';
        if('{{ $agent_rents_order['original_price'] }}'.length!=0){
//            console.log('null');
            originalPriceBefore = '{{ $agent_rents_order['original_price'] }}';
        }
//        console.log('before: '+originalPriceBefore);
        var originalPrice = '{{ $agent_rents_order['total_price_after_tax'] }}';
        if(tix_agent){
            originalPriceBefore = (originalPriceBefore/0.7).toFixed(2)-due;
        }
        console.log("originalPriceBefore: "+originalPriceBefore);


        var levshak = '<?php echo $user->level ;?>';
        var inv_price = '<?php echo $inv_price ;?>';

        if (levshak==='4'){
            $('.shak').hide();
        }

    </script>
    {{--<script--}}
            {{--src="https://code.jquery.com/jquery-1.12.4.min.js"--}}
            {{--integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="--}}
            {{--crossorigin="anonymous"></script>--}}

    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>

    <script src="{{ URL::to('js/notify.min.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.min.js') }}"></script>
    {{--<script src="{{ URL::to('js/agent-order-backup.js') }}"></script>--}}
    {{--<script src="{{ URL::to('js/agent-rent-backup.js') }}"></script>--}}
    {{--<script src="{{ URL::to('js/agent-barcode.js') }}"></script>--}}
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script>
        $('.inventory').DataTable( {
            "pagingType": "full_numbers"
        } );
        $('#inventory').css("display","none");
        $('.inventory-rent').css("margin-left","800px");
        $('.inventory-rent').css("width","30%");
        $('.inventory-rent').css("margin-top","-716px");

        $('#inventory_wrapper').css("background-color","rgba(58, 201, 255, 0.34)");
        $('#inventory_wrapper').css("margin-left","800px");

        $( ".invBTN" ).click(function() {

            console.log(this.id);
            var tmp = this.id;
            var num = tmp.substring(4);
            console.log(num);
            var pricetag = "#price"+num;
            console.log($(pricetag).val());
            var qtytag = "#qty"+num;
            var qty = $(qtytag).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/bigbike/agent/inventory/updateCart',
                type: 'POST',
                data: {
                    id:num,
                    qty:qty
                },
                success: function(data, messages){

                    console.log(data.messages[0].price);
                    // swal(data.messages[0].message);
                    console.log(data.messages[0].code);
                    swal(data.messages[0].message);
                    // if(data.messages[0].code==100){
                    //     swal(data.messages[0].message);
                    // }else {
                    //     swal(data.messages[0].price);
                    // }

                    $("#inventory_price").val(data.messages[0].price);

                    // data.messages[0].price
                    setTimeout(function(){ window.location.reload(); }, 100);

                },error:function(e){
                    // var errors = e.responseJSON;
                    swal(e);
                }
            });

            // var price = parseFloat($(pricetag).val())*parseInt($(qtytag).val());
            // $("#inventory_price").val(price);
        });

        $(document).ready(function() {

            $('input[type=search]').keyup(function() {
                var inputVal = $('input[type=search]').val();
                if(inputVal.length==0){
                    $('#inventory').css("display","none");
                }else{
                    $('#inventory').css("display","block");
                }
            });

            $( ".datepicker" ).datepicker({
                minDate: new Date()
            });

            $(".datepicker").datepicker().datepicker("setDate", new Date());


            $('.spinner').spinner({
                min: 0,
                max: 200,
                step: 1
            });

            $('.tour-spinner').spinner({
                min: 0,
                max: 150,
                step: 1
            });

            $('.timepicker').timepicker({
                timeFormat: 'H:mm',
                interval: 60,
                minTime: '8',
                maxTime: '19:00',
                defaultTime: '11',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            $( "#datepicker" ).value = new Date();


            $( ".tour-spinner" ).change(function () {
                console.log("change");
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#rent_barcode').change(function(){

                var barcode = $("#rent_barcode").val();
                console.log("scan: "+barcode);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    cache: false,
                    url: '/bigbike/agent/barcode-scan',
                    data: "barcode=" + barcode,
                    success: function(data) {
                        // swal(data);
                        // console.log(data['id']);
                        console.log(data['url']);
                        if(data['type']=='error'){
                            swal(data['response']);
                        } else if(data['type']=='reservation' || data['type']=='return'){
                            window.location.replace(data['url']);
                        }

                    }
                });
            });
        });

        var bikes = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike","#electric_bike","#elliptigo","#tricycle","#trailer_bike","#electric_hand","#snow", "#basket_bike", "#seat_bike","#kid_trailer"];
        var bikes_arr = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike","#electric_bike","#elliptigo","#tricycle","#trailer_bike"];
        // var bikes_app_arr = ["#adult_bike", "#child_bike", "#tandem_bike", "#road_bike", "#mountain_bike"];

        var totalsum;
        var total_bikeNum;
        var tax =1.08875;
        var numOrPercent = false;


        function calculate(){
            var s = 0;
            // console.log("value: "+$('.rent_duration').val());
            // if($('.rent_duration').val()=='1 hour'){
            //     s = 0;
            // }else if($('.rent_duration').val()=='2 hours'){
            //     s=1;
            // }else if($('.rent_duration').val()=='3 hours'){
            //     s=2;
            // }else if($('.rent_duration').val()=='5 hours'){
            //     s=3;
            // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
            //     s=4;
            // }else if($('.rent_duration').val()=='24 hours'){
            //     s=5;
            // }
            var form = document.getElementById("payment-form");
            if(form.elements["rent_duration"].value=='1 hour'){
                s = 0;
            }else if(form.elements["rent_duration"].value=='2 hours'){
                s=1;
            }else if(form.elements["rent_duration"].value=='3 hours'){
                s=2;
            }else if(form.elements["rent_duration"].value=='5 hours'){
                s=3;
            }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
                s=4;
            }else if(form.elements["rent_duration"].value=='24 hours'){

                s=5;
                $('#tandem_bike').val(0);
                $('#road_bike').val(0);
                $('#mountain_bike').val(0);
                $('#electric_bike').val(0);
                $('#elliptigo').val(0);
                $('#tricycle').val(0);
                $('#electric_hand').val(0);
                $('#snow').val(0);


                // $('#trailer_bike').val(0);
                // $('#seat_bike').val(0);
                // $('#basket_bike').val(0);

            }
            //console.log("#"+s+'1');
            var total = 0;
            var bikeNum = 0;
            var doubleBikeNum = 0;
            for(i=1;i<=bikes_arr.length;i++){
                total += parseInt(($(bikes[i-1]).val().trim().length==0? 0:$(bikes[i-1]).val())*$("#"+s+i).val());
                // bikeNum += parseInt($(bikes[i-1]).val());
                console.log("PP: "+bikes[i-1]+", "+$("#"+s+i).val());
            }
            console.log("total: "+total);
//            var oldTotal = total;

            for(i=1;i<=bikes_arr.length-1;i++){
                bikeNum += parseInt(($(bikes[i-1]).val().trim().length==0? 0:$(bikes[i-1]).val()));
                console.log("bike num: "+bikeNum);
            }

            for(i=3;i<8;i++){
                doubleBikeNum += parseInt(($(bikes[i-1]).val().trim().length==0? 0:$(bikes[i-1]).val()));
            }

            if($('#dropoff').prop('checked')==true){
                total += (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val()))*5;
            }

            //check membership
            if($('#member_guest_checkbox').prop('checked')==true){
                if($('#member_type').val().length>=1){
                    total = (total*0.5).toFixed(2);
                }
            }else if($('#member_checkbox').prop('checked')==true){
                if(bikeNum>1){
                    //only one bike
//                    $('#adult_bike_label').notify("Membership only Applies for 1 Bike", {position: "top right"});
                    swal("Membership only Applies for 1 Bike");
                }

                if($('#member_type').val()=="Month Pass/$45" || $('#member_type').val()=="Annual Pass/$129"){
                    total = 3;
                }else if($('#member_type').val()=="Day Pass/$5"){

                }
            }else if($('#coupon_bike').prop('checked')==true){
                total = 0;
                if($('#dropoff').prop('checked')==true){
                    total += bikeNum*5;
                }

                if($('#insurance').prop('checked')==true){
                    total += (bikeNum-doubleBikeNum)*2 + 498*parseInt($('#electric_bike').val());
                    total += doubleBikeNum*4;
                }
                // total += parseFloat($('#basket_bike').val());
            }


            for(i=9;i<=bikes.length;i++){
                total += parseInt(($(bikes[i-1]).val().trim().length==0? 0:$(bikes[i-1]).val())*$("#"+s+i).val());
            }


            if($('#coupon_bike').prop('checked')==false){
                if($('#insurance').prop('checked')==true){
                    total += (bikeNum-doubleBikeNum)*2 + 498*parseInt($('#electric_bike').val());
                    total += doubleBikeNum*4;
                }
            }

            // console.log("after cal, now total: "+total+" ,original: "+originalPriceBefore);
            total -= originalPriceBefore;
            console.log("total: "+total);
            totalsum = (total*tax).toFixed(2);

            // if($('#rent_adjust').val().length>=1){
            //     total = parseFloat($('#rent_adjust').val());
            //
            //     if($('#dropoff').prop('checked')==true){
            //         total += bikeNum*5;
            //     }
            //
            //     if($('#insurance').prop('checked')==true){
            //         total += (bikeNum-doubleBikeNum)*2;
            //         total += doubleBikeNum*4;
            //     }
            //
            //     total += parseFloat($('#basket_bike').val());
            //
            // }
            // // if($('#rent_discount').val().trim().length>=1){
            // //     total = total*(1-parseFloat($('#rent_discount').val())*0.01);
            // //     $('#rent_adjust').val(total.toFixed(2));
            // //     if($('#dropoff').prop('checked')==true){
            // //         total += bikeNum*5;
            // //     }
            // //
            // //     if($('#insurance').prop('checked')==true){
            // //         total += (bikeNum-doubleBikeNum)*2;
            // //         total += doubleBikeNum*4;
            // //     }
            // //
            // //     total += parseFloat($('#basket_bike').val());
            // //
            // // }
            if(numOrPercent) {
                if ($('#rent_adjust').val().length >= 1) {
                    console.log("originalPriceBefore: "+ originalPriceBefore.trim().length);
                    if(originalPriceBefore.trim().length==0) {

                        total = parseFloat($('#rent_adjust').val());

                        if ($('#dropoff').prop('checked') == true) {
                            total += (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())) * 5;
                        }

                        if ($('#insurance').prop('checked') == true) {
                            total += (bikeNum - doubleBikeNum) * 2 + 498*parseInt($('#electric_bike').val());
                            total += doubleBikeNum * 4;
                        }

                        total += parseFloat($('#basket_bike').val());
                    }else{
                        total = parseFloat($('#rent_adjust').val());
//                        console.log("oldTotal: "+oldTotal);
//                        $('#rent_discount').val((100*total/oldTotal).toFixed(2));
                        if ($('#dropoff').prop('checked') == true) {
                            total += (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())-originalTotalBikes) * 5 ;
                        }

                        if ($('#insurance').prop('checked') == true) {
                            var originalDoubleBike = parseInt(originalTandem)+parseInt(originalRoad)+parseInt(originalMountain);
                            total += (bikeNum - doubleBikeNum - originalTotalBikes+originalDoubleBike) * 2 + 498*parseInt($('#electric_bike').val());
                            total += (doubleBikeNum-originalDoubleBike) * 4;
                        }

                        total += parseFloat($('#basket_bike').val())-parseInt(originalBasket);
                    }
                }
            }

            if(!numOrPercent) {
                if ($('#rent_discount').val().trim().length >= 1) {
                    console.log("originalPriceBefore: "+ originalPriceBefore.trim().length);
                    if(originalPriceBefore.trim().length==0){
                        if ($('#dropoff').prop('checked') == true) {
                            total -= (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())) * 5;
                        }

                        if ($('#insurance').prop('checked') == true) {
                            total -= (bikeNum  - doubleBikeNum) * 2;
                            total -= doubleBikeNum * 4;
                        }

                        total -= parseFloat($('#basket_bike').val());


                        total = total * (1 - parseFloat($('#rent_discount').val()) * 0.01);

                        $('#rent_adjust').val(total.toFixed(2));
                        if ($('#dropoff').prop('checked') == true) {
                            total += (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())) * 5;
                        }

                        if ($('#insurance').prop('checked') == true) {
                            total += (bikeNum - doubleBikeNum) * 2 + 498*parseInt($('#electric_bike').val());
                            total += doubleBikeNum * 4;
                        }

                        total += parseFloat($('#basket_bike').val());
                    }else {

                        var diffBastket = parseInt($('#basket_bike').val())-originalBasket;
                        var originalDoubleBike = parseInt(originalTandem)+parseInt(originalRoad)+parseInt(originalMountain);

                        if ($('#insurance').prop('checked') == true) {
                            total -= (bikeNum - doubleBikeNum - originalTotalBikes+originalDoubleBike) * 2 + 498*parseInt($('#electric_bike').val());
                            total -= (doubleBikeNum-originalDoubleBike) * 4;
                        }

                        if ($('#dropoff').prop('checked') == true) {
                            total -= (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())-originalTotalBikes) * 5;
                        }

                        total -= diffBastket;

                        total = total * (1 - parseFloat($('#rent_discount').val()) * 0.01);
                        $('#rent_adjust').val(total.toFixed(2));

                        if ($('#insurance').prop('checked') == true) {
                            total += (bikeNum - doubleBikeNum - originalTotalBikes+originalDoubleBike) * 2 + 498*parseInt($('#electric_bike').val());
                            total += (doubleBikeNum-originalDoubleBike) * 4;
                        }

                        if ($('#dropoff').prop('checked') == true) {
                            total += (bikeNum+parseInt($("#snow").val())+parseInt($("#electric_hand").val())-originalTotalBikes) * 5;
                        }
                        total += diffBastket;

                    }
                }
            }

            total_bikeNum = bikeNum;

            $("#rent_total_label").val((total*1).toFixed(2)).css('color','green');
            // $("#rent_tips_label").val(Math.floor(total*30)/100).css('color','green');

            // $("#rent_tips_label").val((total*0.2995).toFixed(2)).css('color','green');
            if($('#coupon_bike').prop('checked')==true){
                $("#rent_tax").val((0).toFixed(2)).css('color','green');
                $("#rent_total_after_tax").val((total*1).toFixed(2)).css('color','green');

            }else{
                $("#rent_tax").val((total*0.08875).toFixed(2)).css('color','green');
                $("#rent_total_after_tax").val((total*tax).toFixed(2)).css('color','green');

            }

            if($('#rent_deposit').val()){
                var deposit = parseFloat($('#rent_deposit').val());
            }else{
                var deposit = 0;
            }
            $('#rent_total_after_tax_deposit').val((parseFloat($('#rent_total_after_tax').val())+deposit+parseFloat(inv_price)).toFixed(2)).css('color','green');

            if(total<0){
                $('#rent_rendered').val(0);
                $('#rent_change').val((total*tax*(-1)).toFixed(2));
            }else{

                // $('#rent_rendered').val(0);
                if(!$('#rent_rendered').val()){
                    $('#rent_change').val(null);
                }else{

                    if($('#deposit_cc_checkbox').prop('checked')==true){
                        console.log('cc checked');
                        var rent_rendered = parseFloat($('#rent_rendered').val());
                        var rent_total_after_tax = parseFloat($('#rent_total_after_tax').val());
                        if(rent_rendered>=rent_total_after_tax){
                            $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                        }else{
                            $('#rent_change').val(null);
                        }
                    }
                    else{
                        var rent_rendered = parseFloat($('#rent_rendered').val());
                        var rent_total_after_tax = parseFloat($('#rent_total_after_tax_deposit').val());
                        if(rent_rendered>=rent_total_after_tax){
                            $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                        }else{
                            $('#rent_change').val(null);
                        }
                    }

                }
            }
//            console.log('total::: '+total);


            // return total;

        }

        //agent-rent-backup.js
        function getPrice(cur,name){

            if(cur.id=='53'||cur.id=='54'||cur.id=='55'||cur.id=='56'||cur.id=='57'||cur.id=='58'){
//                $(cur).notify("No service", {position:"right middle",autoHideDelay:2000});
                //console.log("invalid");
                return false;
            }

            console.log("click: " +cur.id.charAt(0));
            var c = cur.id.charAt(0);
            var curRow = '0';
            if($('#rent_duration').val()=='1 hour'){
                curRow = '0';
            }else if($('#rent_duration').val()=='2 hours'){
                curRow='1';
            }else if($('#rent_duration').val()=='3 hours'){
                curRow='2';
            }else if($('#rent_duration').val()=='5 hours'){
                curRow='3';
            }else if($('#rent_duration').val()=='All Day (8am-8pm)'){
                curRow='4';
            }else if($('#rent_duration').val()=='24 hours'){
                curRow='5';
            }

            //check if the same row
            if(c!=curRow){
                //curRow = c;
//                $(cur).notify("Please choose hours first", {position:"right middle",autoHideDelay:2000});
                //console.log('not equal');
                return false;
            }

            for(j=0;j<=5;j++) {
                for (i = 0; i <= bikes.length; i++) {
                    var tmp = '#' + j + i;
                    //console.log(tmp);
                    $(tmp).css('background-color', 'white');
                }
            }

            for(i=0;i<=bikes.length;i++){
                var tmp = '#'+c+i;
                //console.log(tmp);
                $(tmp).css('background-color','#rgb(232, 232, 232);');
            }
            //update this color
            //update bike nums
            var nam = name+'_bike';
            if($(nam).val()<=19){
                $(nam).val(parseInt($(nam).val())+1);
            }

            calculate();
        }


        $(function() {

            $( ".field" ).keyup(function() {
                //console.log( "Handler for .change() called." );
                calculate();
            });

            $('.ui-spinner-button').click(function () {
                //console.log("Handler for .change() called.");
                calculate();
            });

            $('#basket').click(function () {
                calculate();
            });
            $('#insurance').click(function () {
                calculate();
            });
            $('#dropoff').click(function () {
                calculate();
            });

            for(i=0;i<=bikes.length;i++){
                var tmp = '#'+0+i;
                //console.log(tmp);
                $(tmp).css('background-color','#rgb(232, 232, 232);');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }

            $('.duration-title').click(function () {
                //console.log("check duration.");
                $('#rent_duration').val(this.value);
                // console.log("now: "+$('#rent_duration').val());
                //console.log('id: '+this.id);
                var c = this.id.charAt(0);
                for(j=0;j<=5;j++) {
                    for (i = 0; i <= bikes.length; i++) {
                        var tmp = '#' + j + i;
                        //console.log(tmp);
                        $(tmp).css('background-color', 'white');
                        //$(tmp).notify("Please input valid name", {position:"right middle"});
                    }
                }
                for(i=0;i<=bikes.length;i++){
                    var tmp = '#'+c+i;
                    //console.log(tmp);
                    $(tmp).css('background-color','#rgb(232, 232, 232)');
                    //$(tmp).notify("Please input valid name", {position:"right middle"});
                }
                calculate();
            });

            $('.rent_duration').change(function() {

                console.log( "Handler for .change() called." );
                for(j=0;j<=5;j++) {
                    for (i = 0; i <= bikes.length; i++) {
                        var tmp = '#' + j + i;
                        //console.log(tmp);
                        $(tmp).css('background-color', 'white');
                        //$(tmp).notify("Please input valid name", {position:"right middle"});
                    }
                }
                var curRow = '0';
                var form = document.getElementById("payment-form");
                // console.log(form.elements["rent_duration"].value);
                // console.log("val: "+$('[name="rent_duration"]').val());

                if(form.elements["rent_duration"].value=='1 hour'){
                    curRow = '0';
                }else if(form.elements["rent_duration"].value=='2 hours'){
                    curRow='1';
                }else if(form.elements["rent_duration"].value=='3 hours'){
                    curRow='2';
                }else if(form.elements["rent_duration"].value=='5 hours'){
                    curRow='3';
                }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
                    curRow='4';
                }else if(form.elements["rent_duration"].value=='24 hours'){
                    curRow='5';
                    $('#tandem_bike').val(0);
                    $('#road_bike').val(0);
                    $('#mountain_bike').val(0);
                    // $('#trailer_bike').val(0);
                    // $('#seat_bike').val(0);
                    // $('#basket_bike').val(0);
                }

                // if($('.rent_duration').val()=='1 hour'){
                //     curRow = '0';
                // }else if($('.rent_duration').val()=='2 hours'){
                //     curRow='1';
                // }else if($('.rent_duration').val()=='3 hours'){
                //     curRow='2';
                // }else if($('.rent_duration').val()=='5 hours'){
                //     curRow='3';
                // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
                //     curRow='4';
                // }else if($('.rent_duration').val()=='24 hours'){
                //     curRow='5';
                //     $('#tandem_bike').val(0);
                //     $('#road_bike').val(0);
                //     $('#mountain_bike').val(0);
                //     $('#trailer_bike').val(0);
                //     $('#seat_bike').val(0);
                //     $('#basket_bike').val(0);
                //
                // }

                for(i=0;i<=bikes.length;i++){
                    var tmp = '#'+curRow+i;
                    //console.log(tmp);
                    $(tmp).css('background-color','#rgb(232, 232, 232)');
                    //$(tmp).notify("Please input valid name", {position:"right middle"});
                }
                calculate();
            });


            $("#cash_paid_label").keyup(function() {
                var customer = parseFloat($( "#cash_paid_label" ).val());
                var agent = parseFloat($( "#tips_label" ).val());
                if(customer>=agent){
                    console.log("true");
                    $("#rent_cash_change_label").val((customer-agent).toFixed(2)).css('color','green');
                }else{
                    console.log("false");
                }
            });


            goBack();


            $('#rent_adjust').keyup(function() {
                numOrPercent = true;

                if(!$('#rent_adjust').val()){
                    $('#rent_discount').val(null);
                }else{
                    //var total = calculate();
                    // console.log("adjust: "+$('#rent_adjust').val());
                    // console.log("sum: "+totalsum);

                    var beforeTaxSum = totalsum/tax;
                    if(totalsum!=0){
                        $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0));
                    }
                    // $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0)+'%');
                    // $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0));
                    if(parseFloat($('#rent_discount').val())==0){
                        $('#rent_discount').val(0);
                    }
                    $('#rent_total_label').val($('#rent_adjust').val());
                    // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
                    // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
                }
                calculate();
            });


            // $('#rent_adjust').keyup(function() {
            //
            //     if(!$('#rent_adjust').val()){
            //         $('#rent_discount').val(null);
            //     }else{
            //         //var total = calculate();
            //         console.log("adjust: "+$('#rent_adjust').val());
            //         console.log("sum: "+totalsum);
            //
            //         var beforeTaxSum = totalsum/tax;
            //
            //         // $('#rent_discount').val((100*(parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(0)+'%');
            //         $('#rent_discount').val((100*(1-parseFloat($('#rent_adjust').val())/parseFloat(beforeTaxSum))).toFixed(2));
            //         if(parseFloat($('#rent_discount').val())==0){
            //             $('#rent_discount').val(0);
            //         }
            //         $('#rent_total_label').val($('#rent_adjust').val());
            //         // $('#rent_tax').val((parseFloat($('#rent_adjust').val())*(tax-1)).toFixed(2));
            //         // $("#rent_total_after_tax").val((parseFloat($('#rent_adjust').val()*tax).toFixed(2))).css('color','green');
            //     }
            //     calculate();
            // });


            $('#rent_discount').keyup(function() {
                numOrPercent = false;

                if(!$('#rent_discount').val()){
                    // calculate();
                    $('#rent_adjust').val(null);
                }else{
                    //var total = calculate();
                    var bikeNum = 0;
                    var doubleBikeNum = 0;
                    for(i=1;i<=bikes_arr.length-1;i++){
                        bikeNum += parseInt($(bikes[i-1]).val());
                    }

                    for(i=3;i<6;i++){
                        doubleBikeNum += parseInt($(bikes[i-1]).val());
                    }

                    var tmpTotalsum = totalsum/tax;

                    if($('#dropoff').prop('checked')==true){
                        tmpTotalsum -= bikeNum*5;
                    }

                    if($('#insurance').prop('checked')==true){
                        tmpTotalsum -= (bikeNum-doubleBikeNum)*2;
                        tmpTotalsum -= doubleBikeNum*4;
                    }

                    tmpTotalsum -= parseFloat($('#basket_bike').val());



                    var beforeTaxSum = tmpTotalsum;

                    //console.log('rate: '+parseFloat($('#rent_discount').val())/100);

                    $('#rent_adjust').val(((1-(parseFloat($('#rent_discount').val())/100))*parseFloat(beforeTaxSum)).toFixed(2));
                    // $('#rent_total_label').val($('#rent_adjust').val());
                }
                calculate();
            });

            $( "#rent_rendered" ).focus(function() {
                if($('#rent_rendered').val()=="0"){
                    $('#rent_rendered').val(null);
                }
            });

            $('#rent_rendered').keyup(function() {

                if(!$('#rent_rendered').val()){
                    $('#rent_change').val(null);
                }else{

                    if($('#deposit_cc_checkbox').prop('checked')==true){
                        console.log('cc checked');
                        var rent_rendered = parseFloat($('#rent_rendered').val());
                        var rent_total_after_tax = parseFloat($('#rent_total_after_tax').val());
                        if(rent_rendered>=rent_total_after_tax){
                            $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                        }else{
                            $('#rent_change').val(null);
                        }
                    }
                    else{
                        var rent_rendered = parseFloat($('#rent_rendered').val());
                        var rent_total_after_tax = parseFloat($('#rent_total_after_tax_deposit').val());
                        if(rent_rendered>=rent_total_after_tax){
                            $('#rent_change').val((rent_rendered-rent_total_after_tax).toFixed(2));
                        }else{
                            $('#rent_change').val(null);
                        }
                    }

                }
            });

            $( "#rent_rendered" ).focus(function() {
                if($('#rent_rendered').val()=='0'){
                    $('#rent_rendered').val(null);
                }
            });

            $( "#rent_rendered" ).blur(function() {
                if($('#rent_rendered').val()==null || $('#rent_rendered').val().length==0){
                    $('#rent_rendered').val(0);
                }
            });


            $("#member_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    //console.log("Checked Box Selected");
                    $('#member_guest_checkbox').prop('checked',false);
                    $('.member_checkbox_label').css('display','inline-block');
                } else {
                    //console.log("Checked Box deselect");
                    $('.member_checkbox_label').css('display','none');
                }
                calculate();
            });

            $("#member_guest_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#member_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                    $('.member_checkbox_label').css('display','inline-block');
                    $('#coupon_bike').prop('checked',false);
                } else {
                    //console.log("Checked Box deselect");
                    $('.member_checkbox_label').css('display','none');
                }
                calculate();
            });


            $("#coupon_bike").change(function() {
                if($(this).prop('checked') == true) {
                    // $('#coupon_bike').prop('checked',true);
                    $('#member_checkbox').prop('checked',false);
                    $('#member_guest_checkbox').prop('checked',false);
                    $('.member_checkbox_label').css('display','none');

                    //console.log("Checked Box Selected");
                } else {
                    // $('#coupon_bike').prop('checked',false);
                }
                calculate();
            });


            $( "#rent_agent" ).autocomplete({
                source: agentList
            });

            $( "#rent_agent" ).focus(function() {
                if(this.value == ' ') {
                    this.value = '';
                }
            });

            $( ".field" ).focus(function() {
                if(this.value == ' ') {
                    this.value = '';
                }
            });

            $('#rent_deposit').keyup(function() {
                console.log('click');
                if(!$('#rent_deposit').val()){
                    calculate();
                    $('#rent_deposit').val(null);
                }else{
                    //var total = calculate();
                    calculate();
                }
            });

            $("#deposit_cc_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#deposit_cash_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                }
            });

            $("#deposit_cash_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#deposit_cc_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                }
                calculate();
                console.log('cash_checkbox');
            });

            $('[name="cash"]').click( function() {
                $("form").attr("target", "_self");

            });

            $( ".bike" ).focus(function() {
                if($(this).val()=='0'){
                    $(this).val(null);
                }
            });

            $("#rent_agent").blur(function(){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '/bigbike/agent/rent/addAgent',
                    data: {'rent_agent': $('#rent_agent').val()},
                    type: 'POST',
                    datatype: 'JSON',
                    success: function (data) {
                        if(data!='exists'){
                            swal(data);
                        }
                    },
                    error: function (data) {
                        swal(data);
                        // $('#rent_agent').val('No such member!');
                    }
                });
            });



            $("#deposit_cc_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#deposit_cash_checkbox').prop('checked',false);
                    $('#deposit_id_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                }
                calculate();
            });

            $("#deposit_cash_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#deposit_cc_checkbox').prop('checked',false);
                    $('#deposit_id_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                }
                calculate();
                console.log('cash_checkbox');
            });

            $("#deposit_id_checkbox").change(function() {
                if($(this).prop('checked') == true) {
                    $('#deposit_cash_checkbox').prop('checked',false);
                    $('#deposit_cc_checkbox').prop('checked',false);
                    //console.log("Checked Box Selected");
                }
                $("#rent_deposit").val(0);
                calculate();
            });


            $( "#rent_deposit" ).focus(function() {
                if(parseInt($("#rent_deposit").val().trim())==0){
                    $("#rent_deposit").val(null);
                }
            });

        });

        function goBack(){
            //check go back this page
            var curRow = 0;
            // if($('.rent_duration').val()=='1 hour'){
            //     curRow = 0;
            // }else if($('.rent_duration').val()=='2 hours'){
            //     curRow=1;
            // }else if($('.rent_duration').val()=='3 hours'){
            //     curRow=2;
            // }else if($('.rent_duration').val()=='5 hours'){
            //     curRow=3;
            // }else if($('.rent_duration').val()=='All Day (8am-9pm)'){
            //     curRow=4;
            // }else if($('.rent_duration').val()=='24 hours'){
            //     curRow=5;
            // }
            var form = document.getElementById("payment-form");
            if(form.elements["rent_duration"].value=='1 hour'){
                curRow = 0;
            }else if(form.elements["rent_duration"].value=='2 hours'){
                curRow=1;
            }else if(form.elements["rent_duration"].value=='3 hours'){
                curRow=2;
            }else if(form.elements["rent_duration"].value=='5 hours'){
                curRow=3;
            }else if(form.elements["rent_duration"].value=='All Day (8am-8pm)'){
                curRow=4;
            }else if(form.elements["rent_duration"].value=='24 hours'){
                curRow=5;
            }

            for(j=0;j<=5;j++) {
                for (i = 0; i <= bikes.length; i++) {
                    var tmp = '#' + j + i;
                    //console.log(tmp);
                    $(tmp).css('background-color', 'white');
                    //$(tmp).notify("Please input valid name", {position:"right middle"});
                }
            }

            for(i=0;i<=bikes.length;i++){
                var tmp = '#'+curRow+i;
                //console.log(tmp);
                $(tmp).css('background-color','#rgb(232, 232, 232)');
                //$(tmp).notify("Please input valid name", {position:"right middle"});
            }
        }


        function ccSubmitCheck(){
            if(!checkName()) return false;
            if(!checkBikeNum()) return false;
            if(!checkValid()) return false;
            if(!checkMembership()) return false;
            if(!checkMembershipExpire()) return false;
            if(!checkDeposit())return false;
            if(!checkAdjust()){
                return false;
            }


            removeDisable()


//            var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;
//
//            if(!$('#rent_total_label').val().trim().match(floatRegex)){
////                $('#rent_total_label').notify("Please input valid price", {position: "right middle"});
//                swal("Please input valid price");
//                return false;
//            }else{
//                return true;
//            }
            return true;
        }


        function cashSubmitCheck(){

            $('#cashBtn').attr("disabled", true);
//            $('body').css('cursor', 'wait');


            if(!checkName()) {
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            if(!checkBikeNum()) {
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            if(!checkValid()) {
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            if(!checkMembership()) {
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            // if(!checkRenderedCash()) return false;
            if(!checkMembershipExpire()) {
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            if(!checkDeposit()){
                $('#cashBtn').attr("disabled", false);
//                $('body').css('cursor', 'default');
                return false;
            }
            // if(!checkDepositCCwithCash()) return false;

            if(!checkAdjust()){
                $('#cashBtn').attr("disabled", false);

                return false;
            }


            removeDisable()

            var floatRegex = /^(0\.[1-9]|[1-9][0-9]{0,2}(\.[0-9]{0,2})?)$/;

            $('#cashBtn').attr("disabled", true);
//            console.log('disable');

//            $('body').css('cursor', 'wait');
            var form = document.getElementById('payment-form');
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cash'; // 'the key/name of the attribute/field that is sent to the server
            input.value = 'Cash';
            form.appendChild(input);

            form.submit();

            return true;
        }

        function checkName(){
            if($('#rent_customer').val().trim().length<=0){
//                $('#rent_customer').notify("Please input valid name", {position:"bottom center"});
                swal('Please input valid name');
                return false;
            }else if($('#rent_customer_last').val().trim().length<=0){
//                $('#rent_customer_last').notify("Please input valid name", {position:"bottom center"});
                swal('Please input valid name');
                return false;
            }
            else{
                return true;
            }
        }

        function checkBikeNum(){

            if($('#adult_bike').val()==0 && $('#child_bike').val()==0 && $('#tandem_bike').val()==0 && $('#road_bike').val()==0 &&
                $('#mountain_bike').val()==0 && $('#electric_bike').val()==0 && $('#elliptigo').val()==0 && $('#tricycle').val()==0 && $('#electric_hand').val()==0 && $('#snow').val()==0){
//                $('#adult_bike_label').notify("Bike number can't be 0", {position:"top center"});
                swal("Bike number can't be 0");

                return false;
            }else{
                return true;
            }

        }

        function checkValid(){
            var numReg = /^\d+$/;
            var timeReg = /([01]\d|2[0-3]):([0-5]\d)/;
            var dateReg = /^\d{2}[/]\d{2}[/]\d{4}$/;
//            var floatReg = /^\s*-?[1-9]\d*(\.\d{1,2})?\s*$/;

//            if(!$('#adult_bike').val().trim().match(numReg)||!$('#child_bike').val().trim().match(numReg)||!$('#tandem_bike').val().trim().match(numReg)
//                ||!$('#road_bike').val().trim().match(numReg) ||!$('#mountain_bike').val().trim().match(numReg)||!$('#trailer_bike').val().trim().match(numReg)
//                ||!$('#seat_bike').val().trim().match(numReg)){
//
////                $('#adult_bike').notify("Please input valid number", {position: "top right"});
//                swal("Please input valid number");
//                // console.log('cash1');
//
//                return false;
//            }
//            else
            if(!$('#rent_date').val().trim().match(dateReg)){
                // console.log('cash2');
//                $('#rent_date_label').notify("Please input valid date", {position: "top right"});
                swal("Please input valid date");
                return false;
            }

//            else if($('#rent_adjust').val().trim().length!=0 &&!$('#rent_adjust').val().trim().match(floatReg)){
//
//                swal("Please input valid number");
//                return false;
//            }
            else{
                return true;
            }
        }
        // $(function() {
        //     $('.btn-default').value('#00').css('bacgkound-color','white');
        //
        //
        // });

        function clearMember(){
            $('#member_type').val(null);
            $('#rent_membership_expire').val(null);
            if($('#member_checkbox').prop('checked')==true) {
                $('#rent_customer').val(null);
                $('#rent_customer_last').val(null);
                $('#rent_email').val(null);
            }
        }

        function memberSearch(){
            console.log('search');
            clearMember();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/bigbike/agent/rent/membership',
                data: {'rent_membership': $('#rent_membership').val()},
                type: 'POST',
                datatype: 'JSON',
                success: function (data) {

                    var data2 = JSON.parse(data)

                    if(data2.order_completed=='0'){
//                        $('#member_type').notify("Membership Fee hasn't paid yet", {position: "top right"});
                        swal("Membership Fee hasn't paid yet");
                    }

                    console.log("response: "+typeof(data2.customer_name));

                    if($('#member_checkbox').prop('checked') == true) {
                        $('#rent_customer').val(data2.customer_name);
                        $('#rent_customer_last').val(data2.customer_lastname);
                        $('#rent_email').val(data2.customer_email);
                    }

                    $('#rent_membership_expire').val(data2.enddate);
                    $('#member_type').val(data2.member_type);

                    var dateArr = (data2.enddate).split('-');
                    var enddate = new Date(dateArr[0],dateArr[1]-1,dateArr[2]);
                    enddate.setDate(enddate.getDate()+1);
                    // console.log('end: '+enddate);
                    var today = new Date();
                    // console.log('now: '+today);

                    if (enddate<today) {
                        //console.log('expire');
//                        $('#rent_membership_expire').notify("Membership has been expired", {position: "top right"});
                        swal("Membership has been expired");
                    }

                    calculate();
                },
                error: function (data) {
                    $('#rent_customer').val('No such member!');
                }
            });
            return false;
        }

        function checkMembership() {
            if($('#member_checkbox').prop('checked')==true && total_bikeNum >1){
//                $('#adult_bike_label').notify("Membership only Applies for 1 Bike", {position: "top right"});
                swal("Membership only Applies for 1 Bike");
                return false;
            }

            return true;
        }

        function checkRenderedCash(){
            // if($('#rent_rendered').val().length<=0 ){
            //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
            //     return false;
            // }

            if($('#rent_adjust').val().length>0){
                // if(parseFloat($('#rent_adjust').val())>parseFloat($('#rent_rendered').val())){
                //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                //     console.log('THIIIS');
                //     return false; ALEX
                // }
            }else{
                if($('#rent_deposit').val()){
                    //cash
                    if($('#deposit_cash_checkbox').prop('checked')==true){
                        // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
                        //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                        //     console.log('THIS');
                        //     // console.log('after_tax_deposit not enough');
                        //     return false;ALEX
                        // }
                    }else if($('#deposit_cc_checkbox').prop('checked')==true){
                        //credit
                        if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
//                            $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                            swal("Cash is not enough");

                            // console.log('after tax not enough');
                            return false;
                        }
                    }
                }else{
                    // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
                    //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                    //     // console.log('after_tax_deposit not enough');ALEX
                    //     return false;
                    // }
                }
                // if(parseFloat($('#rent_total_after_tax_deposit').val())>parseFloat($('#rent_rendered').val())){
                //     $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                //     console.log('not enough');
                //     return false;
                // }
                // console.log('enough');
            }
            return true;
        }

        function checkDeposit(){
            if($('#rent_deposit').val().length<=0){
//                $('#rent_deposit').notify("Deposit", {position: "top right"});
                swal("Deposit");
                return false;
            }
            return true;
        }

        function checkMembershipExpire(){

            if($('#member_checkbox').prop('checked')==true || $('#member_guest_checkbox').prop('checked')==true){
                var today = new Date();
                var enddate = new Date($('#rent_membership_expire').val());
                enddate.setDate(enddate.getDate()+1);
                console.log("end: "+enddate);
                if (enddate<today) {
                    //console.log('expire');
//                    $('#rent_membership_expire').notify("Membership has been expired", {position: "top right"});
                    swal("Membership has been expired");
                    return false;

                }
            }
            return true;
        }

        function removeDisable(){
            // console.log('change');
            $("#member_type").prop("disabled",false);

        }

        function checkDeposit() {
            // console.log('checkDeposit cash: '+$('#rent_deposit').val());

            if($('#rent_deposit').val().trim()){
                // console.log('checkDeposit');
                if(!($('#deposit_cc_checkbox').prop('checked')==true) && !($('#deposit_cash_checkbox').prop('checked')==true)){
//                    $('#rent_deposit').notify("Select a payment method", {position: "top right"});
                    swal("Select a payment method");
                    return false;
                }
            }
            return true;
        }

        function checkAdjust() {
            // console.log('checkDeposit cash: '+$('#rent_deposit').val());

            var numReg = /^\s*-?[0-9]\d*(\.\d{1,2})?\s*$/;
            if($('#rent_discount').val().trim()){
                // console.log('checkDeposit');
                if(!($('#rent_discount').val().trim().match(numReg))){
//                    $('#rent_deposit').notify("Select a payment method", {position: "top right"});
                    swal("Discount should be a plain number ");
                    return false;
                }
            }
            return true;
        }

        function checkDepositCCwithCash(){
            if($('#rent_deposit').val() && $('#deposit_cc_checkbox').prop('checked')==true){
                if($('#rent_rendered').val().length<=0){
                    $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                    console.log('THIS ONE');

                    return false;
                }

                if($('#rent_adjust').val().length>0){
                    if(parseFloat($('#rent_adjust').val())>parseFloat($('#rent_rendered').val())){
                        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                        console.log('THIS TWO');
                        return false;
                    }
                }else{
                    if(parseFloat($('#rent_total_after_tax').val())>parseFloat($('#rent_rendered').val())){
                        $('#rent_rendered').notify("Cash is not enough", {position: "top right"});
                        console.log('not enough');
                        console.log('THIS 3');

                        return false;
                    }
                }
            }

            return false;
        }


        //agent-barcode.js
        $('#rent_barcode').change(function(){

            var barcode = $("#rent_barcode").val();
            console.log("scan: "+barcode);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                cache: false,
                url: '/bigbike/agent/barcode-scan',
                data: "barcode=" + barcode,
                success: function(data) {
                    // swal(data);
                    // console.log(data['id']);
                    console.log(data['url']);
                    if(data['type']=='error'){
                        swal(data['response']);
                    } else if(data['type']=='reservation' || data['type']=='return'){
                        window.location.replace(data['url']);
                    }

                }
            });
        });


    </script>

@endsection
