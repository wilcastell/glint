<span class="font-light text-slate-200 text-[28px] hidden md:flex mx-4">|</span>
@guest
<a href="{{ url('login') }}" class="bg-verde-manzana text-white py-2 px-3 rounded-xl">Iniciar Sesión</a>
@endguest

@auth
<!-- Contenedor para el botón y el icono SVG -->
<div class="flex items-center" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
    <button type="button" class="bg-primary py-2 px-3 mr-1 text-white rounded-lg
        hover:text-white font-bold uppercase">
        <span class="sr-only">Open user menu</span>
        {{ charAt(auth()->name) }}
    </button>

    <div class="hidden md:flex items-center">
        <div class="flex-1 flex flex-col">
            <div class="text-sm font-semibold">{{ auth()->name }}</div>
            <div class="text-xs text-gris-oscuro">{{ auth()->email }}</div>
        </div>
        <!-- Icono de dropdown -->
        <svg class="w-4 h-4 text-gray-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
</div>


{{-- Dropdown --}}
<div class="hidden z-50 my-4 w-76 text-base list-none bg-white divide-y divide-gray-100 shadow  rounded-xl p-4"
    id="dropdown" data-popper-placement="bottom"
    style="position: absolute; inset: 0px auto auto 0px; margin: 0px;
        transform: translate3d(560px, 105   px, 0px);">
    <div class="flex items-center gap-3 p-3">
        <a href="#"
            class="bg-verde-manzana py-2 px-3 mr-1 text-white rounded-lg hover:text-white font-bold uppercase">
            {{ charAt(auth()->name) }}
        </a>
        <div class="font-medium">
            <h5 class="font-bold">Perfil</h5>
            <div class="text-sm text-gray-500 font-Montserrat font-normal">{{ auth()->name }}</div>
        </div>
    </div>
    <ul class="py-1 text-white flex justify-center" aria-labelledby="dropdown">
        <li class="flex">
            <a
                href="{{ url('/perfil') }}" class="block py-2 px-6 text-sm font-semibold  ml-2
                    rounded-full bg-primary my-2 font-montserrat text-16 leading-18 text-center m-2">Actualizar
                datos</a>
        </li>
        <li class="flex">
            <button hx-get="{{ url('auth/logoutpopup') }}" hx-target="#modal-body" hx-trigger="click"
                onclick="openModal()"
                class="block py-2 px-6 text-sm font-semibold  ml-2 rounded-full bg-red-500
                    my-2 font-montserrat text-16 leading-18 text-center m-2"
                type="button"> Cerrar Sesión
            </button>
        </li>
    </ul>
</div>
@endauth
