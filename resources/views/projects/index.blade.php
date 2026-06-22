@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Workspace</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight">Projects</h1>
            <p class="mt-2 text-slate-600">Track the work, deadlines, and issues across your team.</p>
        </div>
        <a href="{{ route('projects.create') }}" class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
            New project
        </a>
    </div>

    @if ($projects->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <h2 class="font-semibold">No projects yet</h2>
            <p class="mt-2 text-sm text-slate-500">Create your first project to begin tracking issues.</p>
        </div>
    @else
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <h2 class="text-lg font-semibold group-hover:text-indigo-700">{{ $project->name }}</h2>
                        <span class="shrink-0 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                            {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                        </span>
                    </div>
                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ $project->description }}</p>
                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500">
                        <span>{{ $project->start_date?->format('M j, Y') ?? 'No start date' }}</span>
                        <span>{{ $project->deadline?->format('M j, Y') ?? 'No deadline' }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $projects->links() }}</div>
    @endif
@endsection
