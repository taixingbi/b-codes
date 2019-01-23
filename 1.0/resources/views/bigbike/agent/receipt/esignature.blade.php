@extends('layouts.master')

@section('title')
    big bike agent
@endsection
@section('styles')

    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/esignature.css') }}" >

@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')

    <div class="print-colf">

        <br><br>
        <u>Late Fee:</u>
        A 15-minute grace period shall be allowed for the return of bicycles following the cessation
        of bike rental period, with no late fee charged. If you do not return any bicycle or child
        seat for any reason before that 15-minute grace period, the hourly late fee begins calculating
        and you will be required to pay an appropriate late fee. Late Fee Prices: Adult Bikes, Child
        Bikes and Child Bike Trailers = $10 per bike-per hour; Tandem Bikes, Road Bikes, Mountain
        Bikes and Hand-cycles = $20 per hour-per bike; Child Seat = $5 per hour-per seat. Late fees
        are not prorated and any minute-used of an hour, constitutes full use of that hour. Late fee
        may not be waived except by cause or emergency, and only with approval of Manager.
        <br><br>
        <u>All Sales Are Final:</u>
        No bicycle may be rented without signature and liability acceptance of a responsible adult.
        No cash refund for any reason; nor may the store credit be applied for unused bicycle during
        rental time.</b>
        {{Session::get('rent_id')}}
<div class="signn">
    <div class="wrapper">
    <img src="{{ URL::to('images/white.jpg') }}" width=800 height=300 />
    <canvas id="signature-pad" class="signature-pad" width=800 height=300></canvas>
            <span id="save" class="" style="position: absolute;top: 45%;right:30px;font-size:26px;cursor: pointer">Done</span>
            <span id="clear" class="btnn" style="position: absolute;top: 10px;right: 20px;cursor: pointer;font-size:22px">X</span>
            {{--<button id="clear" class="btn-primary">Clear</button>--}}

        </div>
    <div style="margin-top: 30px;text-align: center">

    </div></div></div>

    <div style="text-align:center"><img id="img" class="signature-img" src="" style="width:30%" /></div>

@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });
        var wrapper = document.getElementsByClassName('wrapper');
        var saveButton = document.getElementById('save');

        saveButton.addEventListener('click', function (event) {
            var dataURL = signaturePad.toDataURL();
            document.getElementById('img').setAttribute( 'src', dataURL );

//            console.log(dataURL);



// Send data to server instead...
//            window.open(data);

            $.ajax({
                type: "POST",
                cache: false,
                url: '/bigbike/agent/esignature/store',
                data: "dataURL=" + dataURL,
                success: function(data) {
                    swal({
                        title: "Signature was uploaded",
                        timer: 1500,
                        showConfirmButton: true
                    });
                    console.log("Success");
                    $(".signn").delay(2000).fadeOut('5000',function () {
//                        window.onload=function(){self.print();}
//                        window.print();
                    });
                    if(data['type']=='error'){
                        console.log("error: "+data['response']);
//                        swal(data['response']);
                    } else if(data['type']=='reservation' || data['type']=='return' || data['type']=='good'){
//                        window.location.replace(data['url']);
                        console.log("no error: "+data['response']);

                    }

                }

            });
        });

        $('.btnn').on('click', function (event) {
            signaturePad.clear();
        });

    </script>
<script>
    function  reloadPage() {
        $('html,body').animate({scrollTop: document.body.scrollHeight},"slow");
        $('#signature-pad').click();

    }
//reloadPage();



</script>
    
@endsection