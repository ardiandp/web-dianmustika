<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>

    {{-- Favicon --}}
    @php $favicon = \App\Models\Setting::get('favicon'); @endphp
    @if ($favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- AdminLTE 3 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    {{-- Google Font: Source Sans Pro --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    {{-- TinyMCE WYSIWYG Editor --}}
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js" referrerpolicy="origin"></script>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    {{-- Navbar --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ url('/') }}" class="nav-link" target="_blank">
                    <i class="fas fa-globe mr-1"></i> Lihat Website
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-user-circle mr-1"></i>
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="user-header bg-primary">
                        <p>
                            {{ Auth::user()->name }}
                            <small>{{ Auth::user()->email }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat btn-block">
                            <i class="fas fa-user-cog mr-1"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-flat btn-block mt-2">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    {{-- Sidebar --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
            @php $logo = \App\Models\Setting::get('logo'); @endphp
            @if ($logo)
                <img src="{{ asset('storage/' . $logo) }}" alt="{{ config('app.name') }}" class="brand-image img-circle elevation-3" style="opacity: .8; max-height: 30px;">
            @endif
            <span class="brand-text font-weight-light"><b>{{ config('app.name') }}</b></span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <nav>
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">KONTEN</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-concierge-bell"></i>
                            <p>Layanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.service-categories.index') }}" class="nav-link {{ request()->routeIs('admin.service-categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori Layanan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.packages.index') }}" class="nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>Paket / Promo</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.locations.index') }}" class="nav-link {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>Lokasi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.galleries.index') }}" class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-images"></i>
                            <p>Galeri</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comment-dots"></i>
                            <p>Testimonial</p>
                        </a>
                    </li>

                    <li class="nav-header">ARTIKEL</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Artikel</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.article-categories.index') }}" class="nav-link {{ request()->routeIs('admin.article-categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p>Kategori Artikel</p>
                        </a>
                    </li>

                    <li class="nav-header">LAINNYA</li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Manajemen User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.faqs.index') }}" class="nav-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-question-circle"></i>
                            <p>FAQ</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Pengaturan Website</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Content Wrapper --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $title ?? 'Dashboard' }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            v1.0
        </div>
        <strong>{{ config('app.name') }} &copy; {{ date('Y') }}.</strong> All rights reserved.
    </footer>
</div>

{{-- jQuery --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

{{-- Bootstrap 4 --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- AdminLTE 3 --}}
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

{{-- TinyMCE Init --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce !== 'undefined' && document.querySelector('.js-tinymce')) {
        tinymce.init({
            selector: '.js-tinymce',
            height: 450,
            menubar: false,
            plugins: 'link lists image code table media quickbars wordcount',
            toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table media | code',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
            quickbars_insert_toolbar: 'image media table hr pagebreak',
            content_style: 'body { font-family: "Source Sans Pro", sans-serif; font-size: 14px; line-height: 1.6; color: #444; }',
            images_upload_credentials: true,
            convert_urls: false,
            relative_urls: false,
            branding: false,
            promotion: false,
            setup: function (editor) {
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
