<!-- Certificados -->
<li class="relative">
    <button type="button"
        class="flex items-center p-2 w-full text-base font-semibold text-[#626262] rounded-lg transition
        duration-75 group hover:bg-gray-100 hover:text-green-700
        {{ isCurrentRoute('/certificaciones*') ? 'active' : '' }}"
        aria-controls="dropdown-certificate">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
            stroke="currentColor">
            <mask id="mask0_9901_30171" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="24"
                height="24">
                <rect width="24" height="24" fill="#626262" />
            </mask>
            <g mask="url(#mask0_9901_30171)">
                <path
                    d="M9.675 13.7L10.55 10.85L8.25 9H11.1L12 6.2L12.9 9H15.75L13.425 10.85L14.3 13.7L12 11.925L9.675
                    13.7ZM6 23V15.275C5.36667 14.575 4.875 13.775 4.525 12.875C4.175 11.975 4 11.0167 4 10C4 7.76667
                    4.775 5.875 6.325 4.325C7.875 2.775 9.76667 2 12 2C14.2333 2 16.125 2.775 17.675 4.325C19.225 5.875
                    20 7.76667 20 10C20 11.0167 19.825 11.975 19.475 12.875C19.125 13.775 18.6333 14.575 18
                    15.275V23L12 21L6 23ZM12 16C13.6667 16 15.0833 15.4167 16.25 14.25C17.4167 13.0833 18 11.6667 18
                    10C18 8.33333 17.4167 6.91667 16.25 5.75C15.0833 4.58333 13.6667 4 12 4C10.3333 4 8.91667 4.58333
                    7.75 5.75C6.58333 6.91667 6 8.33333 6 10C6 11.6667 6.58333 13.0833 7.75 14.25C8.91667 15.4167
                    10.3333 16 12 16ZM8 20.025L12 19L16 20.025V16.925C15.4167 17.2583 14.7875 17.5208 14.1125
                    17.7125C13.4375 17.9042 12.7333 18 12 18C11.2667 18 10.5625 17.9042 9.8875 17.7125C9.2125 17.5208
                    8.58333 17.2583 8 16.925V20.025Z"
                    fill="#626262" />
            </g>
        </svg>

        <a href="{{ url('/certificaciones') }}"
            class="flex-1 text-left whitespace-nowrap p-2 w-full text-base font-medium text-[#626262] rounded-lg
            transition duration-75 group hover:bg-gray-100
            {{ $currentUrl === '/certificaciones' ? 'active' : '' }}">
            Certificados</a>
    </button>

    <!-- Indicador visual -->
    <span
        class="{{ isCurrentRoute('/certificaciones*') ?
         'absolute right-0 top-2 w-[7px] h-[40px] bg-[#3A8340] rounded-l-md' : '' }}"></span>
</li>
