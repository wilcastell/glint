@extends('layouts.account')
@section('title', 'Registro')


@section('table-content')
@include('partials.errors')
@include('partials.success')
<div class="bg-gray-100 flex justify-center items-center h-screen">
    <div
        class="min-h-screen flex flex-col md:flex-row justify-center items-center max-w-6xl
            w-full mx-auto my-auto sm:p-0 p-2">
        <!-- Left section -->
        <div
            class="w-full md:w-1/2 bg-primary pt-[60px] pr-[50px] pl-[50px] pb-[80px]
                text-white content-center h-[880px] md:h-[730px] rounded-custom-br2 md:pr-[70px]
                md:pl-[70px]">
            <h5 class="font-semibold text-3xl mb-4 leading-10 font-century">
                Registro
            </h5>
            <p class="mb-8 font-montserrat font-normal text-base leading-6">
                Completa tus datos para ingresar a nuestro admin. de programas de
                formación.
            </p>

            <!-- form login -->
            <form action="{{ url('/register') }}" method="POST" class="mt-8">
                @csrf @method('POST')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                    <!-- Username -->
                    <div class="flex flex-col md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-white" for="name">Usuario</label>
                        <input id="name" placeholder="Nombre"
                            class="block w-full px-4 py-3 text-white bg-input-bg rounded-full
                                focus:border-blue-400 focus:ring-opacity-40 focus:outline-none
                                focus:ring focus:ring-blue-300 border-0 placeholder:text-white"
                            type="text" name="name"
                            value="<?= isset($_SESSION['old']['name']) ?
                                        htmlspecialchars($_SESSION['old']['name'], ENT_QUOTES, 'UTF-8') : '' ?>" />
                    </div>
                    <!-- Email -->
                    <div class="flex flex-col md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-white" for="email_empresa">Correo
                            electrónico</label>
                        <input id="user" placeholder=""
                            class="block w-full px-4 py-3 text-white bg-input-bg rounded-full
                                focus:border-blue-400 focus:ring-opacity-40 focus:outline-none
                                focus:ring focus:ring-blue-300 border-0 placeholder:text-white"
                            type="email" name="email"
                            value="<?= isset($_SESSION['old']['email']) ?
                                        htmlspecialchars($_SESSION['old']['email'], ENT_QUOTES, 'UTF-8') : '' ?>" />
                    </div>
                    <!-- Password -->
                    <div class="relative flex flex-col">
                        <div class="flex justify-between items-center">
                            <label class="block mb-2 text-sm font-medium text-white"
                                for="password">Contraseña</label>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Contraseña"
                                class="block w-full px-4 py-3 pr-12 text-white bg-input-bg
                                    rounded-full focus:border-blue-400 focus:ring-opacity-40
                                    focus:outline-none focus:ring focus:ring-blue-300 border-0
                                    placeholder:text-white" />
                            <button id="togglePassword" type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- confirm password -->
                    <div class="relative flex flex-col">
                        <div class="flex justify-between items-center">
                            <label class="block mb-2 text-sm font-medium text-white"
                                for="password_confirmation">Confirmar contraseña</label>
                        </div>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                placeholder="Confirmar contraseña"
                                class="block w-full px-4 py-3 pr-12 text-white bg-input-bg
                                    rounded-full focus:border-blue-400 focus:ring-opacity-40
                                    focus:outline-none focus:ring focus:ring-blue-300 border-0
                                    placeholder:text-white" />
                            <button id="togglePassword" type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:col-span-2 pb-4">
                    <label for="role" class="block mb-2 text-sm font-medium text-white">
                        Rol</label>
                    <select name="role" id="role"
                        class="block w-full px-4 py-3 pr-12 text-white bg-input-bg rounded-full
                            focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring
                            focus:ring-blue-300 border-0 placeholder:text-white">
                        <option value="user" class="text-black">Usuario</option>
                        <option value="admin" class="text-black">Administrador</option>
                        <option value="capacitador" class="text-black">Capacitador</option>
                        <option value="verificador" class="text-black">Verificador de asistencia</option>
                    </select>
                </div>
                <!-- Registrarse -->
                <div class="mt-1">
                    <button
                        class="w-full px-6 py-4 text-sm font-medium tracking-wide text-white
                            capitalize transition-colors duration-300 transform bg-verde-manzana
                            rounded-full hover:bg-verde-menta focus:outline-none focus:ring
                            focus:ring-gray-300 focus:ring-opacity-50">
                        Registrarse
                    </button>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm font-light text-white">
                        ¿Ya tienes una cuenta?
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

{{-- Script Password --}}
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>
@endsection
