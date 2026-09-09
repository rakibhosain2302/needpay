@extends('layouts.auth', ['title' => 'Register - Needpay'])

@section('content')
    <h1 class="text-xl font-semibold text-slate-900">Create an account</h1>
    <p class="mt-1 text-sm text-slate-500">Sign up as a customer or a driver.</p>

    <div id="alert" class="mt-4 hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

    <form id="register-form" class="mt-6 space-y-4">
        <div class="flex gap-4 text-sm">
            <label class="flex items-center gap-2">
                <input type="radio" name="account_type" value="customer" checked class="text-slate-900 focus:ring-slate-900">
                Customer
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="account_type" value="driver" class="text-slate-900 focus:ring-slate-900">
                Driver
            </label>
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Full name</label>
            <input type="text" id="name" name="name" required
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input type="email" id="email" name="email"
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700">Phone</label>
            <input type="text" id="phone" name="phone" placeholder="01XXXXXXXXX"
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
            <p class="mt-1 text-xs text-slate-400">Provide either an email or a phone number.</p>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input type="password" id="password" name="password" required minlength="8"
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8"
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <button type="submit" id="submit-btn"
            class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700 disabled:opacity-50">
            Create account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Already have an account? <a href="{{ url('/login') }}" class="font-medium text-slate-900 hover:underline">Log in</a>
    </p>
@endsection

@section('scripts')
<script>
    const form = document.getElementById('register-form');
    const alertBox = document.getElementById('alert');
    const submitBtn = document.getElementById('submit-btn');

    function showError(message) {
        alertBox.textContent = message;
        alertBox.classList.remove('hidden');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        alertBox.classList.add('hidden');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Creating account...';

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
            submitBtn.disabled = false;
            submitBtn.textContent = 'Create account';
        }
    });
</script>
@endsection
