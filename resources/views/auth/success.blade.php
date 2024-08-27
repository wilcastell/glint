@extends('layouts.app')
@section('title', 'Inicio')

@section('content-title')
<h1 class="text-lg font-bold leading-6 text-black">Genial</h1>
@endsection

@section('content-button')
@include('partials.errors')
@include('partials.success')
@endsection

@section('table-content')
<h1>Su solicitud fue realizada con éxito</h1>
@endsection
