<!-- Programas de formación -->
<li class="relative">
    <button type="button"
        class="flex items-center p-2 w-full text-base font-semibold text-[#626262] rounded-lg transition duration-75
        group hover:bg-gray-100 hover:text-green-700 pt-1
        {{ isCurrentRoute('/programas*') || isCurrentRoute('/carga-masiva*') ? 'active' : '' }}"
        aria-controls="dropdown-programas" data-collapse-toggle="dropdown-programas">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 22 22" stroke-width="1.5" stroke="currentColor"
            width="23" height="23" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 5h6m-6
                4h6M10 3v4h4V3h-4Z" />
        </svg>

        <span class="flex-1 ml-3 text-left whitespace-nowrap">Programas de <br> formación</span>
        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0
                01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
    </button>

    <!-- Indicador visual -->
    <span
        class="{{ isCurrentRoute('/programas*') || isCurrentRoute('/carga-masiva*') ? 'absolute right-0 top-2 w-[7px]
        h-[40px] bg-[#3A8340] rounded-l-md' : '' }}"></span>

    <ul id="dropdown-programas"
        class="{{ isCurrentRoute('/programas*') || isCurrentRoute('/carga-masiva*') ? '' : 'hidden' }} py-2 space-y-2">
        <li>
            <a href="<?= url('/programas') ?>"
                class="flex items-center py-0 pl-11 w-full text-base font-400 text-gray-500 rounded-lg
                transition duration-75 group hover:bg-gray-100 hover:text-green-700
                {{ $currentUrl === '/programas' ? 'active' : '' }}">Listado
                de los programas</a>
        </li>
        <li>
            <a href="{{ url('/carga-masiva') }}"
                class="flex items-center py-0 pl-11 w-full text-base font-400 text-gray-500 rounded-lg transition
                 duration-75 group hover:bg-gray-100 hover:text-green-700
                 {{ $currentUrl === '/carga-masiva' ? 'active' : '' }}">Carga
                masiva</a>
        </li>
    </ul>
</li>
