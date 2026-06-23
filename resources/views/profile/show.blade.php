@extends('layouts.app')

@section('title', 'My profile')

@section('content')
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Account</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">{{ $user->name }}</h1>
            <p class="mt-2 text-slate-600">{{ $user->email }}</p>
        </div>
        <div class="rounded-xl bg-indigo-50 px-4 py-3 text-sm text-indigo-800">
            Team member since {{ $user->created_at->format('M Y') }}
        </div>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ([
            ['label' => 'Owned projects', 'value' => $summary['projects']],
            ['label' => 'Project issues', 'value' => $summary['issues']],
            ['label' => 'Still open', 'value' => $summary['open']],
            ['label' => 'Overdue', 'value' => $summary['overdue']],
            ['label' => 'Assigned to me', 'value' => $summary['assigned']],
        ] as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-3xl font-bold">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div>
            <h2 class="text-lg font-semibold">Assigned to me</h2>
            <p class="mt-1 text-sm text-slate-500">Your open work across all team projects.</p>
        </div>
        <div class="mt-5 grid gap-3 md:grid-cols-2">
            @forelse ($assignedIssues as $issue)
                <a href="{{ route('issues.show', $issue) }}" class="rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                    <p class="text-xs font-medium text-indigo-600">{{ $issue->project->name }}</p>
                    <p class="mt-1 font-medium">{{ $issue->title }}</p>
                    <p class="mt-2 text-xs text-slate-500">Due {{ $issue->due_date?->format('M j, Y') ?? 'not set' }}</p>
                </a>
            @empty
                <p class="text-sm text-slate-500">You have no open assigned issues.</p>
            @endforelse
        </div>
    </section>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1.2fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <h2 class="text-lg font-semibold">Owned projects</h2>
                <p class="mt-1 text-sm text-slate-500">Projects where you control settings and deletion.</p>
            </div>
            <div class="mt-5 space-y-3">
                @forelse ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="flex items-center justify-between rounded-xl border border-slate-200 p-4 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                        <div>
                            <p class="font-medium">{{ $project->name }}</p>
                            <p class="mt-1 text-sm text-slate-500">Deadline {{ $project->deadline?->format('M j, Y') ?? 'not set' }}</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">{{ $project->issues_count }} issues</span>
                    </a>
                @empty
                    <p class="rounded-xl bg-slate-50 p-6 text-center text-sm text-slate-500">You do not own any projects yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Account settings</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-5 space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="mb-2 block text-sm font-medium">Name</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="border-t border-slate-100 pt-5">
                    <p class="text-sm font-medium">Change password</p>
                    <p class="mt-1 text-xs text-slate-500">Leave these fields blank to keep your current password.</p>
                </div>
                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium">Current password</label>
                    <input id="current_password" type="password" name="current_password" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                    @error('current_password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="mb-2 block text-sm font-medium">New password</label>
                    <input id="password" type="password" name="password" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium">Confirm new password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                </div>
                <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Save changes</button>
            </form>
        </section>
    </div>
@endsection
