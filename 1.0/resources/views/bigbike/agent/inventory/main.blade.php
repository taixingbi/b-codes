@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/rent-main.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/inventory-main.css') }}" >
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
@endsection
@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
        {{--<div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>--}}

        {{--<h2>Daily Report </h2>--}}
        {{--<div class="text-center">--}}
            {{--<h2>Location Summary</h2>--}}
        {{--</div>--}}
        {{--{{ dd($locations) }}--}}
{{--<div  >--}}
    {{--<div class="col-md-6 mr-top" >--}}
        {{--<div class="mk-fancy-table mk-shortcode table-style1">--}}

        {{--@foreach($inventories as $inventory)--}}
            {{--{{ $inventory->title }}--}}

        {{--@endforeach--}}

        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
        {{--    --}}
    @if(Session::has('rent_price_error'))
        <br>
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('rent_price_error') }}
                    {{ Session::forget('rent_price_error') }}
                </div>
            </div>
        </div><br>
    @endif
@php
    //dd(Session::get('inv_cart'));
    $cnt = 0;
@endphp

        <h2>Inventory</h2>
        @if(Session::has('inv_cart') && Session::get("inv_cart")["price"]>0)

            <h2 style="margin-top:100px;">Shopping Cart</h2>
            <h2>Total Price: @if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){{"$".Session::get("inv_cart")["price"]}}@endif
            </h2>
            <div style="text-align:center">

                <a href="{{ route('agent.inventory.clear_cart') }}">Clear Shopping cart</a>
            </div>
            @php
                $cart = Session::get('inv_cart');
            @endphp

            <table id="inventory_cart" class="display inventory" style="margin-top:20px;">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $key => $value)
                    @if($key!="price" && $key!="firstname" && $key!="lastname")
                        <tr>
                            {{--                            <td>{{$key}}</td>--}}
                            <td>{{$value['title']}}</td>
                            <td>{{"$".$value['price']}}</td>
                            <td>{{$value['qty']}}</td>
                            <td>@if(intval($value['qty'])>0)
                                    <form action="{{ route('agent.inventory.update_qty') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="text" hidden name="id" value="{{$value["id"]}}">
                                        <input type="text" hidden name="name" value="{{$key}}">
                                        <select name="qty" id="qty" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                                            @for ($i = 0; $i <= intval($value["qty"]); $i++)
                                                <option value="{{ $i }}" @if($i==0) default @endif>{{ $i }}</option>
                                                {{--<option value="saab">Saab</option>--}}
                                                {{--<option value="opel">Opel</option>--}}
                                                {{--<option value="audi">Audi</option>--}}
                                            @endfor
                                        </select>

                                        <input type="submit" id='ppBtn' class="btn btn-primary " onclick="" name="update" value="Update" style="background-color: #007bff;">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
                </tfoot>
            </table>
        @endif

        {{--<h2 style="margin-top:100px;">Checkout</h2>--}}

        <div style="margin-bottom: 20px;width: 50%;float: left;margin-left: 60px;">
            <form action="{{ route('agent.inventory.inv_cartCheckout_cash') }}" method="post">
                <label for="fname">First Name</label>
                <input class="checkout" type="text" id="fname" required name="firstname" placeholder="Your name..">

                <label for="lname">Last Name</label>
                <input class="checkout" type="text" id="lname" required name="lastname" placeholder="Your last name..">


                {{ csrf_field() }}
                <input class="submit" type="submit" name="credit_card" value="Credit Card" style="margin-bottom: 10px;">
                <input class="submit btn-warning" type="submit" name="Cash" value="Cash" style="background-color: #007bff;">
            </form>
        </div>

        <table id="inventory" class="display inventory" style="width:100%;">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>SKU</th>
                <th>Barcode</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                    @if($inventory->status)
                    <tr>
                    <td>{{$inventory->title}}</td>
                    <td>{{"$".$inventory->price}}</td>
                    <td>{{$inventory->qty}}</td>
                    <td>{{$inventory->sku}}</td>
                    <td>{{$inventory->title}}</td>
                    <td>@if(intval($inventory->qty)>0)
                            <input type="text" hidden id="price{{ $inventory->id }}" value="{{ $inventory->price }}">
                            <select id="qty{{ $inventory->id }}" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">
                                @for ($i = 0; $i <= intval($inventory->qty); $i++)
                                    <option value="{{ $i }}" @if($i==0) default @endif>{{ $i }}</option>
                                    {{--<option value="saab">Saab</option>--}}
                                    {{--<option value="opel">Opel</option>--}}
                                    {{--<option value="audi">Audi</option>--}}
                                @endfor
                            </select>
                            <button type="button" id="item{{ $inventory->id }}" class="btn btn-success invBTN" style="background-color: #007bff;">Add to Cart</button>
                        @endif
                    </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>SKU</th>
                <th>Barcode</th>
                <th>Action</th>
            </tr>
            </tfoot>
        </table>

