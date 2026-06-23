@extends('layouts.app')

@section('title', 'Issues')

@section('content')
    <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Work queue</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">Issues</h1>
            <p class="mt-2 text-slate-600">Browse and filter issues from every project.</p>
        </div>
        <a href="{{ route('issues.create') }}" class="rounded-xl bg-indigo-600 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-700">
            New issue
        </a>
    </div>

    <form method="GET" data-issue-filters class="mt-8 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-2 xl:grid-cols-5">
        <div>
            <label for="q" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
            <div class="relative">
                <input id="q" name="q" value="{{ request('q') }}" placeholder="Title or description" autocomplete="off" data-issue-search
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 pr-9 text-sm outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                <span class="absolute right-3 top-3 hidden size-4 animate-spin rounded-full border-2 border-slate-300 border-t-indigo-600" data-search-spinner></span>
            </div>
        </div>
        <div>
            <label for="status" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">All statuses</option>
                @foreach (App\Enums\IssueStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                        {{ str($status->value)->replace('_', ' ')->title() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="priority" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Priority</label>
            <select id="priority" name="priority" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">All priorities</option>
                @foreach (App\Enums\IssuePriority::cases() as $priority)
                    <option value="{{ $priority->value }}" @selected(request('priority') === $priority->value)>
                        {{ str($priority->value)->title() }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tag" class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Tag</label>
            <select id="tag" name="tag" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">All tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected((string) request('tag') === (string) $tag->id)>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button class="flex-1 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">Filter</button>
            <a href="{{ route('issues.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium hover:bg-slate-50">Clear</a>
        </div>
    </form>

    <div data-issue-results aria-live="polite">
        @include('issues._results')
    </div>
@endsection
