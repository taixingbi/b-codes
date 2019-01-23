@extends('layouts.master')

@section('title')
    big bike agent
@endsection
@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::to('css/jquery.timepicker.min.css') }}" >

@endsection

@section('nav-buttons')
    @include('bigbike.agent.nav-buttons')
@endsection

@section('content')
    <div class="col-md-12 mr-top">
        <dl>
            <h3>Contact</h3>
            <dd>Contact Bike Rent NYC management, marketing and business development at (917) 283-2453.
                Bike Rent NYC is committed to serving New York City residents and visitors through daily bike rentals,
                guided bike tours of Central Park and Brooklyn Bridge, and daily, monthly or annual “hop-on, hop-off” biking programs.
                Bike Rent NYC is the largest bike rental business in New York City, now serving approximately 400,000 visitors a year,
                with nearly 3000 Giant and Trek bikes, and four (4) storefronts open year-round.
            </dd>
        </dl>
    </div>

@endsection

@section('scripts')
    {{--<script src="{{ URL::to('agent-rent.js') }}"></script>--}}
@endsection