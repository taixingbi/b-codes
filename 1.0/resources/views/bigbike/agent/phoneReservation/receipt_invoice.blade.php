@extends('layouts.master')

@section('title')
    Bikerent receipt
@endsection

@section('styles')
    <link rel="stylesheet" media="print" href="{{ URL::to('css/print.css') }}">
    <style>
        table{}
        table{border-collapse:collapse !important;margin-bottom:5px}
        .content {width: 100%; max-width: 600px;}
        .content img { height: auto; min-height: 1px; }

        #bodyTable{margin:0; padding:0; width:100% !important;}
        #bodyCell{margin:0; padding:0;}
        #bodyCellFooter{margin:0; padding:0; width:100% !important;padding-top:39px;padding-bottom:15px;}
        body {margin: 0; padding: 0; min-width: 100%!important;}

        #templateContainerHeader{
            font-size: 14px;
            padding-top:2.429em;
        }
        hr.style9 {
            border-top: 1px dashed #8c8b8b;
            border-bottom: 1px dashed #fff;
        }
        .marg{
            margin-left: 20px;
            margin-right: 20px;
            font-size: 14px;
        }
        .final{
            line-height: 22px;

        }
        #templateContainerImageFull { border-left:1px solid #e2e2e2; border-right:1px solid #e2e2e2; }
        #templateContainerFootBrd{
            border-bottom:1px solid #e2e2e2;
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-radius: 0 0 4px 4px;
            background-clip: padding-box;
            border-spacing: 0;
            height: 10px;
            width:100% !important;
        }
        #templateContainer{
            border-top:1px solid #e2e2e2;
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-radius: 4px 4px 0 0 ;
            background-clip: padding-box;
            border-spacing: 0;
        }
        #templateContainerMiddle {
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
        }
        #templateContainerMiddleBtm {
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-bottom:1px solid #e2e2e2;
            border-radius: 0 0 4px 4px;
            background-clip: padding-box;
            border-spacing: 0;
        }

    </style>
@endsection

