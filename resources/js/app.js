import 'bootstrap/dist/js/bootstrap.bundle.min.js';

function applyColorScheme() {
    const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
}

applyColorScheme();
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applyColorScheme);
