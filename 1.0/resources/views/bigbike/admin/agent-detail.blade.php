@extends('layouts.master')

@section('title')
    Report
@endsection

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/admin.css') }}" >
@endsection

@section('content')

    <div class="row">
        <h2>Agent Month Details</h2><br>
        <div class="text-center">
            <h4>For Month: {{ $date_pay }}</h4><br>
            <h4>Agent: {{ $agent_pay }}</h4>
            <h4>Credit Card</h4>
            <h4>Total Revenue (credit card): ${{ $sum }}</h4>
            <h4>Total Commision Fee (credit card): ${{ $agent_sum }}</h4><br>

            <h4>Total Revenue (cash): ${{ $cash_sum }}</h4>
            <h4>Net Revenue for company (cash): ${{ number_format(0.7*(float)$cash_sum,2) }}</h4>
            <h4>Total Commision Fee (cash): $0</h4><br>
        </div>

        <div >
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Type</th>
                    <th>Total Price</th>
                    <th>Agent Charge</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php $idx = 1; ?>
                @foreach($agent_cc_rents as $agent_cc_rent)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $agent_cc_rent->payment_type }}</td>
                        <td>{{ $agent_cc_rent->total_price_after_tax }}</td>
                        <td>{{ $agent_cc_rent->agent_price_after_tax }}</td>
                        <td>{{ $agent_cc_rent->created_at }}</td>
                    </tr>
                @endforeach
                @foreach($agent_cc_tours as $agent_cc_tour)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $agent_cc_tour->payment_type }}</td>
                        <td>{{ $agent_cc_tour->total_price_after_tax }}</td>
                        <td>{{ $agent_cc_tour->agent_price_after_tax }}</td>
                        <td>{{ $agent_cc_tour->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <h3 class="text-center">Cash</h3>
        <h4 class="text-center">For Month: {{ $date_pay }}</h4><br>
        <div >
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Payment Type</th>
                    <th>Total Price</th>
                    <th>Agent Charge</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php $idx = 1; ?>
                @foreach($agent_cash_rents as $agent_cash_rent)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $agent_cash_rent->payment_type }}</td>
                        <td>{{ $agent_cash_rent->total_price_after_tax }}</td>
                        <td>{{ $agent_cash_rent->agent_price_after_tax }}</td>
                        <td>{{ $agent_cash_rent->created_at }}</td>
                    </tr>
                @endforeach
                @foreach($agent_cash_tours as $agent_cash_tour)
                    <tr>
                        <th scope="row">{{ $idx++ }}</th>
                        <td>{{ $agent_cash_tour->payment_type }}</td>
                        <td>{{ $agent_cash_tour->total_price_after_tax }}</td>
                        <td>{{ $agent_cash_tour->agent_price_after_tax }}</td>
                        <td>{{ $agent_cash_tour->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="{{ URL::to('js/admin.js') }}"></script>

@endsection
