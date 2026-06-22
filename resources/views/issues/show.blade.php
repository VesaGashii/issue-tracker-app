@extends('layouts.app')

@section('title', $issue->title)

@section('content')
    <a href="{{ route('projects.show', $issue->project) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
        ← {{ $issue->project->name }}
    </a>

    <div class="mt-4 flex flex-col justify-between gap-5 sm:flex-row sm:items-start">
        <div class="max-w-3xl">
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-full bg-slate-200 px-3 py-1">{{ str($issue->status->value)->replace('_', ' ')->title() }}</span>
                <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-800">{{ str($issue->priority->value)->title() }} priority</span>
            </div>
            <h1 class="mt-4 text-3xl font-bold tracking-tight">{{ $issue->title }}</h1>
            <p class="mt-4 whitespace-pre-line leading-7 text-slate-600">{{ $issue->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('issues.edit', $issue) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold hover:bg-slate-50">Edit</a>
            <form method="POST" action="{{ route('issues.destroy', $issue) }}" onsubmit="return confirm('Delete this issue?')">
                @csrf
                @method('DELETE')
                <button class="rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[2fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Comments</h2>
                <span class="text-sm text-slate-500">{{ $issue->comments_count }} total</span>
            </div>
            <div class="mt-5 space-y-4">
                @forelse ($comments as $comment)
                    <article class="rounded-xl bg-slate-50 p-4">
                        <div class="flex justify-between gap-3">
                            <p class="font-medium">{{ $comment->author_name }}</p>
                            <time class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $comment->body }}</p>
                    </article>
                @empty
                    <p class="py-6 text-center text-sm text-slate-500">No comments yet.</p>
                @endforelse
            </div>
        </section>

        <aside class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold">Details</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-500">Project</dt>
                        <dd class="mt-1 font-medium">{{ $issue->project->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Due date</dt>
                        <dd class="mt-1 font-medium">{{ $issue->due_date?->format('M j, Y') ?? 'Not set' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold">Tags</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @forelse ($issue->tags as $tag)
                        <span class="rounded-full px-3 py-1 text-xs font-medium text-white" style="background-color: {{ $tag->color ?? '#64748b' }}">{{ $tag->name }}</span>
                    @empty
                        <p class="text-sm text-slate-500">No tags attached.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
@endsection
