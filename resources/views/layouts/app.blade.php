<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('messages.app_name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="background-color: #f3f4f6; margin: 0; font-family: sans-serif;">

    @include('components.header')

    <main style="min-height: calc(100vh - 130px); padding: 80px 20px 40px;">
        @yield('content')
    </main>

    @include('components.footer')

</body>
</html>
