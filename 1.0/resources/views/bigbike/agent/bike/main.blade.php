@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/rent-main.css') }}" >
@endsection

@section('content')
        {{--<div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>--}}

        {{--<h2>Daily Report </h2>--}}
        {{--<div class="text-center">--}}
            {{--<h2>Location Summary</h2>--}}
        {{--</div>--}}
        {{--{{ dd($locations) }}--}}
<div  >
    <div class="col-md-6 mr-top" >
        <div class="mk-fancy-table mk-shortcode table-style1">

        @foreach($locations as $location)
        <table width="70%" class="table table-hover" >
            <caption>{{ $location->title }}</caption>
            <thead>
                <tr class="bl">
                    <th >#</th>
                    <th >Title</th>
                    <th >QR Code</th>
                    <th >Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $row = 1;?>
                @foreach($bikes as $bike)
                    @if($location->title==$bike->location)
                    <?php
                        $curDate = date("Y-m-d");
                        if($bike->status==1){
                            $status = "In Store";
                        }else{
                            $status = "Out";
                        }
                    ?>
                    {{--<tr href='{{route("",['id'=>$item->id])}}'>--}}
                    <tr href=''>
                        <td class="cell-size" >
                            <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $row }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $bike->title }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $bike->qrcode }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $status }}</span>
                        </td>
                    </tr>
                    <?php $row++; ?>
                    @endif
                @endforeach
            </tbody>
        </table><br>
        @endforeach

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>
    <script>
        $('.container').addClass('MyClass2');
        $('.container').removeClass('container');
        $('.row').removeClass('row');

    </script>
@endsection
