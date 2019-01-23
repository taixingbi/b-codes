@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{ URL::to('css/agent-order.css') }}">
    <link rel="stylesheet" href="{{ URL::to('css/vegas.min.css') }}">
    <style media="screen">
        footer{
            display: none;
        }
        body{
            /*background: #cac8d1;*/
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-4 center-mainl">
            <h1 class=" text-center">Add Agent</h1>
            @if(count($errors)>0)
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            @if(Session::get('location')!="203W 58th Street" || (Session::get('location')=="203W 58th Street" && Auth::user()->email=="kakajan@bikerent.nyc"))
            <form action="{{ route('agent.posAgentAddPost') }}" method="post">
                <div class="form-group">
                    <label class="" for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control">
                </div>
                <div class="form-group">
                    <label class="" for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control">
                </div>
                <div class="form-group">
                    <label class="" for="email">Phone/E-mail</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label class="" for="location">Location</label>
                    <select  class="agent-order-duration form-control" name="location" id="location" >
                        <option selected >203W 58th Street</option>
                        <option >117W 58th Street</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="" for="commission">Commission Percentage(%)</label>
                    <input type="text" id="commission" name="commission" class="form-control">
                </div>
                {{--<div class="form-group">--}}
                {{--<input type="checkbox"  required name="terms"><a href="#"> Accept Terms & Conditions of Use</a><span id="term"> *</span>--}}
                {{--</div>--}}
                <button type="submit" class="btn btn-primary">Add</button>
                {{ csrf_field() }}
            </form>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

@endsection