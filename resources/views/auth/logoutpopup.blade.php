<!-- Popup Cerrar Sesión -->
<div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-28">
    <div class="relative bg-white rounded-lg">
        <div class="p-4 md:p-5 text-center">
            <div class="flex justify-center items-center mb-5">
                <svg width="108" height="108" viewBox="0 0 108 108" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="108" height="108" rx="54" fill="#93B540" fill-opacity="0.2" />
                    <mask id="mask0_9463_35817" style="mask-type: alpha" maskUnits="userSpaceOnUse" x="30" y="30"
                        width="48" height="48">
                        <rect x="30" y="30" width="48" height="48" fill="#D9D9D9" />
                    </mask>
                    <g mask="url(#mask0_9463_35817)">
                        <path
                            d="M40 72C38.9 72 37.9583 71.6083 37.175 70.825C36.3917 70.0417 36 69.1
                            36 68V40C36 38.9 36.3917 37.9583 37.175 37.175C37.9583 36.3917 38.9 36 40
                            36H54V40H40V68H54V72H40ZM62 64L59.25 61.1L64.35 56H48V52H64.35L59.25
                            46.9L62 44L72 54L62 64Z"
                            fill="#579E44" />
                    </g>
                </svg>
            </div>
            <h3 class="mb-5 font-semibold text-gris-azul text-2xl">
                Cerrar sesión
            </h3>
            <p class="font-normal text-gris-azul text-sm">
                ¿Estás seguro de cerrar sesión?
            </p>
            <div class="p-6">
                <form action="{{ url('logout') }}" method="POST">
                    @csrf
                    <button type="button"
                        class="py-2.5 px-5 ms-3 text-sm uppercase font-medium text-primary
                        focus:outline-none bg-white rounded-full border-none hover:bg-gray-100
                        hover:text-primary focus:z-10 focus:ring-4 focus:ring-gray-100"
                        onclick="closeModal()">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="text-white uppercase bg-green-700 hover:bg-primary focus:ring-4
                        focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm
                        inline-flex items-center px-8 py-2.5 text-center">Aceptar</button>
                </form>
            </div>
        </div>
    </div>
</div>
