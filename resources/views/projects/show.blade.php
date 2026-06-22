@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-start">
        <div class="max-w-3xl">
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">← All projects</a>
            <h1 class="mt-3 text-3xl font-bold tracking-tight">{{ $project->name }}</h1>
            <p class="mt-3 leading-7 text-slate-600">{{ $project->description }}</p>
            <div class="mt-5 flex flex-wrap gap-3 text-sm text-slate-600">
                <span class="rounded-lg bg-white px-3 py-2 ring-1 ring-slate-200">Start: {{ $project->start_date?->format('M j, Y') ?? 'Not set' }}</span>
                <span class="rounded-lg bg-white px-3 py-2 ring-1 ring-slate-200">Deadline: {{ $project->deadline?->format('M j, Y') ?? 'Not set' }}</span>
            </div>
        </div>

        @can('update', $project)
        <div class="flex gap-2">
            <a href="{{ route('projects.edit', $project) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold hover:bg-slate-50">Edit</a>
            <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project and all of its issues?')">
                @csrf
                @method('DELETE')
                <button class="rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
        @endcan
    </div>

    <section class="mt-10">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Issues</h2>
                <p class="mt-1 text-sm text-slate-500">Issues belonging to this project.</p>
            </div>
            <a href="{{ route('issues.create', ['project' => $project->id]) }}"
               class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                Add issue
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse ($issues as $issue)
                <a href="{{ route('issues.show', $issue) }}" class="block border-b border-slate-100 p-5 transition last:border-0 hover:bg-slate-50">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="font-semibold">{{ $issue->title }}</h3>
                            <p class="mt-1 line-clamp-1 text-sm text-slate-500">{{ $issue->description }}</p>
                        </div>
                        <div class="flex gap-2 text-xs font-medium">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1">{{ str($issue->status->value)->replace('_', ' ')->title() }}</span>
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-amber-700">{{ str($issue->priority->value)->title() }}</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-500">{{ $issue->comments_count }} comments · Due {{ $issue->due_date?->format('M j, Y') ?? 'not set' }}</div>
                </a>
            @empty
                <div class="p-10 text-center text-sm text-slate-500">No issues have been added to this project yet.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $issues->links() }}</div>
    </section>
@endsection
