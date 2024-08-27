@extends('layouts.account')
@section('title', 'Recuperar contraseña')


@section('table-content')
@include('partials.errors')
@include('partials.success')
<div class="bg-gray-100 flex justify-center items-center h-screen">
    <div
        class="min-h-screen flex flex-col md:flex-row justify-center items-center max-w-6xl
            w-full mx-auto my-auto sm:p-0 p-2">
        <!-- Left section -->
        <div
            class="w-full md:w-1/2 bg-primary pt-[80px] pr-[50px] pl-[50px] pb-[80px]
                text-white content-center h-[631px] rounded-custom-br2 md:pr-[70px] md:pl-[70px]">
            <h5 class="font-semibold text-3xl mb-4 leading-10 font-century">
                Recuperar contraseña
            </h5>
            <p class="mb-8 font-montserrat font-normal text-base leading-6">
                Completa tus datos para ingresar a nuestro admin. de programas de
                formación.
            </p>

            <!-- form login -->
            <form action="{{ url('/password/email') }}" method="POST" class="mt-8">
                @csrf @method('POST')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pb-4">
                    <div class="flex flex-col md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-white" for="email_empresa">Usuario</label>
                        <input id="user"
                            class="block w-full px-4 py-3 text-gray-700 bg-[rgba(255, 255, 255, 0.3)]
                                rounded-full focus:border-blue-400 focus:ring-opacity-40
                                focus:outline-none focus:ring focus:ring-blue-300 border-0"
                            type="email" name="email" placeholder="Correo electrónico" />
                    </div>
                </div>

                <!-- Enviar correo -->
                <div class="mt-6">
                    <button
                        class="w-full px-6 py-4 text-sm font-medium tracking-wide text-white
                            capitalize transition-colors duration-300 transform bg-verde-manzana
                            rounded-full hover:bg-verde-menta focus:outline-none focus:ring
                            focus:ring-gray-300 focus:ring-opacity-50">
                        Enviar enlace
                    </button>
                </div>
                <!-- Login Link -->
                <div class="mt-6  text-center">
                    <p class="text-sm font-light text-white">
                        ¿Volver al inicio?
                        <a href="{{ url('login') }}" class="font-semibold text-primary-600 hover:underline">Iniciar
                            Sesión</a>
                    </p>
                </div>
            </form>
        </div>
        <!-- Right section -->
        <div
            class="w-full md:w-1/2 p-8 flex flex-col justify-center items-center sm:items-start
                bg-gray-50 rounded-custom-br1 h-432 text-center sm:text-left">
            <img src="{{ url('public/img/logologin.svg') }}" alt="banner-login" width="208px" height="78px" />
            <h6 class="text-lg font-century-gothic font-semibold mb-2 text-gris-oscuro pt-6">
                Administrador
            </h6>
            <h5 class="font-bold mb-4 text-negro text-4xl leading-10">
                Programa de formación
            </h5>
            <p class="text-gris-oscuro">
                Aquí podrás crear y editar nuevos módulos, ciudades y programas de
                formación, y mucho más.
            </p>
        </div>
        {{-- imagen banner --}}
        <div class="absolute bottom-0 right-0">
            <img src="{{ url('public/img/banner-login.png') }}" alt="banner-login" width="477px" height="505px"
                class="hidden md:block" />
        </div>
    </div>
</div>
@endsection
