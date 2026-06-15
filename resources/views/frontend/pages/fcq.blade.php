@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/fcq.css') }}">
@endsection

@section('content')
<div style="padding:150px 20px;text-align:center;min-height:60vh;"><h1>FCQ</h1></div>
@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/fcq.js') }}"></script>
@endsection
