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
