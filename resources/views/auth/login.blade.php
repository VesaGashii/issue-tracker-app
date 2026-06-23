@extends('layouts.app')

@section('title', 'Sign in')

@section('content')
    <div class="mx-auto max-w-md">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Team access</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Sign in</h1>
        <p class="mt-2 text-slate-600">Sign in to manage projects and team work.</p>

        <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                <input id="email" type="text" inputmode="email" autocomplete="username" name="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="mb-2 block text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1">
                Remember me
            </label>
            <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Sign in</button>
        </form>

        <div class="mt-5 rounded-xl bg-indigo-50 p-4 text-sm text-indigo-900">
            Demo account: <strong>alex@example.com</strong> / <strong>password</strong>
        </div>
    </div>
@endsection
