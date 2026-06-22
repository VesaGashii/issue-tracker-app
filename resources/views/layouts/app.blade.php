<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker') · PRITECH</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="border-b border-slate-200 bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 font-semibold">
                <span class="grid size-9 place-items-center rounded-xl bg-indigo-600 text-sm text-white">PT</span>
                <span>Mini Issue Tracker</span>
            </a>

            <div class="flex items-center gap-2 text-sm font-medium">
                <a href="{{ route('projects.index') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('projects.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    Projects
                </a>
                <a href="{{ route('issues.index') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('issues.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    Issues
                </a>
                <a href="{{ route('tags.index') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('tags.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    Tags
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
