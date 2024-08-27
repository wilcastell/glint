@extends('layouts.app')
@section('title', 'Inicio')

@section('content-title')
<h1 class="text-2xl font-bold leading-6 text-verde-oscuro">Info</h1>
@endsection

@section('content-button')
@include('partials.errors')
@include('partials.success')
@endsection

@section('table-content')
<h2 class="text-lg pl-8">No puedes acceder a esta sección</h2>
@endsection
