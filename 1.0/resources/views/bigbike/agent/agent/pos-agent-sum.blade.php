@extends('layouts.master')

@section('title')
    Agent Commision Today
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin-pos.css') }}" >
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">


@endsection

@section('content')

    <div class="row" style="margin: auto; text-align: center;">

        <h3>POS Agent Summary Today({{ $date }})</h3><br>

        <div class="col-md-12 " >
            <div class="mk-fancy-table mk-shortcode table-style1">

                <div>
                    <table width="100%" style="margin-top: 10px;">
                        <thead>
                        <tr class="gr">
                            <th >#</th>
                            <th >Agent name</th>
                            <th >Sum After Tax</th>
                            <th >Sum Before Tax, Baskets, Insurance, etc</th>
                            <th >Commission(%)</th>
                            <th >Commission Fee</th>
                            <th >Counts</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;?>
                        @foreach($map as $item)
                            <tr href='{{route("agent.showAgentComDetail",['id'=>$item['id'] ])}}'>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $row }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >{{ $item['name'] }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" >${{ $item['sum_after'] }}</span>
                                </td>

                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >${{ $item['sum'] }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  > @if($item['commission']) {{ $item['commission'] }}% @endif</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration"  >${{ $item['commissionFee'] }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title "  name="duration" id="count_down" >{{ $item['nums'] }}</span>
                                </td>
                            </tr>
                            <?php $row++; ?>
                        @endforeach
                        </tbody>
                    </table><br>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::to('js/notify.js') }}"></script>
    <script src="{{ URL::to('js/jquery.timepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.js"></script>
    <script src="{{ URL::to('js/admin-pos.js') }}"></script>



@endsection
