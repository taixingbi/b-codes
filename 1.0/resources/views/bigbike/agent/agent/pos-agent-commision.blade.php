@extends('layouts.master')

@section('title')
    Agent Commision Setting
@endsection

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin-pos.css') }}" >
    <link href="https://cdn.jsdelivr.net/sweetalert2/6.6.0/sweetalert2.min.css" rel="stylesheet">


@endsection

@section('content')

    <div class="row" style="margin: auto; text-align: center;">

        @if(Session::has('success'))
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
                    <div id="charge-message" class="alert alert-success">
                        {{ Session::get('success') }}
                        {{ Session::forget('success') }}
                    </div>
                </div>
            </div>
        @endif

        {{--<h3>POS Cashier Search</h3><br>--}}

        <div class="col-md-12 " >
            <div class="mk-fancy-table mk-shortcode table-style1">
                <div>
                    <table width="100%" style="margin-top: 10px;">
                        <button style="float: left;margin-bottom: 20px;" type="submit" class="btn btn-primary" id="deleteAgent" name="deleteAgent" value="deleteAgent" onclick="return deleteAgent()">Delete Agent</button>
                        <thead>
                        <tr class="bl">
                            <th>Delete</th>
                            <th >#</th>
                            <th >Agent name</th>
                            <th >Commission(%)</th>
                            <th >Phone/Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row = 1;?>
                        @foreach($agents as $item)

                            <tr >
                                <td class="cell-size" >
                                <span class="cell-item btn duration-title {{ $row }}0"  >
                                    <input class="delete_agent" type="checkbox" id="{{$item->id}}" value="{{$item->id}}"  onclick="event.cancelBubble = true;">
                                </span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $row }}</span>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $item->fullname }}</span>
                                </td>
                                <td class="cell-size" >
                                    <input class="cell-item btn duration-title {{ $row }}0"  name="agent_com" id="up<?php echo $row;?>" value="" placeholder="{{ $item->commission }}" />
                                    <button class='update-agent-com' onclick="updateAgentCom('{{ $item->fullname }}', '<?php echo $row;?>')">Update</button>
                                </td>
                                <td class="cell-size" >
                                    <span class="cell-item btn duration-title {{ $row }}0"  name="duration" >{{ $item->email }}</span>
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
