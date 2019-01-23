<div class="col-md-12 col-sm-12" style="margin-bottom: 20px;">
<div class="col-md-2 col-sm-2">
    <a href="{{route("agent.showReservationPage")}}" ><button style="background-color: #5e4485; color: white;" type="button" class="btn btn-circle btn-xl" >
        <i class="glyphicon glyphicon-folder-open"></i><p class="icon-main">RESERVATION</p></button></a>
</div>
<div class="col-md-2 col-sm-2">
    <a href="{{route("agent.rentOrder")}}" ><button type="button" class="btn btn-primary btn-circle btn-xl">
        <i class="glyphicon "><img
                    src="{{ URL::to('images/bicycle.svg') }}" width="60px" alt=""></i><p class="icon-main">RENTAL</p>
    </button></a>
</div>
<div class="col-md-2 col-sm-2">

    <a href="{{route("agent.tourOrder")}}" ><button type="button" class="btn btn-success btn-circle btn-xl" >
       <i class="glyphicon glyphicon-map-marker"></i> <p class="icon-main">TOUR</p></button></a>
</div>


<div class="col-md-2 col-sm-2">

    <a href="{{route("agent.showReturnPage")}}" ><button type="button" class="btn btn-danger btn-circle btn-xl">
            <i class="glyphicon glyphicon-list-alt"></i><p class="icon-main">RETURN</p></button></a>
</div>

<div class="col-md-2 col-sm-2">

    <a href="{{route("agent.report")}}" ><button type="button" class="btn btn-warning btn-circle btn-xl" >
        <div>
            <i class="glyphicon glyphicon-user"></i><p class="icon-main">CASHIER REPORT</p></div></button></a>
</div>

<div class="col-md-2 col-sm-2">

    <a href="{{route("agent.showMemberPage")}}" ><button type="button" class="btn btn-info btn-circle btn-xl" >
        <i class="glyphicon glyphicon-sunglasses"></i><p class="icon-main">MEMBERSHIP</p></button></a>
</div>
    <div class="col-md-2 col-sm-2">

        <a href="{{route("agent.sportsSale")}}" ><button style="color:white;background:#596e88;" type="button" class="btn btn-basic btn-circle btn-xl" >
                <i class="glyphicon glyphicon-shopping-cart"></i><p class="icon-main">INVENTORY</p></button></a>
    </div>
    <div>
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.searchCustomer") }}'">Customer Info Search</button>
    </div>
@if(Session::has('level') && Session::get('level')==1 && (Auth::user()->email!='bermudezcrystal@gmail.com' && Auth::user()->email!="josesimen@yahoo.com"))

    <div style="margin-top:20px;text-align:center;">
        {{--@if(Session::has('level') && Session::get('level')==0 )--}}
        {{--<button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.posMonthReport") }}'">POS Summary(Testing)</button>--}}
        {{--@endif--}}
        @if(Auth::user()->email=="xdrealmadrid@gmail.com")
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.getPosCashierReport") }}'">POS Cashier Summary</button>
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.getPosDailyReport") }}'">POS Daily Report(Testing)</button>
        @endif
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.posAgentUpdate") }}'">POS Agent Commision Setting</button>
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.posAgentAdd") }}'">POS Agent Adding</button>
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.posAgentReport") }}'">POS Agent Report</button>
        {{--<button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.sportsSale") }}'">Sports Sale</button>--}}

    </div>
    <div style="margin-top:20px;text-align:center;">
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.bikeInventory") }}'">Bike Inventory(Testing)</button>
        {{--<button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.clockMain") }}'">Clock System(Testing)</button>--}}
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.searchCustomer") }}'">Customer Info Search</button>
        @if(Session::has('location') && (Session::get('location')=='203W 58th Street' || Session::get('location')=='117W 58th Street'))
        <button type="button" class="btn btn-success" onclick="window.location='{{ route("agent.inventory.main") }}'">New Inventory Testing</button>
        @endif
</div>
@endif
<div class="shak" style="position:;
top: 150px;">
<h4 class="shak">Barcode</h4>
<label id="rent_barcode_label">
<span>Barcode Scan</span><br>
<input name="rent_barcode" id="rent_barcode" class=" is-empty"/>
</label><br>
</div>
</div>


