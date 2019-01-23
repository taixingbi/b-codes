@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>POS Cashier Summary ({{ $type }}):{{ $data }}</h2>
        {{--<h3>Total : ${{ number_format($sum,2) }}</h3>--}}
        <div>
            <table class="table table-striped table-bordered table-hover col-md-8">
                <caption></caption>
                <thead>
                <tr class="gr" style="color: white">
                    <th >#</th>
                    <th >Employee</th>
                    <th >Credit Card</th>
                    <th >Cash</th>
                    <th >Paypal</th>
                    <th >Coupon</th>
                    <th >Sports Sales(Test)</th>
                    <th >Sales Amount</th>
                    <th >Manager Edit Times</th>
                </tr>
                </thead>
                <tbody>
                <?php $row = 1;?>
                @foreach($map as $k => $v)
                    <tr href='{{route("agent.showPosCashierMoreDetail",['email'=>$k,'type'=>$type,'data'=>$data])}}'>
                        <td class="cell-size">
                            <span class="cell-item btn" >{{ $row }}</span>
                        </td>
                        <td class="cell-size" >
                            @if(array_key_exists($k, $name_map))
                                <span class="cell-item " >{{ $name_map[$k] }}</span>
                            @else
                                <span class="cell-item " >{{ $k }}</span>
                            @endif
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($newMap[$k]['cc'],2) }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($newMap[$k]['cash'],2) }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($newMap[$k]['pp'],2) }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($newMap[$k]['coupon'],2) }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($newMap[$k]['sports'],2) }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item " >${{ number_format($v,2) }}</span>
                        </td>
                        <td class="cell-size" >
                            @if(array_key_exists($k, $manager_map))
                                <span class="cell-item ">{{ $manager_map[$k] }}</span>
                            @else <span class="cell-item ">0</span>
                            @endif
                        </td>
                    </tr>
                    <?php $row++; ?>

                @endforeach
                </tbody>
            </table>

            {{--<table class="table table-striped table-bordered table-hover col-md-8">--}}
            {{--<caption></caption>--}}
            {{--<tr class="bl" style="color: white">--}}
            {{--<th >Date</th>--}}
            {{--<th >Numbers</th>--}}
            {{--<th >Amount</th>--}}
            {{--</tr>--}}
            {{--<tr >--}}
            {{--<th >Deposit</th>--}}
            {{--<td class="cell-size" >--}}
            {{--<span class="cell-item " >{{ $deposit_num }}</span>--}}
            {{--</td>--}}
            {{--<td class="cell-size" >--}}
            {{--<span class="cell-item ">${{ number_format($deposit_sum, 2) }}</span>--}}
            {{--</td>--}}
            {{--</tr>--}}
            {{--</table>--}}

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>


@endsection