@props(['title' => '', 'description' => '', 'active' => 'home', 'seo' => []])

@php
    $siteName = App\Models\Setting::get('site_name', config('app.name'));
    $seo = $seo ?? [];
    $pageTitle = $seo['title'] ?? (trim($title) !== '' ? trim($title).' | '.$siteName : $siteName);
    $metaDescription = $seo['description'] ?? (trim($description) !== '' ? $description : App\Models\Setting::get('site_description', ''));
    $canonical = $seo['canonical'] ?? url()->current();
    $robots = $seo['robots'] ?? 'index, follow';
    $keywords = $seo['keywords'] ?? '';
    $og = $seo['og'] ?? [
        'site_name' => $siteName,
        'locale' => 'id_ID',
        'type' => 'website',
        'title' => $pageTitle,
        'description' => $metaDescription,
        'image' => null,
        'url' => $canonical,
    ];
    $schemas = $seo['schema'] ?? [];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">
        @if ($keywords)
            <meta name="keywords" content="{{ $keywords }}">
        @endif
        <meta name="robots" content="{{ $robots }}">
        <link rel="canonical" href="{{ $canonical }}">

        @php
            $favicon = App\Models\Setting::get('favicon');
            $shareImage = $og['image']
                ?? App\Services\SeoService::absoluteImage(App\Models\Setting::get('default_og_image'))
                ?? App\Services\SeoService::absoluteImage(App\Models\Setting::get('hero_image'))
                ?? App\Services\SeoService::absoluteImage(App\Models\Setting::get('logo'));
        @endphp
        @if ($favicon)
            <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
            <link rel="apple-touch-icon" href="{{ asset('storage/' . $favicon) }}">
        @else
            <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🌸</text></svg>">
        @endif
        <meta name="theme-color" content="#6f3a43">

        <meta property="og:type" content="{{ $og['type'] }}">
        <meta property="og:site_name" content="{{ $og['site_name'] }}">
        <meta property="og:title" content="{{ $og['title'] }}">
        <meta property="og:description" content="{{ $og['description'] }}">
        <meta property="og:url" content="{{ $og['url'] }}">
        <meta property="og:locale" content="{{ $og['locale'] }}">
        @if ($shareImage)
            <meta property="og:image" content="{{ $shareImage }}">
            <meta property="og:image:alt" content="{{ $og['title'] }}">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        @endif

        <meta name="twitter:card" content="{{ $shareImage ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title" content="{{ $og['title'] }}">
        <meta name="twitter:description" content="{{ $og['description'] }}">
        @if ($shareImage)
            <meta name="twitter:image" content="{{ $shareImage }}">
        @endif

        @foreach ($schemas as $schema)
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endforeach

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-cream font-sans text-ink antialiased">
        <x-public.navbar :active="$active" />

        {{ $slot }}

        <x-public.footer />

        <x-public.ai-chat />

        @stack('scripts')
    </body>
</html>
