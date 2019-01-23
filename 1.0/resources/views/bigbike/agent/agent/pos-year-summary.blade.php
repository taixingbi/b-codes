@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>POS Month Summary(missing pedicab && reservatoin) ({{ $year }}):</h2>
        {{--<h3>Location: @if(!empty($location)){{ $location }} @else All Stores @endif</h3>--}}
        <h3>Total : ${{ number_format($sum,2) }}</h3>
        @foreach($locations as $key=>$item)
            <div>
                {{--<table class="table  table-hover col-md-8">--}}
                    <table class="table  table-hover col-md-8" style="width: 48.66667%!important;">

                <caption style="font-weight: bold;">{{  $key }}</caption>
                    @foreach($item as $key_sub=>$item_sub)
                        <tr >
                            <th >{{ $key_sub }}</th>
                            <td class="" >
                                <span class=" " >${{ number_format($item_sub,2) }}</span>
                            </td>

                        </tr>
                    @endforeach
                </table>

            </div>
        @endforeach
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>

@endsection