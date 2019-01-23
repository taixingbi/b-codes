
{{--@if(Session::has('location') && (Session::get('location')!='Central Park West' && Session::get('location')!='Central Park South' && Session::get('location')!='Grand Army Plaza'))--}}
<div class="shak" @if(Session::has('location') && (Session::get('location')=='Central Park West' || Session::get('location')=='Central Park South' || Session::get('location')=='Grand Army Plaza') || Session::get('location')=='High Bridge Park' || Session::get('location')=='Riverside Park' || Session::get('location')=='East River Park')) style="display: none;"
    @endif><h4>Agent Info</h4>
    <label id="rent_adjust_label" >
        <span>Agent name</span><br>
        <input style="font-size:16px;font-weight: bold;text-transform: uppercase;" name="rent_agent" id="rent_agent" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['agent_name'] }}@endif @if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['agent_name'] }}@endif" class=" is-empty" type="text" />
    </label>
    <label style="display: none" id="rent_adjust_label" ><br>
        <span>Level</span><br />
        <input style="font-size:16px;font-weight: bold" name="rent_agent_level" id="rent_agent_level" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['agent_level'] }}@else 0 @endif" class="readonly is-empty" type="text" readonly/>
    </label><br>
    <label id="rent_adjust_label" class="" style="display: none"><br>
        <span>ID</span><br />
        <input style="font-size:16px;font-weight: bold" name="rent_id" id="rent_id" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['id'] }}@else null @endif @if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['id'] }}@else null @endif" class="readonly is-empty" type="hidden" readonly/>
    </label>
</div>
{{--@endif--}}

<div><h4 class="extra">Deposit & ADJUSTED PRICE</h4></div>
<label id="" class="extra">
    <span>Deposit</span><br>
    $<input style="font-size:16px;font-weight: bold" name="rent_deposit" id="rent_deposit"  class="field is-empty" type="text"  />
</label><br>

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
@if(!empty($isEdit))
<label class="custom-control shak custom-checkbox">
    <input type="checkbox" checked="checked" name="deposit_id_checkbox" id="deposit_id_checkbox" class="custom-control-input" @if(empty($agent_rents_order['deposit'])) checked="checked"@endif>
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description"> ID </span>
</label>
@endif
<br>


{{--<button type="submit" class="btn btn-primary" name="deposit_credit_card" id="deposit_credit_card" value="deposit_credit_card" onclick="return ccSubmitCheck()">Credit Card</button>--}}
{{--<button type="submit" class="btn btn-primary" name="deposit_cash" id="deposit_cash" value="deposit_cash" onclick="return cashSubmitCheck()">Cash</button><br><br>--}}
<div @if(Session::has('location') && (Session::get('location')=='Central Park West' || Session::get('location')=='Central Park South' || Session::get('location')=='Grand Army Plaza') || Session::get('location')=='High Bridge Park')) style="display: none;"
    @endif>
    <label id="rent_adjust_label" class="shak" ><br>
    <span>Adjusted Price</span><br>
    $<input style="font-size:16px;font-weight: bold" name="rent_adjust" id="rent_adjust" class=" is-empty" type="text" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['adjust_price'] }}@elseif(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['adjust_price'] }}@endif" />
</label>
<label id="" class="shak"><br>
    <span>Adjusted Discount</span><br>
    %<input style="font-size:16px;font-weight: bold" name="rent_discount" id="rent_discount" class=" is-empty" type="text" step=".01" value="@if(!empty($agent_rents_order)){{ $agent_rents_order['adjust_percentage'] }}@elseif(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['adjust_percentage'] }}@endif" />
</label><br>
</div>
{{--@endif--}}

@if(!empty($agent_rents_order))
    <label id="" ><br>
        <span>Previous Total Before Tax</span><br>
        $<input style="font-size:18px;font-weight: bold" name="rent_previous_total_before_tax" id="rent_previous_total_before_tax" value="{{ $agent_rents_order['total_price_before_tax'] }}" class="readonly is-empty" type="text" readonly/>
    </label>
    <label id="" ><br>
        <span>Previous Total After Tax</span><br>
        $<input style="font-size:18px;font-weight: bold" name="rent_previous_total_after_tax" id="rent_previous_total_after_tax" value="{{ $agent_rents_order['total_price_after_tax'] }}" class="readonly is-empty" type="text" readonly/>
    </label>
@endif
<br><h4>Total PRICE</h4>
<label id="">
    <span>Total</span><br>
    $<input style="font-size:14px!important;font-weight: bold" name="rent_total_label" id="rent_total_label" class="readonly is-empty" type="text" value="@if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['total_price_before_tax'] }}@else 0 @endif" placeholder="0" readonly/>
</label>
<label id="">
    <span>Tax</span><br>
    $<input style="font-size:14px!important;font-weight:bold;color:green;" name="rent_tax" id="rent_tax" class="readonly is-empty" type="text" value="@if(!empty($agent_rents_order_cc)){{ number_format(floatval($agent_rents_order_cc['total_price_before_tax'])*0.08875,2) }}@else 0 @endif" placeholder="0" readonly/>
</label>
<label id="" ><br>
    <span>Total After Tax</span><br>
    $<input style="font-size:14px!important;font-weight: bold" name="rent_total_after_tax" id="rent_total_after_tax" class="readonly is-empty" type="text" value="@if(!empty($agent_rents_order_cc)){{ $agent_rents_order_cc['total_price_after_tax'] }}@else 0 @endif" placeholder="0" readonly/>
</label><br>
<label id="" ><br>
    <span>Total After Tax + Deposit</span><br>
    $<input style="font-size:14px!important;font-weight: bold" name="rent_total_after_tax_deposit" id="rent_total_after_tax_deposit" class="readonly is-empty" type="text" value="@if(!empty($agent_rents_order_cc)){{ floatval($agent_rents_order_cc['total_price_after_tax'])+floatval($agent_rents_order_cc['deposit']) }}@else 0 @endif" placeholder="0" readonly/>
</label><br>


<label id="" class="shak"><br>
    <span>Cash Paid</span><br>
    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_rendered" id="rent_rendered" class=" is-empty" type="text" value="0" placeholder="0" />
</label>
<label id="" class="shak"><br>
    <span>Change</span><br>
    $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_change" id="rent_change" class="readonly is-empty" type="text" value="0" placeholder="0" readonly />
</label>

