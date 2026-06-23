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
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"
                 data-comments
                 data-index-url="{{ route('issues.comments.index', $issue) }}">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Comments</h2>
                <span class="text-sm text-slate-500"><span data-comment-count>{{ $issue->comments_count }}</span> total</span>
            </div>

            <form method="POST" action="{{ route('issues.comments.store', $issue) }}" class="mt-5 space-y-4 rounded-xl border border-slate-200 p-4" data-comment-form>
                @csrf
                <div data-comment-errors class="hidden rounded-lg bg-red-50 p-3 text-sm text-red-700"></div>
                <div>
                    <label for="author_name" class="mb-1.5 block text-sm font-medium">Your name</label>
                    <input id="author_name" name="author_name" maxlength="100" value="{{ auth()->user()->name }}"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                    <p class="mt-1 hidden text-sm text-red-600" data-field-error="author_name"></p>
                </div>
                <div>
                    <label for="body" class="mb-1.5 block text-sm font-medium">Comment</label>
                    <textarea id="body" name="body" rows="3" maxlength="5000"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100"></textarea>
                    <p class="mt-1 hidden text-sm text-red-600" data-field-error="body"></p>
                </div>
                <div class="flex justify-end">
                    <button class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">Add comment</button>
                </div>
            </form>

            <div class="mt-5 space-y-4" data-comment-list>
                @include('comments._list')
            </div>
            @if ($comments->hasMorePages())
                <button type="button" data-load-more-comments data-next-url="{{ route('issues.comments.index', ['issue' => $issue, 'page' => 2], false) }}"
                        class="mt-5 w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium hover:bg-slate-50">
                    Load more comments
                </button>
            @endif
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

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-members>
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Members</h2>
                    <button type="button" data-member-toggle class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Manage</button>
                </div>
                <div class="mt-4 space-y-2" data-assigned-members>
                    @forelse ($issue->members as $member)
                        <div data-member-card="{{ $member->id }}" class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
                            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700">
                                {{ str($member->name)->substr(0, 1)->upper() }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium">{{ $member->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $member->email }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500" data-no-members>No members assigned.</p>
                    @endforelse
                </div>

                <div class="mt-4 hidden space-y-2 border-t border-slate-100 pt-4" data-member-panel>
                    <p class="hidden rounded-lg bg-red-50 p-2 text-sm text-red-700" data-member-error></p>
                    @foreach ($availableMembers as $member)
                        <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-2 hover:bg-slate-50">
                            <span class="text-sm">
                                <span class="block font-medium">{{ $member->name }}</span>
                                <span class="text-xs text-slate-500">{{ $member->email }}</span>
                            </span>
                            <input type="checkbox"
                                   value="{{ $member->id }}"
                                   data-member-checkbox
                                   data-attach-url="{{ route('issues.members.store', [$issue, $member]) }}"
                                   data-detach-url="{{ route('issues.members.destroy', [$issue, $member]) }}"
                                   data-member-name="{{ $member->name }}"
                                   data-member-email="{{ $member->email }}"
                                   @checked($issue->members->contains($member))>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" data-tags>
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold">Tags</h2>
                    <button type="button" data-tag-toggle class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Manage</button>
                </div>
                <div class="mt-4 flex flex-wrap gap-2" data-attached-tags>
                    @forelse ($issue->tags as $tag)
                        <span data-tag-badge="{{ $tag->id }}" class="rounded-full px-3 py-1 text-xs font-medium text-white" style="background-color: {{ $tag->color ?? '#64748b' }}">{{ $tag->name }}</span>
                    @empty
                        <p class="text-sm text-slate-500" data-no-tags>No tags attached.</p>
                    @endforelse
                </div>

                <div class="mt-4 hidden space-y-2 border-t border-slate-100 pt-4" data-tag-panel>
                    <p class="hidden rounded-lg bg-red-50 p-2 text-sm text-red-700" data-tag-error></p>
                    @foreach ($availableTags as $tag)
                        <label class="flex cursor-pointer items-center justify-between rounded-lg px-2 py-2 hover:bg-slate-50">
                            <span class="flex items-center gap-2 text-sm">
                                <span class="size-3 rounded-full" style="background-color: {{ $tag->color ?? '#64748b' }}"></span>
                                {{ $tag->name }}
                            </span>
                            <input type="checkbox"
                                   value="{{ $tag->id }}"
                                   data-tag-checkbox
                                   data-attach-url="{{ route('issues.tags.store', [$issue, $tag]) }}"
                                   data-detach-url="{{ route('issues.tags.destroy', [$issue, $tag]) }}"
                                   data-tag-name="{{ $tag->name }}"
                                   data-tag-color="{{ $tag->color ?? '#64748b' }}"
                                   @checked($issue->tags->contains($tag))>
                        </label>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
@endsection
