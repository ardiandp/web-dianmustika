<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} - Login</title>

    @php $favicon = App\Models\Setting::get('favicon'); @endphp
    @if ($favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $favicon) }}">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
</head>
<body class="hold-transition login-page" style="background-color: #e9ecef;">
    <div class="login-box">
        @php
            $logo = App\Models\Setting::get('logo');
            $siteName = App\Models\Setting::get('site_name', config('app.name'));
        @endphp

        <div class="login-logo">
            <a href="/" style="color: #495057;">
                @if ($logo)
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}" style="height: 72px; width: auto; object-fit: contain;">
                @else
                    <b style="color: #86434e;">{{ $siteName }}</b>
                @endif
            </a>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Masuk ke panel administrasi</p>

                {{ $slot }}
            </div>
        </div>

        <div class="text-center mt-2" style="color: #999;">
            <small>&copy; {{ date('Y') }} {{ $siteName }}</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    @stack('scripts')
</body>
</html>
