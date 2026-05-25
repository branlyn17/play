@php
    use App\Support\Localization\LocaleConfig;
@endphp
<!DOCTYPE html>
<html lang="{{ LocaleConfig::htmlLang() }}" dir="{{ LocaleConfig::direction() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-white antialiased">
        @yield('content')
    </body>
</html>
