@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
    <div class="row text-center">
        <div class="text-center" ><button class="btn btn-success"  onclick="window.print() ">Print Report</button> </div>

        <h2>POS Cashier Summary<br> {{ $name }} ({{ $type }}):{{ $data }}</h2>
        {{--<h3>Total : ${{ number_format($sum,2) }}</h3>--}}
        <div>
            <table class="table table-striped table-bordered table-hover col-md-8">
                <caption></caption>
                <thead>
                <tr class="gr" style="color: white">
                    <th >#</th>
                    <th >Quantity</th>
                    <th >Sales Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php $row = 1;?>
                <tr >
                    <th >Cash</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $cash_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $cash_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Credit Card</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $cc_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $cc_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Paypal</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $paypal_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $paypal_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Coupon</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $coupon_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $coupon_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th>Sports Sales(Test)</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $sport_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $sport_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Total</th>
                    <td class="cell-size" >
                        <span class="cell-item " ></span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ number_format($sum, 2) }}</span>
                    </td>
                </tr>
                <?php $row++; ?>
                </tbody>
            </table>

            <table class="table table-striped table-bordered table-hover col-md-8">
                <caption></caption>
                <thead>
                <tr class="gr" style="color: white">
                    <th >#</th>
                    <th >Quantity</th>
                    <th >Sales Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php $row = 1;?>
                <tr >
                    <th >Deposit</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $deposit_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $deposit_sum }}</span>
                    </td>
                </tr >
                <tr >
                    <th >Late Fee</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $latefee_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $latefee_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Insurance</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $ins_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $ins_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Basket</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $basket_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $basket_sum }}</span>
                    </td>
                </tr>
                <tr >
                    <th >Dropoff</th>
                    <td class="cell-size" >
                        <span class="cell-item " >{{ $dropoff_num }}</span>
                    </td>
                    <td class="cell-size" >
                        <span class="cell-item ">${{ $dropoff_sum }}</span>
                    </td>
                </tr>
                <?php $row++; ?>
                </tbody>
            </table>

            <table width="100%" class="table table-striped table-bordered table-hover col-md-8" >
                <caption>Manager Edit History</caption>
                <thead>
                <tr class="gr" style="color: white">
                    <th >#</th>
                    <th >Cashier Name</th>
                    <th >Manager Name</th>
                    <th >Date&Time</th>
                    <th >Payment Type</th>
                    <th >Total Price</th>
                    <th >Edit Price</th>
                    <th >Hours</th>
                    <th >Bike Quantity</th>
                </tr>
                </thead>
                <tbody>
                <?php $row = 1;?>
                @foreach($manager_array as $item)
                    @if($item->cashier_email==$email && !empty($item->extra_cashier_email))
                        <tr>
                            <td class="cell-size">
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $row }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $name_map[$item->cashier_email] }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $name_map[$item->extra_cashier_email] }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->time }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->payment_type }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->total_price_after_tax }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->extra_service_total_after_tax }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->duration }}</span>
                            </td>
                            <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0" id="{{ $row }}0" name="duration" >{{ $item->total_bikes }}</span>
                            </td>
                        </tr>
                        <?php $row++; ?>

                    @endif
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="{{ URL::to('js/agent-report.js') }}"></script>


@endsection