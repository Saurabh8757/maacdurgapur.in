@extends('frontend.layout.app')

@section('custom_css')
<link rel="stylesheet" href="{{ asset('frontend/css/space_e_fic.css') }}">
@endsection

@section('content')
<div style="padding:150px 20px;text-align:center;min-height:60vh;"><h1>Space-E-Fic</h1></div>
@endsection

@section('custom_js')
<script src="{{ asset('frontend/js/space_e_fic.js') }}"></script>
@endsection
