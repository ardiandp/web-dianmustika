<x-guest-layout>
    @php
        if (! isset($a) || ! isset($operator) || ! isset($b)) {
            [$a, $operator, $b] = \App\Support\MathCaptcha::generate();
        }
    @endphp
    @if (session('status'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="Email"
                required
                autofocus
                autocomplete="username"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Password"
                required
                autocomplete="current-password"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input
                id="captcha"
                type="text"
                name="captcha"
                value="{{ old('captcha') }}"
                class="form-control @error('captcha') is-invalid @enderror"
                placeholder="Jawaban"
                required
                autocomplete="off"
                inputmode="numeric"
            >
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-shield-alt"></span>
                </div>
            </div>
            @error('captcha')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <span class="form-text text-muted w-100">Verifikasi: berapa hasil dari {{ $a }} {{ $operator }} {{ $b }} ?</span>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ingat saya</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </div>
        </div>
    </form>

    @if (Route::has('password.request'))
        <p class="mt-3 mb-1 text-center">
            <a href="{{ route('password.request') }}">Lupa password</a>
        </p>
    @endif
</x-guest-layout>
