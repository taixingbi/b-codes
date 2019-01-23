@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>Daily Report ({{ $end_date }}):</h2>
        <div class="text-center">
            <h2>Cashier Summary: {{ $cashier_name }}</h2>
        </div>
        <h3>Grand Cash : ${{ number_format($cash_sum,2) }}</h3>
        <h3>Grand Credit Card : ${{ number_format($cc_sum,2) }}</h3>

        <h3>Grand Total : ${{ number_format($sum,2) }}</h3>
        <h4>Location : {{ Session::get('location')}}</h4>
        <h4>Time : {{ date('h:i:s A')}}</h4>
{{--        {{dd($cash)}}--}}
        <div>
            <table class="table table-striped table-bordered table-hover col-md-8" >
                <caption></caption>
                <tr class="gr" style="color: white">
                    <th >Date</th>
                    <th >Numbers</th>
                    <th >Amount</th>
                </tr>
                <tr >
                    <th >Cash</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $cash_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($cash_sum,2)}}</span>
                    </td>
                </tr>
                <tr >
                    <th >Credit Card</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $cc_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($cc_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Paypal</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $paypal_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($paypal_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Coupon</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $coupon_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($coupon_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th>Sports Sales(Test)</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $sport_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($sport_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Total</th>
                    <td class="cell-size" >
                        <span class="cell-item " ></span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($sum,2) }}</span>
                    </td>
                </tr>
            </table>
            <table class="table table-striped table-bordered table-hover col-md-8" >
                <caption>Cash</caption>
                <tr class="gr" style="color: white">
                    {{--<th >Cash</th>--}}
                    <th >Location</th>
                    <th >Amount</th>
                </tr>
                <tr >
                    {{--<th >Cash</th>--}}
                    @foreach( $cash as $key => $item)
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $key }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $item  }}</span>
                    </td>
                    @endforeach
                </tr>
            </table>
            <table class="table table-striped table-bordered table-hover col-md-8" >
                <caption>Credit Card</caption>
                <tr class="gr" style="color: white">
                    {{--<th >Cash</th>--}}
                    <th >Location</th>
                    <th >Amount</th>
                </tr>
                <tr >
                    {{--<th >Cash</th>--}}
                    @foreach( $credit as $key => $item)
                        <td class="cell-size" >
                            <span class="cell-item " >{{ $key }}</span>
                        </td>
                        <td class="cell-size" >
                            <span class="cell-item ">${{ $item  }}</span>
                        </td>
                    @endforeach
                </tr>
            </table>


            <table class="table table-striped table-bordered table-hover col-md-8">
                <caption></caption>
                <tr class="gr" style="color: white">
                    <th >Date</th>
                    <th >Numbers</th>
                    <th >Amount</th>
                </tr>
                <tr >
                    <th >Insurance</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $ins_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($ins_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Basket</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $basket_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($basket_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Dropoff</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $dropoff_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($dropoff_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Late Fee</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $latefee_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($latefee_sum,2) }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Deposit</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $deposit_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($deposit_sum,2) }}</span>
                    </td>
                </tr>
            </table>
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
