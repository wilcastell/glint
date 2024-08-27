@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('content-title')
<h1 class="text-verde-oscuro text-center font-bold font-century m-auto  rounded-xl">Restablecer contraseña</h1>
@endsection

@section('content-button')
@include('partials.errors')
@include('partials.success')

@endsection

@section('table-content')
<div class="container w-2/4 m-auto">
    <form action="{{ url('/password/reset') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="my-4">
            <label for="password" class="block text-gray-700 font-bold mb-2">Nueva contraseña</label>
            <input
                type="password"
                name="password"
                placeholder="Nueva contraseña"
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                focus:outline-none focus:shadow-outline">
        </div>
        <div class="my-4">
            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirmar contraseña</label>
            <input
                type="password"
                name="password_confirmation"
                placeholder="Confirmar contraseña"
                class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                focus:outline-none focus:shadow-outline">
        </div>
        <div class="flex items-center justify-center">
            <button
                type="submit"
                class="text-white uppercase bg-green-700 hover:bg-green-700 focus:ring-4
                focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm inline-flex
                items-center px-8 py-2.5 text-center">Restablecer contraseña</button>
        </div>
    </form>
</div>
@endsection