{{--@foreach($inventories as $inventory)--}}
    {{--@if($cnt%4==0)--}}
        {{--<div class="row course-set courses__row">--}}
    {{--@endif--}}
        {{--<article class="col-md-4 course-block course-block-lessons">--}}
            {{--{{ "Title: ".$inventory->title }}--}}
            {{--{{ "SKU: ".$inventory->sku }}--}}
            {{--{{ "Price: ".$inventory->price }}--}}
            {{--{{ "QTY: ".$inventory->qty }}--}}
            {{--{{ "Status: ".$inventory->status }}--}}
            {{--<ul class="list-group col-md-3 course-block course-block-lessons">--}}
                {{--<li class="list-group-item" >{{ "Title: ".$inventory->title }}</li>--}}
                {{--<li class="list-group-item" >{{ "SKU: ".$inventory->sku }}</li>--}}
                {{--<li class="list-group-item"><span class="badge" >{{ "Price: ".$inventory->price.",  QTY: ".$inventory->qty.",  Status: ".$inventory->status }}</span></li>--}}
                {{--<li class="list-group-item">{{ "QTY: ".$inventory->qty }}</li>--}}
                {{--<li class="list-group-item">{{ "Status: ".$inventory->status }}</li>--}}
                {{--<div class="dropdown">--}}
                    {{--<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">--}}
                        {{--Dropdown--}}
                        {{--<span class="caret"></span>--}}
                    {{--</button>--}}
                    {{--<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">--}}
                        {{--<li><a href="#">Action</a></li>--}}
                        {{--<li><a href="#">Another action</a></li>--}}
                        {{--<li><a href="#">Something else here</a></li>--}}
                        {{--<li role="separator" class="divider"></li>--}}
                        {{--<li><a href="#">Separated link</a></li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
                {{--@if(intval($inventory->qty)>0)--}}
                    {{--<input type="text" id="price{{ $inventory->id }}" value="{{ $inventory->price }}">--}}
                {{--<select id="qty{{ $inventory->id }}" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">--}}
                    {{--@for ($i = 0; $i <= intval($inventory->qty); $i++)--}}
                    {{--<option value="{{ $i }}" @if($i==0) default @endif>{{ $i }}</option>--}}
                    {{--<option value="saab">Saab</option>--}}
                    {{--<option value="opel">Opel</option>--}}
                    {{--<option value="audi">Audi</option>--}}
                    {{--@endfor--}}
                {{--</select>--}}
                {{--<button type="button" id="item{{ $inventory->id }}" class="btn btn-default">Add to Cart</button>--}}
                {{--@endif--}}
            {{--</ul>--}}

        {{--</article>--}}
    {{--@if($cnt%4==3)--}}
        {{--</div>--}}
    {{--@endif--}}
    {{--@php ($cnt++)--}}
{{--@endforeach--}}


        {{--<div class="row" style="clear: left;float: left;">--}}
            {{--<div class="col-md-6">--}}

                {{--@if(Session::has('inv_cart'))--}}
                    {{--<h3>Shopping Cart</h3>--}}
                    {{--@php--}}
                        {{--$cart = Session::get('inv_cart');--}}
                    {{--@endphp--}}
                    {{--@foreach($cart as $key=>$value)--}}
                        {{--@if($key!="price" && $key!="firstname" && $key!="lastname")--}}
                            {{--@if($value['qty']>0)--}}
                            {{--<div class="list-group-item">{{ $key.": ".$value['qty'] }}</div>--}}
                            {{--<form action="{{ route('agent.inventory.update_qty') }}" method="post">--}}
                                {{--{{ csrf_field() }}--}}
                                {{--<input type="text" hidden name="id" value="{{$value["id"]}}">--}}
                                {{--<input type="text" hidden name="name" value="{{$key}}">--}}

                                {{--<select name="qty" id="qty{{ $inventory->id }}" class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelectPref">--}}
                                    {{--@for ($i = 0; $i <= intval($value["qty"]); $i++)--}}
                                        {{--<option value="{{ $i }}" @if($i==0) default @endif>{{ $i }}</option>--}}
                                        {{--<option value="saab">Saab</option>--}}
                                        {{--<option value="opel">Opel</option>--}}
                                        {{--<option value="audi">Audi</option>--}}
                                    {{--@endfor--}}
                                {{--</select>--}}

                                {{--<input type="submit" id='ppBtn' onclick="" name="update" >--}}
                            {{--</form>--}}
                            {{--@endif--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
{{--        <button ><a href="{{ route('agent.inventory.checkout') }}" class="button">Credit Card</a></button>--}}
        {{--<div class="row" style="float: right;">--}}
            {{--<div class="col-md-4">--}}

            {{--<form action="{{ route('agent.inventory.inv_cartCheckout_cash') }}" method="post">--}}
                {{--Total Price: <input type="text" name="inventory_price" id="inventory_price" value="@if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){{Session::get("inv_cart")["price"]}}@endif">--}}
                {{--First name:<br>--}}
                {{--<input type="text" required name="firstname" value="">--}}
                {{--<br>--}}
                {{--Last name:<br>--}}
                {{--<input type="text" required name="lastname" value="">--}}
                {{--<br><br>--}}
                {{--{{ csrf_field() }}--}}
                {{--<input type="submit" name="Credit Card" value="credit_card" style="margin-bottom: 10px;">--}}
                {{--<input type="submit" name="Cash" value="Cash" >--}}
            {{--</form>--}}

            {{--</div>--}}
        {{--</div>--}}




@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>


    <script>
        // $('.container').addClass('MyClass2');
        // $('.container').removeClass('container');
        // $('.row').removeClass('row');
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

        $('.inventory').DataTable( {
            "pagingType": "full_numbers"
        } );

        $('#inventory_cart_wrapper').css("width","40%");
        $('#inventory_cart_wrapper').css("float","left");


    </script>
@endsection
