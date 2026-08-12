@props(['title' => '', 'description' => '', 'active' => 'home'])

@php
    $siteName = App\Models\Setting::get('site_name', config('app.name'));
    $pageTitle = trim($title) !== '' ? trim($title).' | '.$siteName : $siteName;
    $metaDescription = trim($description) !== '' ? $description : App\Models\Setting::get('site_description', '');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $pageTitle }}</title>
        <meta name="description" content="{{ $metaDescription }}">

        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:title" content="{{ $pageTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-cream font-sans text-ink antialiased">
        <x-public.navbar :active="$active" />

        {{ $slot }}

        <x-public.footer />

        <x-public.whatsapp-button />
    </body>
</html>
