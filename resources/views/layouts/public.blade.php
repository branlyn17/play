<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $title ?? config('app.name') }}</title>
        <meta name="description" content="{{ $metaDescription ?? 'Invitaciones digitales modernas para eventos memorables.' }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-950 text-stone-50 antialiased">
        @yield('content')
    </body>
</html>
