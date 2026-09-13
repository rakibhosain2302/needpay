<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Needpay') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="landing-hero d-flex align-items-center">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="auth-hero__brand-badge">N</span>
                        <span class="fs-4 fw-semibold">Needpay</span>
                    </div>

                    <h1 class="display-5 fw-semibold mb-3">Ride bidding, on your terms.</h1>
                    <p class="fs-5 mb-4" style="max-width: 34rem; opacity: .9;">
                        Name your fare, get real offers from nearby drivers, and pick the ride that works for you —
                        as a passenger or a driver.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-light btn-lg fw-semibold">Go to dashboard</a>
                        @else
                            <a href="{{ url('/register') }}" class="btn btn-light btn-lg fw-semibold">Create an account</a>
                            <a href="{{ url('/login') }}" class="btn btn-outline-light btn-lg">Log in</a>
                        @endauth
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="row g-3">
                        <div class="col-12 d-flex align-items-start gap-3">
                            <span class="landing-feature-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </span>
                            <div>
                                <p class="fw-semibold mb-1">Live fare negotiation</p>
                                <p class="small mb-0" style="opacity: .85;">Post a fare, get bids from nearby drivers in real time.</p>
                            </div>
                        </div>
                        <div class="col-12 d-flex align-items-start gap-3">
                            <span class="landing-feature-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </span>
                            <div>
                                <p class="fw-semibold mb-1">Verified drivers and vehicles</p>
                                <p class="small mb-0" style="opacity: .85;">Every driver and vehicle is reviewed before they can go online.</p>
                            </div>
                        </div>
                        <div class="col-12 d-flex align-items-start gap-3">
                            <span class="landing-feature-icon">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            </span>
                            <div>
                                <p class="fw-semibold mb-1">Transparent, upfront pricing</p>
                                <p class="small mb-0" style="opacity: .85;">No surprises — you agree on the fare before the ride starts.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
