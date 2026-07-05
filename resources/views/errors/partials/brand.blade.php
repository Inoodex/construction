<a href="{{ auth()->check() ? route('tyro-dashboard.index') : route('tyro-login.login') }}" class="error-page__brand">
    <img src="{{ get_setting('app_logo') ? asset('storage/' . get_setting('app_logo')) : asset('assets/images/logo.svg') }}"
        alt="{{ get_setting('app_name', config('app.name')) }}" />
    <span>{{ get_setting('app_name', config('app.name')) }}</span>
</a>
