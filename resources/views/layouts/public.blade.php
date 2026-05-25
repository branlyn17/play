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
        <meta name="description" content="{{ $metaDescription ?? __('public.shared.meta.default_description') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-stone-950 text-stone-50 antialiased">
        @yield('content')
    </body>
</html>
