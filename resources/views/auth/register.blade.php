@extends('layouts.auth', ['title' => 'Register - Needpay'])

@section('content')
    <h1 class="h3 fw-semibold mb-1">Create your account</h1>
    <p class="text-secondary mb-4">Sign up as a passenger or a driver — it takes a minute.</p>
    <div id="alert" class="alert alert-danger d-none align-items-start gap-2" role="alert">
        <svg class="flex-shrink-0 mt-1" width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <span id="alert-text"></span>
    </div>

    <form id="register-form" class="vstack gap-3" novalidate>
        <div>
            <span class="form-label d-block">I want to sign up as</span>
            <div class="segmented">
                <label class="segmented-option">
                    <input type="radio" name="account_type" value="passenger" checked>
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M10 9a3.5 3.5 0 100-7 3.5 3.5 0 000 7zm-6 8a6 6 0 1112 0 1 1 0 01-1 1H5a1 1 0 01-1-1z"/></svg>
                    <span>Passenger</span>
                </label>
                <label class="segmented-option">
                    <input type="radio" name="account_type" value="driver">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M3 13l1.5-4.5A2 2 0 016.4 7h7.2a2 2 0 011.9 1.5L17 13v3a1 1 0 01-1 1h-1a1 1 0 01-1-1v-1H6v1a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2.5-.5h9l-.9-2.7a.5.5 0 00-.48-.3H6.88a.5.5 0 00-.48.3L5.5 12.5zM6 14.5a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/></svg>
                    <span>Driver</span>
                </label>
            </div>
        </div>

        <div>
            <label for="name" class="form-label">Full name</label>
            <div class="input-icon-group">
                <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 8a3 3 0 100-6 3 3 0 000 6zm-7 8a7 7 0 1114 0 1 1 0 01-1 1H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </span>
                <input type="text" id="name" name="name" required autocomplete="name" placeholder="Enter your full name" class="form-control">
            </div>
        </div>

        <div>
            <label for="email" class="form-label">Email</label>
            <div class="input-icon-group">
                <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M2.94 6.94a2 2 0 011.41-.59h11.3a2 2 0 011.41.59L10 11.5 2.94 6.94z"/><path d="M18 8.12l-7.5 4.83a1 1 0 01-1 0L2 8.12V13a2 2 0 002 2h12a2 2 0 002-2V8.12z"/></svg>
                </span>
                <input type="email" id="email" name="email" autocomplete="email" placeholder="user@gmail.com" class="form-control">
            </div>
        </div>

        <div>
            <label for="phone" class="form-label">Phone</label>
            <div class="input-icon-group">
                <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-.464 1.489l-.983.873a1 1 0 00-.183 1.259 12.5 12.5 0 006.032 6.032 1 1 0 001.259-.183l.873-.983a1.5 1.5 0 011.489-.464l3.223.716A1.5 1.5 0 0118 16.352V17.5a1.5 1.5 0 01-1.5 1.5H15c-8.284 0-15-6.716-15-15v-.5z"/></svg>
                </span>
                <input type="text" id="phone" name="phone" autocomplete="tel" placeholder="01XXXXXXXXX" class="form-control">
            </div>
            <div class="form-text">Provide either an email or a phone number.</div>
        </div>

        <div>
            <label for="password" class="form-label">Password</label>
            <div class="input-icon-group">
                <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                </span>
                <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" placeholder="At least 8 characters" class="form-control has-trailing-icon">
                <button type="button" class="password-toggle" data-toggle-password="password">
                    <svg class="eye-open" width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.65 1.65 0 010-1.18C1.82 6.44 5.32 3.5 10 3.5s8.18 2.94 9.336 5.91a1.65 1.65 0 010 1.18C18.18 13.56 14.68 16.5 10 16.5S1.82 13.56.664 10.59zM14.5 10a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" clip-rule="evenodd"/></svg>
                    <svg class="eye-closed d-none" width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.29 10.29 0 003.3-4.38 1.65 1.65 0 000-1.18C18.18 6.44 14.68 3.5 10 3.5c-1.6 0-3.02.35-4.24.98L3.28 2.22zM7.53 6.47l1.6 1.6a2.5 2.5 0 013.44 3.44l1.6 1.6a4.5 4.5 0 00-6.64-6.64z" clip-rule="evenodd"/><path d="M2.06 9.412a10.3 10.3 0 003.79 4.245l1.15-1.15a4.5 4.5 0 01-.05-6.552L5.11 4.11a10.35 10.35 0 00-3.05 4.302 1.65 1.65 0 000 1.18z"/></svg>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <div class="input-icon-group">
                <span class="input-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                </span>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password" placeholder="Re-enter your password" class="form-control has-trailing-icon">
                <button type="button" class="password-toggle" data-toggle-password="password_confirmation">
                    <svg class="eye-open" width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.65 1.65 0 010-1.18C1.82 6.44 5.32 3.5 10 3.5s8.18 2.94 9.336 5.91a1.65 1.65 0 010 1.18C18.18 13.56 14.68 16.5 10 16.5S1.82 13.56.664 10.59zM14.5 10a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" clip-rule="evenodd"/></svg>
                    <svg class="eye-closed d-none" width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.29 10.29 0 003.3-4.38 1.65 1.65 0 000-1.18C18.18 6.44 14.68 3.5 10 3.5c-1.6 0-3.02.35-4.24.98L3.28 2.22zM7.53 6.47l1.6 1.6a2.5 2.5 0 013.44 3.44l1.6 1.6a4.5 4.5 0 00-6.64-6.64z" clip-rule="evenodd"/><path d="M2.06 9.412a10.3 10.3 0 003.79 4.245l1.15-1.15a4.5 4.5 0 01-.05-6.552L5.11 4.11a10.35 10.35 0 00-3.05 4.302 1.65 1.65 0 000 1.18z"/></svg>
                </button>
            </div>
        </div>

        <button type="submit" id="submit-btn" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 py-2">
            <span id="submit-spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
            <span id="submit-label">Create account</span>
        </button>
    </form>

    <p class="text-center text-secondary mt-4 mb-0">
        Already have an account?
        <a href="{{ url('/login') }}" class="fw-medium text-decoration-none">Log in</a>
    </p>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('[data-toggle-password]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.togglePassword);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('.eye-open').classList.toggle('d-none', isHidden);
            btn.querySelector('.eye-closed').classList.toggle('d-none', !isHidden);
        });
    });

    const form = document.getElementById('register-form');
    const alertBox = document.getElementById('alert');
    const alertText = document.getElementById('alert-text');
    const submitBtn = document.getElementById('submit-btn');
    const submitSpinner = document.getElementById('submit-spinner');
    const submitLabel = document.getElementById('submit-label');

    function showError(message) {
        alertText.textContent = message;
        alertBox.classList.remove('d-none');
        alertBox.classList.add('d-flex');
    }

    function setLoading(isLoading) {
        submitBtn.disabled = isLoading;
        submitSpinner.classList.toggle('d-none', !isLoading);
        submitLabel.textContent = isLoading ? 'Creating account...' : 'Create account';
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        alertBox.classList.add('d-none');
        alertBox.classList.remove('d-flex');
        setLoading(true);

        const payload = {
            name: form.name.value,
            account_type: form.account_type.value,
            email: form.email.value || null,
            phone: form.phone.value || null,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
        };

        try {
            const response = await fetch('/api/v1/auth/register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(payload),
            });
            const body = await response.json();

            if (!response.ok) {
                const firstError = body.errors ? Object.values(body.errors)[0][0] : body.message;
                showError(firstError || 'Registration failed.');
                return;
            }

            localStorage.setItem('needpay_token', body.data.token);
            window.location.href = '/dashboard';
        } catch (err) {
            showError('Something went wrong. Please try again.');
        } finally {
            setLoading(false);
        }
    });
</script>
@endsection
