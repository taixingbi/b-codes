@extends('layouts.master')

@section('title')
    Admin
@endsection

@section('content')

    <button type="button" class="btn btn-primary" onclick="window.location='{{ route("admin.monthly") }}'">Month Report</button>
    <button type="button" class="btn btn-primary" onclick="window.location='{{ route("admin.report") }}'">Agent Report</button>
    <br><br><br>

@endsection

