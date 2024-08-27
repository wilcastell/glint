@extends('layouts.app')
@section('title', 'Inicio')

@section('content-title')
<h1 class="text-lg font-bold leading-6 text-black">Inicio</h1>
@endsection

@section('content-button')

@endsection

@section('table-content')
@auth
<p class="text-verde-oscuro bg-slate-200 font-bold mb-4"> {{ auth()->name }}</p>
<p class="text-verde-oscuro bg-slate-200 font-bold">{{ auth()->email }}</p>

@endauth
<h2 class="py-8 font-bold text-lg">Acá van las tablas o cualquier otro elemento html que se quiere mostrar como
    contenido
    central!</h2>

<a
    class="bg-lime-200 p-3 text-xl mb-4 pointer"
    href="{{url('/verificador-asistencia')}}">
    Ir al verificador de
</a>
<p class="my-4">
    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nam, optio.
    Est dolore necessitatibus autem nam fuga quia
    dolorum voluptatibus architecto omnis ipsum, perspiciatis, eos, officia a.
    Ipsam libero dignissimos amet?
</p>

<p>Lorem ipsum dolor sit amet consectetur adipisicing elit.
    Velit cum aperiam fugiat sequi quos aliquam alias unde sint
    totam non, nesciunt, provident temporibus, ab a iusto quidem esse vitae harum?</p>
@endsection

@section('paginacion')
<p class="py-8">Paginación 1 2 3 4 5 6</p>
@endsection
