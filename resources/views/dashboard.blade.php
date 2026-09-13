<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Needpay</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="dashboard-shell">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <span class="fs-4 fw-semibold">Needpay</span>
            <button id="logout-btn" class="btn btn-outline-secondary btn-sm">Log out</button>
        </div>

        <div id="content" class="card shadow-sm">
            <div class="card-body p-4">
                <p class="text-secondary mb-0">Loading your account...</p>
            </div>
        </div>
    </div>

    <script>
        const ICONS = {
            passenger: '<svg width="22" height="22" viewBox="0 0 20 20" fill="currentColor"><path d="M10 9a3.5 3.5 0 100-7 3.5 3.5 0 000 7zm-6 8a6 6 0 1112 0 1 1 0 01-1 1H5a1 1 0 01-1-1z"/></svg>',
            driver: '<svg width="22" height="22" viewBox="0 0 20 20" fill="currentColor"><path d="M3 13l1.5-4.5A2 2 0 016.4 7h7.2a2 2 0 011.9 1.5L17 13v3a1 1 0 01-1 1h-1a1 1 0 01-1-1v-1H6v1a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2.5-.5h9l-.9-2.7a.5.5 0 00-.48-.3H6.88a.5.5 0 00-.48.3L5.5 12.5zM6 14.5a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z"/></svg>',
            admin: '<svg width="22" height="22" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1l7 3.11v5.16c0 4.62-3 8.93-7 9.73-4-.8-7-5.11-7-9.73V4.11L10 1zm0 4a2 2 0 100 4 2 2 0 000-4zm-4 8a4.2 4.2 0 018 0 .8.8 0 01-.8.8H6.8a.8.8 0 01-.8-.8z" clip-rule="evenodd"/></svg>',
        };

        const TITLES = {
            passenger: 'Passenger Dashboard',
            driver: 'Driver Dashboard',
            admin: 'Admin Dashboard',
        };

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
                const role = user.account_type;

                document.body.classList.add(`theme-${role}`);

                let roleSection = '';

                if (role === 'driver' && user.driver) {
                    const d = user.driver;
                    roleSection = `
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <span class="dashboard-stat-pill">Verification: ${d.verification_status}</span>
                            <span class="dashboard-stat-pill">${d.is_online ? 'Online' : 'Offline'}</span>
                            <span class="dashboard-stat-pill">★ ${d.average_rating ?? '0.00'} (${d.rating_count ?? 0})</span>
                            <span class="dashboard-stat-pill">${d.total_trips ?? 0} trips</span>
                        </div>
                    `;
                } else if (role === 'admin' && user.roles && user.roles.length) {
                    const roleNames = user.roles.map((r) => r.name).join(', ');
                    roleSection = `
                        <div class="mt-3">
                            <span class="dashboard-stat-pill">${roleNames}</span>
                        </div>
                    `;
                }

                content.innerHTML = `
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="dashboard-role-badge">${ICONS[role] ?? ICONS.passenger}</span>
                            <div>
                                <p class="text-secondary small mb-0 text-uppercase" style="letter-spacing:.04em;">${TITLES[role] ?? 'Dashboard'}</p>
                                <h1 class="h4 fw-semibold mb-0">Welcome, ${user.name}</h1>
                            </div>
                        </div>

                        ${roleSection}

                        <dl class="row small mb-0 mt-3">
                            <dt class="col-5 col-sm-4 text-secondary fw-normal border-bottom py-2">Account type</dt>
                            <dd class="col-7 col-sm-8 fw-medium text-capitalize border-bottom py-2 mb-0">${user.account_type}</dd>
                            <dt class="col-5 col-sm-4 text-secondary fw-normal border-bottom py-2">Status</dt>
                            <dd class="col-7 col-sm-8 fw-medium text-capitalize border-bottom py-2 mb-0">${user.status}</dd>
                            <dt class="col-5 col-sm-4 text-secondary fw-normal border-bottom py-2">Email</dt>
                            <dd class="col-7 col-sm-8 fw-medium border-bottom py-2 mb-0">${user.email ?? '—'}</dd>
                            <dt class="col-5 col-sm-4 text-secondary fw-normal py-2">Phone</dt>
                            <dd class="col-7 col-sm-8 fw-medium py-2 mb-0">${user.phone ?? '—'}</dd>
                        </dl>
                    </div>
                `;
            })
            .catch(() => {
                content.innerHTML = '<div class="card-body p-4"><p class="text-danger mb-0">Could not load your account.</p></div>';
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
