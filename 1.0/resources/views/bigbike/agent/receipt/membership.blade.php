@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
@endsection

@section('content')

    @if(Session::has('rent_success'))
        {{ Session::forget('rent_success') }}
        <div class="text-center col-md-12" >

            <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>
            <button style="margin-left: 100px;" class="btn btn-success"  onclick="window.location.href='/bigbike/agent/rent/order'">Go to main page</button>

            {{--<div class="text-right" style="margin-left: 36%"><button class="btn btn-success"  onclick="window.location='{{ route('agent.rentTicket') }}' ">Print Ticket</button> </div><br>--}}
            <br>

            <div style="font-size: 18px">
                <div class="text-center " ><p><strong>Central Park Bike Tours<br> address: {{ $location->title }},<br> New York, NY {{ $location->zipcode }} <br>phone: {{ $location->phone }}
                            <br>In case of an accident - Toll<br>
                            Free: 877-498-8088</strong><br>

                        Served by: {{ $caisher_name }}<br></p>

                    Member Name: {{ $member['customer_name'].' '.$member['customer_lastname'] }}<br>
                    Membership Type: {{ $member['member_type'] }}<br>
                    Membership Number: {{ $member['member_number'] }}<br>

                    Total after Tax:<strong> ${{ $member['price'] }}</strong> <br><br>

                    <?php
                    //                            echo '<img id="barcode" src="data:image/png;base64,' . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39") . '" alt="barcode"  style="width: 40%;" />';
                    //                    $data = "data:image/png;base64," . DNS1D::getBarcodePNG($agent_rents_order['barcode'], "C39");
                    //
                    //                    list($type, $data) = explode(';', $data);
                    //                    list(, $data)      = explode(',', $data);
                    //                    $data = base64_decode($data);

                    //                    file_put_contents('asassads.png', $data);
                    //                                            echo DNS1D::getBarcodeHTML($agent_rents_order['barcode'], "C39");

                    ?>
                    <br><br>
                </div>
            </div>


        </div>
        </div>
        </div>

    @else
        <div>No Transaction!</div>
    @endif
@endsection

@section('scripts')
    <script>
        window.onload = function() { window.print(); }
    </script>
@endsection
