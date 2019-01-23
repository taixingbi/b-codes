@extends('layouts.master')

@section('title')
    big bike agent
@endsection
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >

    <meta name="viewport" content="width=device-width" />

@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')

    <br><br><br><br>

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

    @if(Session::has('success'))
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <div id="price-error" class="alert alert-warning">
                    {{ Session::get('success') }}
                    {{ Session::forget('success') }}
                </div>
            </div>
        </div>
    @endif


    <section class="container" style="width: 100%; overflow: hidden;">
        <a href="{{route("agent.add")}}" >
            <button style="margin-bottom: 30px" class="btn btn-default" >Add A Product To Inventory DBase</button>
        </a>
        <div class="row" style="float: left;">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                {{--<form id="payment-form" method="post">--}}
                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Barcode</span><br>
                        <input name="inventory_barcode" id="inventory_barcode" class=" is-empty"/>
                    </label>
                    <br>

                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Product name</span><br>
                        <input required name="inventory_name" id="inventory_name" class=" is-empty" />
                    </label><br>
                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Size</span><br>
                        <input name="inventory_size" id="inventory_size" class=" is-empty" />
                    </label><br>


                    <label id="rent_date_label">
                        {{--<span>Category</span><br>--}}
                        {{--<input name="inventory_cat" id="inventory_cat" class="field is-empty" value=""/>--}}
                        <span><span>Category</span></span>
                        <input class="agent-order-place form-control "  name="inventory_cat" id="inventory_cat" onchange="" />


                    </label><br>
                    <label id="rent_date_label">
                        <span>Price</span><br>
                        <input name="inventory_price" id="inventory_price" class="field is-empty inventory" value=""/>
                    </label><br>
                    <label id="rent_time_label">
                        <span>Quantity</span><br>
                        <input name="inventory_qua" id="inventory_qua" class="field is-empty inventory" value="1"/>
                        <span>Max Quantity: </span>
                        <input name="max_qua" id="max_qua" value="" readonly/>
                    </label>
                    <label id="rent_date_label">
                        <span>Total Price</span><br>
                        <input name="inventory_total_price" id="inventory_total_price" class="field is-empty" value=""/>
                    </label><br>
                    <label id="rent_date_label">
                        <span>Total Price After Tax</span><br>
                        <input name="inventory_total_price_tax" id="inventory_total_price_tax" class="field is-empty" value=""/>
                    </label><br>
                    <label id="rent_date_label" style="display: none;">
                        <input name="inventory_id" id="inventory_id" class="field is-empty" />
                    </label><br>


                    <br><br>

                    {{ csrf_field() }}
                <div style="display: inline-block;width: 200px">
                    <input type="submit" class="btn inventory-btn btn-primary" style="margin-right: 20px;" name="add" id="add" value="Add" />
                    <input type="submit" class="btn inventory-btn btn-primary" name="addWithoutBarcode" id="addWithoutBarcode" value="Add No Barcode" /></div><br><br>
                <div> <input type="button"><a href="https://eastriverparkbikerental.com/bigbike/agent/esignature"> Esignature</a> </div>

                {{--</form>--}}
            </div>
        </div>


        @if(Session::has('cart'))
            {{--{{ Session::forget('cart') }}--}}
            <div class="row">

                <div class="col-sm-6 col-md-4">
                    <ul class="list-group">
                        @foreach($products as $product)
                            <li class="list-group-item">
                                <span class="badge">{{ $product['qty'] }}</span>
                                <strong>{{ $product['item'] }}</strong>
                                <span class="label label-success">{{ $product['price'] }}</span>
                                <span class="label label-success">{{ $product['size'] }}</span>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        {{--<li><a href="{{ route('agent.updateCart') }}">Reduce by 1</a></li>--}}
                                        <li><a href="/bigbike/agent/sports/updateCart/{{$product['id']}}/1">Reduce by 1</a></li>
                                        <li><a href="/bigbike/agent/sports/updateCart/{{$product['id']}}/{{$product['qty']}}">Reduce All</a></li>
                                    </ul>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @if(!empty($totalPrice) && floatval($totalPrice)>0)
                        <form action="{{ route('agent.pmtForm') }}" method="post">
                            <div><h4>Customer Info</h4></div>
                            <label id="rent_date_label">
                                <span>First Name</span><br>
                                <input name="rent_customer" id="rent_customer" class="field is-empty" value=""/>
                            </label>
                            <label id="rent_date_label">
                                <span>Last Name</span><br>
                                <input name="rent_customer_last" id="rent_customer_last" class="field is-empty" value=""/>
                            </label>
                            <br><br>

                            <label id="rent_date_label">
                                <span>Comment</span><br>
                                <textarea rows="2" cols="35" form="payment-form" name="comment" id="comment" class="field is-empty" ></textarea>
                            </label><br><br>

                            <label id="rent_adjust_label" class="" ><br>
                                <span>Adjusted Price</span><br>
                                $<input style="font-size:16px;font-weight: bold" name="rent_adjust" id="rent_adjust" class=" is-empty" type="text" value="" />
                            </label>
                            <label id="" class=""><br>
                                <span>Adjusted Discount</span><br>
                                %<input style="font-size:16px;font-weight: bold" name="rent_discount" id="rent_discount" class=" is-empty" type="text" step=".01" value="" />
                            </label><br>

                            <label id="">
                                <span>Total</span><br>
                                $<input style="font-size:14px!important;font-weight: bold" name="rent_total_label" id="rent_total_label" class="readonly is-empty" type="text" value="@if(!empty($totalPrice)){{ $totalPrice }}@endif" placeholder="0" readonly/>
                            </label>
                            <label id="">
                                <span>Tax</span><br>
                                $<input style="font-size:14px!important;font-weight:bold;color:green;" name="rent_tax" id="rent_tax" class="readonly is-empty" type="text" value="" placeholder="0" readonly/>
                            </label>
                            <label id="" ><br>
                                <span>Total After Tax</span><br>
                                $<input style="font-size:14px!important;font-weight: bold" name="rent_total_after_tax" id="rent_total_after_tax" class="readonly is-empty" type="text" value="" placeholder="0" readonly/>
                            </label><br>
                            <label id="" class="shak"><br>
                                <span>Cash Paid</span><br>
                                $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_rendered" id="rent_rendered" class=" is-empty" type="text" value="0" placeholder="0" />
                            </label>
                            <label id="" class="shak"><br>
                                <span>Change</span><br>
                                $<input style="font-size:18px;font-weight: bold;color:green;" name="rent_change" id="rent_change" class="readonly is-empty" type="text" value="0" placeholder="0" readonly />
                            </label>

                            {{--<span class="label label-success">Total: ${{ $totalPrice }}</span><br><br>--}}
                            {{ csrf_field() }}
                            <div style="margin-top: 20px">
                            <input type="submit" class="btn btn-primary" name="credit_card" id="credit_card" value="Credit Card" onclick="return ccSubmitCheck()" />
                            <input type="submit" class="btn btn-primary shak" name="cash" id="cash" value="Cash" onclick="return ccSubmitCheck()"/></div><br><br>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </section>

    <div class="col-md-12 " >
        <div class="mk-fancy-table mk-shortcode table-style1">
            <div>
                <table width="100%" style="margin-top: 10px;">
                    <thead>
                    <tr class="bl">
                        <th >#</th>
                        <th >Product Name</th>
                        <th >Size</th>
                        <th >Quantity</th>
                        <th >Price</th>
                        <th >Date</th>
                        <th >Cashier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row = 1;?>
                    @foreach($saleLists as $item)
                        @if(in_array($item->sale_id, $idMap))
                        <tr >
                            {{--<td class="cell-size" >--}}
                            {{--<span class="cell-item btn duration-title {{ $row }}0"  >--}}
                            {{--<input class="delete_agent" type="checkbox" id="{{$item->name}}" value="{{$item->id}}"  onclick="event.cancelBubble = true;">--}}
                            {{--</span>--}}
                            {{--</td>--}}
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $item->name }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $item->size }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $item->quantity }}</span>
                            </td>
                            <?php
                                $percent = empty($item->percentage)?1:(1-floatval($item->percentage)/100);
                            ?>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >${{ number_format($item->price*$percent*(1.08875),2) }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ explode(" ",$item->created_at)[0] }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >@if(!empty($nameMap)){{ $nameMap[$item->cashier_email] }}@endif</span>
                            </td>

                        </tr>
                        <?php $row++; ?>
                        @endif
                    @endforeach


                    </tbody>
                </table><br>
            </div>
        </div>
    </div>

    
@endsection

@section('scripts')

    <script>


        var categories = [];
        @foreach($categories as $category)
            categories.push('{{$category->title}}');
        {{--agentListMap.set('{{$agent->fullname}}', '{{$agent->level}}');--}}

        @endforeach

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
    <script src="{{ URL::to('js/inventory.js') }}"></script>
    <script>
        var originalPriceBefore = '{{ $totalPrice }}';
        //        var originalPriceBefore = 0;

        $( "#inventory_cat" ).autocomplete({
            source: categories
        });


    </script>


@endsection