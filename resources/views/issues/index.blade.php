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

    <form method="GET" class="mt-8 grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:grid-cols-4">
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

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @forelse ($issues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="block border-b border-slate-100 p-5 transition last:border-0 hover:bg-slate-50">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-xs font-medium text-indigo-600">{{ $issue->project->name }}</p>
                        <h2 class="mt-1 font-semibold">{{ $issue->title }}</h2>
                        <p class="mt-1 line-clamp-1 text-sm text-slate-500">{{ $issue->description }}</p>
                    </div>
                    <div class="flex shrink-0 gap-2 text-xs font-medium">
                        <span class="rounded-full bg-slate-100 px-2.5 py-1">{{ str($issue->status->value)->replace('_', ' ')->title() }}</span>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-amber-700">{{ str($issue->priority->value)->title() }}</span>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                    @foreach ($issue->tags as $tag)
                        <span class="rounded-full px-2.5 py-1 text-white" style="background-color: {{ $tag->color ?? '#64748b' }}">{{ $tag->name }}</span>
                    @endforeach
                    <span>{{ $issue->comments_count }} comments</span>
                    <span>·</span>
                    <span>Due {{ $issue->due_date?->format('M j, Y') ?? 'not set' }}</span>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <p class="font-medium">No issues match these filters.</p>
                <a href="{{ route('issues.index') }}" class="mt-2 inline-block text-sm text-indigo-600">Clear filters</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $issues->links() }}</div>
@endsection
