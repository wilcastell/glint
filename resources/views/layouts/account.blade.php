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
    <main class="h-full">
        @yield('filtro')
        @yield('table-content')
        @yield('paginacion')
    </main>

    @stack('js')
    <script src="{{ url('public/js/app.min.js') }}"></script>
    <script src="{{ url('public/js/flowbite.min.js') }}"></script>
    <script src="{{ url('public/js/htmx.min.js') }}"></script>
</body>

</html>
