@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/showcase.css') }}">
@endsection

@section('content')
<div style="padding:150px 20px;text-align:center;min-height:60vh;"><h1>Showcase</h1></div>
@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/showcase.js') }}"></script>
@endsection
