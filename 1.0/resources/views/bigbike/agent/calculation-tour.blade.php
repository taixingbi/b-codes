<div class="shak"><h4>Agent Info</h4>
    <label id="rent_adjust_label" >
        <span>Agent name</span><br>
        <input style="font-size:16px;font-weight: bold;text-transform: uppercase" name="tour_agent" id="tour_agent" value="@if(!empty($agent_tours_order)){{ $agent_tours_order['agent_name'] }}@endif @if(!empty($agent_tours_order_cc)){{ $agent_tours_order_cc['agent_name'] }}@endif" class=" is-empty" type="text" />
    </label>

    <label style="display: none;" id="rent_adjust_label" ><br>
        <span>Level</span><br />
        <input style="font-size:16px;font-weight: bold" name="tour_agent_level" id="tour_agent_level" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['agent_level'] }}  @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['agent_level'] }}  @endif" class="readonly is-empty" type="text" readonly/>
    </label><br>

    <label id="rent_adjust_label" style="display: none"><br>
        <span>ID</span><br />
        <input style="font-size:16px;font-weight: bold" name="tour_id" id="tour_id" value="@if(!empty($agent_tours_order)) {{ $agent_tours_order['id'] }} @else null @endif @if(!empty($agent_tours_order_cc)) {{ $agent_tours_order_cc['id'] }} @endif" class="readonly is-empty" type="hidden" readonly/>
    </label>
</div>

<div><h4 class="extra">Summary</h4></div>
<label id="" class="extra"><br>
    <span>Deposit </span><br>
    $<input style="font-size:16px;font-weight: bold" name="rent_deposit" id="rent_deposit" class=" is-empty" type="text" />
</label><br />
<label class="custom-control custom-checkbox">
    <input type="checkbox" name="deposit_cc_checkbox" id="deposit_cc_checkbox" class="custom-control-input" >
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description"> Credit Card </span>
</label>


<label class="custom-control shak custom-checkbox">
    <input type="checkbox" name="deposit_cash_checkbox" id="deposit_cash_checkbox" class="custom-control-input" >
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description"> Cash </span>
</label>

<label class="custom-control shak custom-checkbox">
    <input type="checkbox" name="deposit_id_checkbox" id="deposit_id_checkbox" class="custom-control-input" @if(empty($agent_rents_order['deposit'])) checked="checked"@endif>
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description"> ID </span>
</label><br>

<label id="rent_adjust_label" class="" ><br>
    <span>Adjusted Price</span><br>
    $<input style="font-size:16px;font-weight: bold" name="rent_adjust" id="rent_adjust" class=" is-empty" type="text"  />
</label>
<label id="" class=""><br>
    <span>Adjusted Discount</span><br>
    %<input style="font-size:16px;font-weight: bold" name="rent_discount" id="rent_discount" class=" is-empty" type="text" step=".01" />
</label><br>

@if(!empty($agent_tours_order))
    <label id="" ><br>
        <span>Previous Total Before Tax</span><br>
        $<input style="font-size:18px;font-weight: bold" name="rent_previous_total_before_tax" id="rent_previous_total_before_tax" value=" {{ $agent_tours_order['total_price_before_tax'] }} " class="readonly is-empty" type="text" readonly/>
    </label>
    <label id="" ><br>
        <span>Previous Total After Tax</span><br>
        $<input style="font-size:18px;font-weight: bold" name="rent_previous_total_after_tax" id="rent_previous_total_after_tax" value=" {{ $agent_tours_order['total_price_after_tax'] }} " class="readonly is-empty" type="text" readonly/>
    </label><br>
@endif

<label id="" ><br>
    <span>Total</span><br>
    $<input style="font-size:18px;font-weight: bold" name="rent_total_label" id="rent_total_label" class="readonly is-empty" type="text" value="@if($agent_tours_order_cc) {{ $agent_tours_order_cc['total_price_before_tax'] }} @else 0 @endif" placeholder="0" readonly/>
</label>
<label id="" ><br>
    <span>Tax</span><br>
    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_tax" id="rent_tax" class="readonly is-empty" type="text" value="@if($agent_tours_order_cc) {{ number_format($agent_tours_order_cc['total_price_before_tax']*0.08875,2) }} @else 0 @endif" placeholder="0" readonly/>
</label>
<label id="" ><br>
    <span>Total After Tax</span><br>
    $<input style="font-size:18px;font-weight: bold" name="rent_total_after_tax" id="rent_total_after_tax" class="readonly is-empty" type="text" value="@if($agent_tours_order_cc) {{ $agent_tours_order_cc['total_price_after_tax'] }} @else 0 @endif" placeholder="0" readonly/>
</label><br>
<label id="" ><br>
    <span>Total After Tax + Deposit</span><br>
    @php
        if(!empty($agent_tours_order_cc))
            $price = floatval($agent_tours_order_cc['total_price_after_tax'])+floatval($agent_tours_order_cc['deposit']);
        else
            $price = 0;
    @endphp
    $<input style="font-size:14px!important;font-weight: bold" name="rent_total_after_tax_deposit" id="rent_total_after_tax_deposit" class="readonly is-empty" type="text" value="{{$price}}" placeholder="0" readonly/>
</label><br>
<div >
    @if(Session::has('inv_cart') && Session::get("inv_cart")["price"]>0)
        <div class="text-center" style="float: left;margin-bottom: -33px;">

            <div id="main-text">
                <div class="text-center " >

                    @php
                        $cart = Session::get("inv_cart");
                    @endphp
                    <ul class="list-group">
                    @foreach($cart as $key => $value)
                        @if($key!="price" && $key!="firstname" && $key!="lastname")
                            <li class="list-group-item">Item: {{$value["title"]}}</li>
                            <li class="list-group-item">QTY: {{ $value["qty"] }}</li>
                        @endif
                    @endforeach
                    </ul>
                    @foreach($cart as $key => $value)

                        @if($key=="price")
                            <div>Inventory Total: <strong> {{ "$".$value }}</strong></div>
                        @endif
                    @endforeach
                    <br>
                </div>
            </div>
        </div>
    @else
        {{--<div>No Transaction!</div>--}}
    @endif
</div>

<div style="clear: both;"><label class="shak" id=""><br>
    <span>Cash Paid</span><br>
    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_rendered" id="rent_rendered" class=" is-empty" type="text" value="0" placeholder="0" />
</label>
<label class="shak" id="" ><br>
    <span>Change</span><br>
    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_change" id="rent_change" class="readonly is-empty" type="text" value="0" placeholder="0" readonly />
</label>
</div>