@section('content')

    @if(Session::has('rent_success'))
        {{ Session::forget('rent_success') }}
        <div class="text-center col-md-12" >

            <button class="btn btn-success"  onclick="window.print() ">Print Receipt</button>

            <table width="100%" bgcolor="#ffffff" border="0" cellpadding="10" cellspacing="0">
                <tr>
                    <td>
                        <!--[if (gte mso 9)|(IE)]>
                        <table width="600"  align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                        <![endif]-->
                        <table bgcolor="#ffffff" class="content"  style="margin:0 auto" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" mc:edit="headerBrand" id="templateContainerHeader">

                                    <p style="text-align:center;margin:0;padding:0;">
                                        <img src="https://bikerent.nyc/wp-content/uploads/2015/05/Bike-Rent-NYC_png-800x.png" width="150px" style="display:inline-block; />
                        </p>

                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainer">
                                            <tr>
                                                <td valign="top" class="bodyContent" mc:edit="body_content_01">
                                                    <h1 style=" margin-bottom: -10px;text-align: center" ><strong>BIKE RENTALS & BIKE TOURS</strong></h1>
                                                </td>
                                            </tr>
                                            </td>
                                            </tr>
                                            <tr>
                                            <tr>
                                                <td><p style="text-align: center"><strong><br><br>Central Park Bike Tours<br> address: 203 W 58th St<br>phone: (212) 541-8759 <br>email: reservations@bikerent.nyc
                                            <br><br>
                                    </p></td></tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerImageFull" style="min-height:15px;">
                                    <tr>
                                        <td valign="top" class="bodyContentImageFull" mc:edit="body_content_01">
                                            <p style="text-align:center;margin:0;padding:0;float:right;">
                                                <img src="https://eastriverparkbikerental.com/images/banner-email.jpg" style="display:block; margin:0; padding:0; border:0;" />
                                            </p>
                                        </td>
                                    </tr>
                                </table>                        </table>

                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <!-- BEGIN BODY // -->
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddle" class="brdBottomPadd">
                            <tr>
                                <td valign="top" style="text-align: center" class="bodyContent" mc:edit="body_content">
                                    <h3><strong>THANK YOU FOR CHOOSING OUR COMPANY</strong></h3>
                                    <p class="final">
                                        Created at: {{ $agent_rents_order['completed_at'] }}<br>
                                        Order number: <strong>PHR{{ $agent_rents_order['id']}}</strong><br>
                                        Name: {{ $agent_rents_order['customer_name'].' '. $agent_rents_order['customer_lastname']}}<br>
                                        @if($agent_rents_order['phone']) Phone Number: {{ $agent_rents_order['phone'] }}<br>@endif
                                        Email: {{ $agent_rents_order['email'] }}<br>
                                        @if($agent_rents_order['description']) Description: {{ $agent_rents_order['description'] }}<br>@endif
                                        <br>Total after Tax:<strong> ${{ number_format(floatval($agent_rents_order['price']),2) }}</strong><br></p>

                                </td>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddle" class="brdBottomPadd">
                                    <td valign="top" class="bodyContent"><br><br>
                                        <p class="marg"><strong>Activity</strong>:
                                            I have chosen to rent and participate in bike rental services (hereinafter referred to as activity,
                                            which is organized by Central Park Bike Tours. I understand that the Activity is inherently hazardous
                                            and I may be exposed to dangers and hazards, including some of the following: falls, injuries associated
                                            with a fall, injuries from lack of fitness, death, equipment failures and negligence of others. As a
                                            consequence of these risks, I may be seriously hurt or disabled or may die from the resulting injuries
                                            and my property may also be damaged. In consideration of the permission to participate in the Activity,
                                            I agree to the terms contained in this contract. I agree to follow the rules and directions for the
                                            Activity, including any New York State traffic laws and park rules</p>

                                    </td></tr>

                                    <tr>
                                        <td>
                                            <p class="marg"><strong>Liability:</strong>
                                                All adult customers assume full liability and ride at their own risk. If you feel that you
                                                or anyone in your party cannot operate a bicycle safely and competently, that person should
                                                not rent or ride a bicycle. All children are to be supervised at all times by their parents
                                                or an adult over the age of 18.
                                                Children under the age of 14 must wear a helmet pursuant to New York State Law.
                                                With the purchase of bicycle services, you hereby release and hold harmless from all
                                                liabilities, causes of action, claims and demands that may arise in any way from injury,
                                                death, loss or harm that may occur. This release does not extend to any claims for gross
                                                negligence, intentional or reckless misconduct.
                                                I acknowledge that CPBT has no control over and assumes no responsibility for the actions
                                                of any independent contractors providing any services
                                                for the Activity
                                            </p>
                                        </td>
                                    </tr>
                                    <tr><td><p class="marg">
                                                <strong>Bike Rental Insurance:</strong>
                                                Bike rental insurance is available at additional cost. Customers who had purchased Bike
                                                Rental Insurance are indemnified and protected against 50% of the cost of damages and
                                                repairs; Customers are not responsible for costs of repairs to damages bicycles during
                                                normal use, wear and tear, lost or stolen bicycle when they purchase Bike Rental Insurance.
                                                Bike Rental Insurance does not indemnify for any cost or liability that arises as a result
                                                of personal injury, coverage shall apply only to property damage. Bike Rental Insurance
                                                includes damaged bike-pick up within Central Park only.</p>
                                        </td> </tr>


                                    </tr>
                                </table>
                                <!-- // END BODY -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddleBtm">
                                        <td valign="top" class="bodyContentImage">
                                            <tr>

                                                <td width="15" align="left" valign="top" style="width:15px;margin:0;padding:0;">&nbsp;</td>

                                            </tr>
                                    </table>
                                    <!-- // END BODY -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" id="bodyCellFooter" class="unSubContent">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="templateContainerFooter">
                                        <tr>
                                            <td valign="top" width="100%" mc:edit="footer_unsubscribe">

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->


                        {{--{{ dd($agent_rents_order) }}--}}





                        {{--@if( $agent_rents_order['agent_rents_order'])Comment: {{ $agent_rents_order['agent_rents_order'] }}@endif<br><p></p>--}}




                        {{session()->forget(['id_trans'])}}

                        @else
                            <div>No Transaction!</div>
    @endif
@endsection
