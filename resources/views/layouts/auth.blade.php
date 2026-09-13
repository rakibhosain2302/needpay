<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Needpay' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container-fluid auth-shell">
        <div class="row min-vh-100">
            <div class="col-lg-6 d-none d-lg-flex flex-column justify-content-between auth-hero">
                <div class="auth-hero__blob auth-hero__blob--1"></div>
                <div class="auth-hero__blob auth-hero__blob--2"></div>

                <div class="position-relative d-flex align-items-center gap-2">
                    <span class="auth-hero__brand-badge">N</span>
                    <span class="fs-4 fw-semibold">Needpay</span>
                </div>

                <div class="position-relative">
                    <h1 class="display-6 fw-semibold">Ride bidding,<br>on your terms.</h1>
                    <p class="mt-3 mb-4" style="max-width: 26rem;">
                        Name your fare, get real offers from nearby drivers, and pick the ride that works for you.
                    </p>

                    <ul class="list-unstyled d-flex flex-column gap-3 small mb-0">
                        <li class="d-flex align-items-center gap-3">
                            <svg class="auth-hero__feature-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            Live fare negotiation with drivers
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <svg class="auth-hero__feature-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            Verified drivers and vehicles
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <svg class="auth-hero__feature-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            Transparent, upfront pricing
                        </li>
                    </ul>
                </div>

                <p class="position-relative small mb-0" style="opacity: .8;">&copy; {{ date('Y') }} Needpay. All rights reserved.</p>
            </div>

            <div class="col-lg-6 auth-form-panel">
                <div class="mx-auto w-100" style="max-width: 24rem;">
                    <div class="d-flex d-lg-none align-items-center gap-2 mb-4">
                        <span class="auth-hero__brand-badge" style="background-color: var(--np-primary); color: #fff;">N</span>
                        <span class="fs-4 fw-semibold">Needpay</span>
                    </div>

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
