@php
$configs = \App\Models\Config::first();

@endphp

<!DOCTYPE html>
<html class="html-app-elisa" data-country="BRA" data-timezone="America/Sao_Paulo" data-city="São Paulo" lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, user-scalable=no, shrink-to-fit=no">
        <title>{{ $title ?? 'Apostas Esportivas | Casa de Apostas Online' }} || {{ $configs->website_name }}</title>

        <link rel="preconnect" crossorigin href="https://fonts.gstatic.com">
        <link rel="preconnect" crossorigin href="https://imagedelivery.net">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#4169e1">

        <meta name="og:type" content="website">
        <meta property="og:url" content="{{ $configs->websiteurl }}" />
        <meta name="description" content="{{ $configs->description }}">
        <meta name="keywords" content="{{ $configs->keywords }}">
	    <link rel="icon" type="image/png" href="{{ imgBaseUrl($configs->favicon) }}" />

        <link rel="preload" as="image" href="{{ imgBaseUrl($configs->logomarca) }}">
        <link rel="dns-prefetch" crossorigin href="https://fonts.gstatic.com">
        <link rel="dns-prefetch" crossorigin href="https://imagedelivery.net">

        @vite(['client/global.css'])
        <style>
            html,body{overflow: unset !important;}
        </style>
    </head>
    <body>
        <div class="max-w-[440px] mx-auto min-h-screen flex flex-col justify-center px-6 py-12">
            <a href="/">
                <img
                    src="{{ $configs->logomarca }}"
                    class="max-h-[26px] h-auto block mx-auto"
                    alt="{{ $configs->website_name }}"
                >
            </a>

            <h2 class="text-3xl text-center font-semibold my-8">{{ $title ?? '' }}</h2>

            @if(session('success'))
                <div class="bg-emerald-600/10 rounded-lg text-emerald-500 text-center leading-5 p-5 mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div class="bg-rose-600/10 rounded-lg text-rose-500 text-center leading-5 p-5 mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="bg-white/10 rounded-lg p-6">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
