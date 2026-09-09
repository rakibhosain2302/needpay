<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Needpay' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <div class="mb-8 text-center">
            <span class="text-2xl font-semibold tracking-tight text-slate-900">Needpay</span>
            <p class="mt-1 text-sm text-slate-500">Ride bidding, on your terms.</p>
        </div>

        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
