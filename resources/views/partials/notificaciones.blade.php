@guest
<a href="{{ url('register') }}" class="bg-verde-oscuro text-white py-2 px-3 rounded-xl">Registrarse</a>
@endguest

@auth

<!-- Notifications -->
<div class="flex items-center" id="notification-button" type="button" data-dropdown-toggle="notification-dropdown">
    <button type="button" class="bg-verde-manzana p-2 mr-1 text-white rounded-lg">
        <span class="sr-only">Notificaciones</span>
        <!-- Bell icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967
                    8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312
                    6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714
                    0m5.714 0a3 3 0 1 1-5.714 0">
            </path>
        </svg>
    </button>
    <!-- Dropdown menu -->
    <span class="text-gris-oscuro text-sm hidden md:flex">Notificaciones</span>
</div>

<div class="hidden overflow-hidden z-50 my-4 max-w-sm text-base list-none bg-white
    rounded-tl-[0px] rounded-tr-[0px] rounded-br-[20px] rounded-bl-[20px] divide-y
    divide-gray-100 shadow-lg "
    id="notification-dropdown" data-popper-placement="bottom"
    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(747px, 78px, 0px);">
    <div class="w-full max-w-md p-4 bg-white border border-gray-100 rounded sm:p-4 relative">
        <div
            class="absolute left-0 h-[91px] w-[8px] rounded-tl-none rounded-bl-none
                rounded-tr-[35px] rounded-br-[35px] bg-[#2A6439]">
        </div>
        <div class="flow-root">
            <ul role="list" class="divide-y divide-gray-200">
                <li class="py-3 sm:py-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg width="41" height="41" viewBox="0 0 41 41" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="41" height="41" rx="20.5" fill="#579E44" />
                                <path
                                    d="M18.4167 29.8698H22.5833C22.5833 31.0156 21.6458
                                        31.9531 20.5 31.9531C19.3542 31.9531 18.4167 31.0156
                                        18.4167 29.8698ZM29.875
                                        27.7865V28.8281H11.125V27.7865L13.2083
                                        25.7031V19.4531C13.2083 16.224 15.2917 13.4115 18.4167
                                        12.474V12.1615C18.4167 11.0156 19.3542 10.0781
                                        20.5 10.0781C21.6458 10.0781 22.5833
                                        11.0156 22.5833 12.1615V12.474C25.7083 13.4115 27.7917 16.224 27.7917
                                        19.4531V25.7031L29.875 27.7865ZM25.7083 19.4531C25.7083
                                        16.5365 23.4167 14.2448 20.5 14.2448C17.5833 14.2448
                                        15.2917 16.5365 15.2917 19.4531V26.7448H25.7083V19.4531Z"
                                    fill="white" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 ms-4">
                            <h4 class="text-base font-bold text-title-popup truncate">
                                Certificados
                            </h4>
                            <p class="text-sm text-text-popup truncate">
                                Se han enviado 16 certificados del programa de formación XXXXXXXXX.
                            </p>
                        </div>
                        <div class="inline-flex items-start text-text-popup font-Montserrat font-normal">
                            4 Días
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="bg-bg-readmore p-4 rounded-tl-[0px] rounded-tr-[0px] rounded-br-[20px]
        rounded-bl-[20px] text-center">
        <a href="{{ url('/notificaciones') }}"
            class="text-[#579E44] text-base font-Montserrat font-bold hover:underline cursor-pointer">Ver
            más</a>
    </div>
</div>
@endauth
