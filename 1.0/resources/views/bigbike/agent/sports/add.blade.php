@extends('layouts.master')

@section('title')
    big bike agent
@endsection
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
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
        <div class="row" style="float: left;">
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                <form action="{{ route('agent.addToInt') }}" id="payment-form" method="post">
                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Barcode</span><br>
                        <input name="inventory_barcode" id="inventory_barcode" class=" is-empty"/>
                    </label>
                    <br>

                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Product</span><br>
                        <input name="inventory_name" id="inventory_name" class=" is-empty" />
                    </label><br>
                    <label class="member_checkbox_label shak" id="rent_date_label">
                        <span>Size</span><br>
                        <input name="inventory_size" id="inventory_size" class=" is-empty" />
                    </label><br>


                    <label id="rent_date_label">
                        {{--<span>Category</span><br>--}}
                        {{--<input name="inventory_cat" id="inventory_cat" class="field is-empty" value=""/>--}}
                        <span><span>Category</span></span>
                        <input class="  "  name="inventory_cat" id="inventory_cat" />
                    </label><br>
                    <label id="rent_date_label">
                        <span>Price</span><br>
                        <input name="inventory_price" id="inventory_price" class="field is-empty" value=""/>
                    </label><br>
                    <label id="rent_time_label">
                        <span>Quantity</span><br>
                        <input name="inventory_qua" id="inventory_qua" class="field is-empty" value="1"/>
                    </label>
                    <br><br>

                    {{ csrf_field() }}
                    <input type="submit" class="btn inventory-btn btn-primary" name="add" id="add" value="Add" /><br><br>
                </form>
            </div>
        </div>
    </section>

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
    {{--<script src="{{ URL::to('js/inventory.js') }}"></script>--}}


    <script>

        $( "#inventory_cat" ).autocomplete({
            source: categories
        });
    </script>
@endsection