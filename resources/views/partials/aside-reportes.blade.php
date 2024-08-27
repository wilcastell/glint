<!-- Reportes -->
<li class="relative">
    <button type="button"
        class="flex justify-start items-center p-2 w-full text-base font-bold text-[#626262] rounded-lg transition
        duration-75 group hover:text-green-700 {{ isCurrentRoute('/') ? 'active' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0
                1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5
                0 0 0-3 0m-9.75 0h9.75" />
        </svg>

        <a class="flex-1 text-left whitespace-nowrap p-2 w-full text-base font-semibold text-[#626262] rounded-lg
        transition duration-75 group hover:text-green-700 {{ $currentUrl === '/' ? 'active' : '' }}"
            href="{{ url('/') }}">
            Reportes</a>
    </button>

    <!-- Indicador visual-->
    <span
        class="{{ isCurrentRoute('/') ?
         'absolute right-0 top-2 w-[7px] h-[40px] bg-primary rounded-l-md' : '' }}"></span>
</li>
