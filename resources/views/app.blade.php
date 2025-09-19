<!DOCTYPE html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, viewport-fit=cover, user-scalable=no, shrink-to-fit=no">
        <title>{{ $configs->website_name }} | Apostas Esportivas | Casa de Apostas Online</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" crossorigin href="https://fonts.gstatic.com">
        <link rel="preconnect" crossorigin href="https://imagedelivery.net">
        <link rel="preload" as="image" href="{{ imgBaseUrl($configs->logomarca) }}">

        <meta name="darkreader-lock">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="{{ $configs->primary_color }}">

        <meta name="og:type" content="website">
        <meta property="og:url" content="{{ $configs->websiteurl }}" />
        <meta name="description" content="{{ $configs->description }}">
        <meta name="keywords" content="{{ str_replace(["[", "]", "'", " "], "", $configs->keywords) }}">
	    <!-- <link rel="icon" type="image/png" href="{{ imgBaseUrl($configs->favicon) }}" /> -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
        <link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family={{ str_replace(' ', '+', $configs->font) }}&display=swap" rel="stylesheet">

        <style>
            html:root {
                --primary-color: {{ convertHexToRGB($configs->primary_color) }} !important;
                --primary-color-contrast: {{ getContrastColor($configs->primary_color) }} !important;
                --bg-color: {{ convertHexToRGB($configs->bg_color) }} !important;
                --surface-color: {{ convertHexToRGB($configs->surface_color) }} !important;
                --border-radius: {{ $configs->border_radius }} !important;
                --font: '{{ $configs->font }}' !important;
            }
        </style>

        @vite(['client/main.ts', 'client/global.css'])

        @if($configs->cf_key)
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif
    </head>
    <body>
        <div id="app">
            <div class="loading-screen fixed inset-0 z-100 flex flex-col justify-center items-center gap-10">
                <img src="{{ imgBaseUrl($configs->logomarca) }}" class="max-h-[55px] max-w-[clamp(0px,55%,340px)] block">
                <svg class="size-12 animate-spin [animation-duration:600ms] opacity-85" preserveAspectRatio="xMidYMid" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                ><circle cx="50" cy="50" fill="none" r="35" stroke="white" stroke-dasharray="164.93361431346415 56.97787143782138" stroke-width="10" transform="matrix(1,0,0,1,0,0)" style="transform: matrix(1, 0, 0, 1, 0, 0); animation-play-state: paused;"></circle></svg>
            </div>
        </div>

        <script id="configs" type="application/json">{!! json_encode($data, true) !!}</script>

        @isset($data['social']['jivo_url'])
            <script src="{{ $data['social']['jivo_url'] }}" async></script>
        @endisset

        @isset($configs->fb_pixel_id)
            <!-- Facebook Pixel Code -->
            <script>
                !function(f,b,e,v,n,t,s)
                {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '{{ $configs->fb_pixel_id }}');
                fbq('track', 'PageView');
            </script>
            <noscript>
                <img height="1" width="1" style="display:none"
                    src="https://www.facebook.com/tr?id={{ $configs->fb_pixel_id }}&ev=PageView&noscript=1"/>
            </noscript>
        @endisset

        @isset($configs->gtm_id)
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ $configs->gtm_id }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ $configs->gtm_id }}');
            </script>
        @endisset

        @isset($configs->scripts)
            @php($scripts = strip_tags($configs->scripts, '<div><span><img><p><a><b><i><u><br><strong><em><ul><ol><li><table><tr><td><th><h1><h2><h3><h4><h5><h6><blockquote><pre><code><script><style>'))

            @if (str_contains($scripts, '<'))
                {!! $scripts !!}
            @else
                <script type="text/javascript">
                    {!! $scripts !!}
                </script>
            @endif
        @endisset
    </body>
</html>
