@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div>
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Organize</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Tags</h1>
        <p class="mt-2 text-slate-600">Create labels that can be attached to issues.</p>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_2fr]">
        <form method="POST" action="{{ route('tags.store') }}" class="h-fit space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <h2 class="font-semibold">New tag</h2>
            <div>
                <label for="name" class="mb-2 block text-sm font-medium">Name</label>
                <input id="name" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
                @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="color" class="mb-2 block text-sm font-medium">Color</label>
                <input id="color" type="color" name="color" value="{{ old('color', '#6366f1') }}"
                       class="h-12 w-full rounded-xl border border-slate-300 bg-white p-1">
                @error('color') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button class="w-full rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">Add tag</button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @forelse ($tags as $tag)
                <div class="flex items-center justify-between border-b border-slate-100 p-5 last:border-0">
                    <div class="flex items-center gap-3">
                        <span class="size-3 rounded-full" style="background-color: {{ $tag->color ?? '#64748b' }}"></span>
                        <span class="font-medium">{{ $tag->name }}</span>
                    </div>
                    <a href="{{ route('issues.index', ['tag' => $tag->id]) }}" class="text-sm text-slate-500 hover:text-indigo-600">
                        {{ $tag->issues_count }} {{ Str::plural('issue', $tag->issues_count) }}
                    </a>
                </div>
            @empty
                <p class="p-10 text-center text-sm text-slate-500">No tags yet.</p>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $tags->links() }}</div>
@endsection
