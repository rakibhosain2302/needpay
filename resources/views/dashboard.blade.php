<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Needpay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="mx-auto max-w-2xl px-4 py-12">
        <div class="flex items-center justify-between">
            <span class="text-2xl font-semibold tracking-tight text-slate-900">Needpay</span>
            <button id="logout-btn" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                Log out
            </button>
        </div>

        <div id="content" class="mt-8 rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-sm text-slate-500">Loading your account...</p>
        </div>
    </div>

    <script>
        const token = localStorage.getItem('needpay_token');
        const content = document.getElementById('content');

        if (!token) {
            window.location.href = '/login';
        }

        fetch('/api/v1/auth/me', {
            headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
        })
            .then(async (response) => {
                if (!response.ok) {
                    localStorage.removeItem('needpay_token');
                    window.location.href = '/login';
                    return;
                }
                const body = await response.json();
                const user = body.data;

                content.innerHTML = `
                    <h1 class="text-xl font-semibold">Welcome, ${user.name}</h1>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Account type</dt>
                            <dd class="font-medium capitalize">${user.account_type}</dd>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Status</dt>
                            <dd class="font-medium capitalize">${user.status}</dd>
                        </div>
                        <div class="flex justify-between border-b border-slate-100 py-2">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="font-medium">${user.email ?? '—'}</dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="font-medium">${user.phone ?? '—'}</dd>
                        </div>
                    </dl>
                `;
            })
            .catch(() => {
                content.innerHTML = '<p class="text-sm text-red-600">Could not load your account.</p>';
            });

        document.getElementById('logout-btn').addEventListener('click', async () => {
            await fetch('/api/v1/auth/logout', {
                method: 'POST',
                headers: { Accept: 'application/json', Authorization: `Bearer ${token}` },
            });
            localStorage.removeItem('needpay_token');
            window.location.href = '/login';
        });
    </script>
</body>
</html>
