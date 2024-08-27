<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ url('/public/css/styles.css') }}" />
    <link rel="icon" href="{{ url('/') }}/favicon.ico" type="image/x-icon">
    <title>@yield('title', 'Page Title')</title>
</head>

<body class="bg-background-general bg-right bg-no-repeat bg-initial bg-bg-gris">

    <div class="antialiased">
        <nav aria-label="#" class="bg-white px-4 py-3 fixed left-0 right-0 top-0 z-50">
            <div class="flex flex-wrap justify-between items-center">
                <div class="flex justify-start items-center">
                    @include('partials.toggle-movil')
                </div>
                <div class="flex items-center lg:order-2 gap-0.5">
                    @include('partials.notificaciones')
                    @include('partials.perfil')
                </div>
            </div>
        </nav>
        <aside
            class="fixed top-0 left-0 z-40 w-64 h-screen pt-14 transition-transform -translate-x-full
            bg-white border-r border-white md:translate-x-0 dark:bg-gray-800"
            aria-label="Sidenav" id="drawer-navigation" aria-hidden="true">
            <div class="overflow-y-auto py-20 px-0 h-full bg-white">
                <ul class="space-y-2 sidebar ">
                    @include('partials.aside-title')
                    @include('partials.aside-reportes')
                    @include('partials.aside-parametros')
                    @include('partials.aside-programas')
                    @include('partials.aside-certificados')
                </ul>
            </div>
        </aside>
    </div>

    <main class="p-4 md:ml-64 h-auto pt-20">
        <div class="pb-10 pt-6">
            <div class="mx-3">
                <nav aria-label="#" class="lg:max-w-[970px] 2xl:max-w-[1440px] mx-auto">
                    <div
                        class="md:h-16 h-20 mx-auto container flex items-center justify-between flex-wrap
                        md:flex-nowrap">
                        <div class="md:order-1">
                            @yield('content-title')
                        </div>
                        <div class="order-2 md:order-3">
                            @yield('content-button')
                        </div>
                    </div>
                </nav>
            </div>
            @yield('filtro')
            @yield('table-content')
            @yield('paginacion')
        </div>
        <!-- Modal Cerrar Sesión -->
        <div id="myModal"
            class=" top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0  max-h-full
            overflow-x-hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden"
            tabindex="-1">
            <div class="modal-body" id="modal-body">
                <!-- El contenido se cargará aquí vía htmx --> </div>
        </div>
    </main>

    @stack('js')
    <script src="{{ url('public/js/app.min.js') }}"></script>
    <script src="{{ url('public/js/flowbite.min.js') }}"></script>
    <script src="{{ url('public/js/htmx.min.js') }}"></script>
</body>

</html>
