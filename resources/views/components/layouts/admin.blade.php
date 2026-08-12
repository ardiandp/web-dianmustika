<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-cream font-sans text-ink antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen lg:flex">
            <aside
                x-show="sidebarOpen"
                x-transition
                @keydown.escape.window="sidebarOpen = false"
                class="fixed inset-y-0 left-0 z-40 w-64 border-r border-ink/10 bg-white lg:static lg:block lg:translate-x-0 lg:overflow-y-auto"
            >
                <div class="flex h-16 items-center justify-between border-b border-ink/10 px-6">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-brand-800">
                        {{ config('app.name') }}
                    </a>
                    <button type="button" class="text-ink/60 lg:hidden" @click="sidebarOpen = false">✕</button>
                </div>

                <nav class="space-y-1 px-3 py-4 text-sm">
                    <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.services.index')" :active="request()->routeIs('admin.services.*')">
                        Layanan
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.service-categories.index')" :active="request()->routeIs('admin.service-categories.*')">
                        Kategori Layanan
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.packages.index')" :active="request()->routeIs('admin.packages.*')">
                        Paket / Promo
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.locations.index')" :active="request()->routeIs('admin.locations.*')">
                        Lokasi
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.galleries.index')" :active="request()->routeIs('admin.galleries.*')">
                        Galeri
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                        Testimonial
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.articles.index')" :active="request()->routeIs('admin.articles.*')">
                        Artikel
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.article-categories.index')" :active="request()->routeIs('admin.article-categories.*')">
                        Kategori Artikel
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')">
                        FAQ
                    </x-admin.nav-link>
                    <x-admin.nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')">
                        Pengaturan Website
                    </x-admin.nav-link>
                </nav>
            </aside>

            <div x-show="sidebarOpen" x-transition @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-ink/40 lg:hidden"></div>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="flex h-16 items-center justify-between border-b border-ink/10 bg-white px-4 sm:px-6">
                    <button type="button" class="text-ink/60 lg:hidden" @click="sidebarOpen = true">☰</button>
                    <div class="hidden text-sm text-ink/60 lg:block">
                        {{ isset($title) ? $title : 'Dashboard' }}
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" class="text-sm font-medium text-brand-700 hover:text-brand-800">
                            Lihat Website
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-ink/60 hover:text-red-600">
                                Keluar
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 p-4 sm:p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
