@extends('layouts.auth', ['title' => 'Log in - Needpay'])

@section('content')
    <h1 class="text-xl font-semibold text-slate-900">Log in</h1>
    <p class="mt-1 text-sm text-slate-500">Use your email or phone number.</p>

    <div id="alert" class="mt-4 hidden rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

    <form id="login-form" class="mt-6 space-y-4">
        <div class="flex gap-4 text-sm">
            <label class="flex items-center gap-2">
                <input type="radio" name="login_type" value="email" checked class="text-slate-900 focus:ring-slate-900">
                Email
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="login_type" value="phone" class="text-slate-900 focus:ring-slate-900">
                Phone
            </label>
        </div>

        <div>
            <label for="identifier" class="block text-sm font-medium text-slate-700">Email or phone</label>
            <input type="text" id="identifier" name="identifier" required
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input type="password" id="password" name="password" required
                class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900">
        </div>

        <button type="submit" id="submit-btn"
            class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700 disabled:opacity-50">
            Log in
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Don't have an account? <a href="{{ url('/register') }}" class="font-medium text-slate-900 hover:underline">Register</a>
    </p>
@endsection

@section('scripts')
<script>
    const form = document.getElementById('login-form');
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
        submitBtn.textContent = 'Logging in...';

        const payload = {
            login_type: form.login_type.value,
            identifier: form.identifier.value,
            password: form.password.value,
        };

        try {
            const response = await fetch('/api/v1/auth/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                body: JSON.stringify(payload),
            });
            const body = await response.json();

            if (!response.ok) {
                const firstError = body.errors ? Object.values(body.errors)[0][0] : body.message;
                showError(firstError || 'Login failed.');
                return;
            }

            localStorage.setItem('needpay_token', body.data.token);
            window.location.href = '/dashboard';
        } catch (err) {
            showError('Something went wrong. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Log in';
        }
    });
</script>
@endsection
