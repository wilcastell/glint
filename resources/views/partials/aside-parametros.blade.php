<!-- Parametrización -->
<ul>
    <li class="relative">
        <button type="button"
            class="flex items-center p-2 w-full text-base font-semibold text-[#626262] rounded-lg transition
            duration-75 group hover:bg-gray-100 hover:text-green-700 pt-4
            {{ isCurrentRoute('/ciudades*') ||
             isCurrentRoute('/modulos*') ||
              isCurrentRoute('/verificador*') ||
              isCurrentRoute('/cargamasiva*') ? 'active' : '' }}"
            aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                stroke="currentColor">
                <mask id="mask0_9901_30150" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0"
                    width="24" height="24">
                    <rect width="24" height="24" fill="#626262" />
                </mask>
                <g mask="url(#mask0_9901_30150)">
                    <path
                        d="M6 20C5.45 20 4.97917 19.8042 4.5875 19.4125C4.19583 19.0208 4 18.55 4 18C4 17.45 4.19583
                        16.9792 4.5875 16.5875C4.97917 16.1958 5.45 16 6 16C6.55 16 7.02083 16.1958 7.4125
                        16.5875C7.80417 16.9792 8 17.45 8 18C8 18.55 7.80417 19.0208 7.4125 19.4125C7.02083 19.8042
                        6.55 20 6 20ZM6 14C5.45 14 4.97917 13.8042 4.5875 13.4125C4.19583 13.0208 4 12.55 4 12C4
                        11.45 4.19583 10.9792 4.5875 10.5875C4.97917 10.1958 5.45 10 6 10C6.55 10 7.02083 10.1958
                        7.4125 10.5875C7.80417 10.9792 8 11.45 8 12C8 12.55 7.80417 13.0208 7.4125 13.4125C7.02083
                        13.8042 6.55 14 6 14ZM6 8C5.45 8 4.97917 7.80417 4.5875 7.4125C4.19583 7.02083 4 6.55 4 6C4
                        5.45 4.19583 4.97917 4.5875 4.5875C4.97917 4.19583 5.45 4 6 4C6.55 4 7.02083 4.19583
                        7.4125 4.5875C7.80417 4.97917 8 5.45 8 6C8 6.55 7.80417 7.02083 7.4125 7.4125C7.02083 7.80417
                        6.55 8 6 8ZM12 8C11.45 8 10.9792 7.80417 10.5875 7.4125C10.1958 7.02083 10 6.55 10 6C10 5.45
                        10.1958 4.97917 10.5875 4.5875C10.9792 4.19583 11.45 4 12 4C12.55 4 13.0208 4.19583 13.4125
                        4.5875C13.8042 4.97917 14 5.45 14 6C14 6.55 13.8042 7.02083 13.4125 7.4125C13.0208 7.80417
                        12.55 8 12 8ZM18 8C17.45 8 16.9792 7.80417 16.5875 7.4125C16.1958 7.02083 16 6.55 16 6C16
                        5.45 16.1958 4.97917 16.5875 4.5875C16.9792 4.19583 17.45 4 18 4C18.55 4 19.0208 4.19583
                        19.4125 4.5875C19.8042 4.97917 20 5.45 20 6C20 6.55 19.8042 7.02083 19.4125 7.4125C19.0208
                        7.80417 18.55 8 18 8ZM12 14C11.45 14 10.9792 13.8042 10.5875 13.4125C10.1958 13.0208 10 12.55
                        10 12C10 11.45 10.1958 10.9792 10.5875 10.5875C10.9792 10.1958 11.45 10 12 10C12.55 10 13.0208
                        10.1958 13.4125 10.5875C13.8042 10.9792 14 11.45 14 12C14 12.55 13.8042 13.0208 13.4125
                        13.4125C13.0208 13.8042 12.55 14 12 14ZM13 20V16.925L18.525 11.425C18.675 11.275 18.8417
                        11.1667 19.025 11.1C19.2083 11.0333 19.3917 11 19.575 11C19.775 11 19.9667 11.0375 20.15
                        11.1125C20.3333 11.1875 20.5 11.3 20.65 11.45L21.575 12.375C21.7083 12.525 21.8125 12.6917
                        21.8875
                        12.875C21.9625 13.0583 22 13.2417 22 13.425C22 13.6083 21.9667 13.7958 21.9 13.9875C21.8333
                        14.1792 21.725 14.35 21.575 14.5L16.075 20H13ZM14.5 18.5H15.45L18.475 15.45L18.025 14.975L17.55
                        14.525L14.5 17.55V18.5ZM18.025 14.975L17.55 14.525L18.475 15.45L18.025 14.975Z"
                        fill="#626262" fill-opacity="0.2" />
                </g>
            </svg>

            <span class="flex-1 ml-3 text-left whitespace-nowrap">Parametrización</span>
            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414
                    0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>

        <!-- Indicador visual -->
        <span
            class="{{ isCurrentRoute('/ciudades*') || isCurrentRoute('/modulos*') ||
             isCurrentRoute('/verificador*') || isCurrentRoute('/cargamasiva*') ?
              'absolute right-0 top-2 w-[7px] h-[40px] bg-[#3A8340] rounded-l-md' : '' }}"></span>

        <ul id="dropdown-pages"
            class="{{ isCurrentRoute('/ciudades*') || isCurrentRoute('/modulos*') ||
             isCurrentRoute('/verificador*') || isCurrentRoute('/cargamasiva*') ? '' : 'hidden' }} py-2 space-y-2">

            <li>
                <a href="<?= url('/ciudades') ?>"
                    class="flex items-center py-0 pl-11 w-full text-base font-400 text-gray-500 rounded-lg
                    transition duration-75 group hover:bg-gray-100 hover:text-green-700
                    {{ $currentUrl === '/ciudades' ? 'active' : '' }}">Ciudad</a>
            </li>
            <li>
                <a href="<?= url('/modulos') ?>"
                    class="flex items-center py-0 pl-11 w-full text-base font-400 text-gray-500 rounded-lg transition
                    duration-75 group hover:bg-gray-100 hover:text-green-700
                    {{ $currentUrl === '/modulos' ? 'active' : '' }}">Módulo</a>
            </li>
            <li>
                <a href="{{ url('/verificador') }}"
                    class="flex items-center py-0 pl-11 w-full text-base font-400 text-gray-500 rounded-lg transition
                    duration-75 group hover:bg-gray-100 hover:text-green-700
                    {{ $currentUrl === '/verificador' ? 'active' : '' }}">Validador
                    de asistencia</a>
            </li>
        </ul>
    </li>
</ul>
