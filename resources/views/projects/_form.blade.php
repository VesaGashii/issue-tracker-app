@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <p class="font-semibold">Please fix the following errors:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Project name</label>
    <input id="name" name="name" value="{{ old('name', $project?->name) }}" required
           class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
    <textarea id="description" name="description" rows="6" required
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">{{ old('description', $project?->description) }}</textarea>
    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">Start date</label>
        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $project?->start_date?->format('Y-m-d')) }}"
               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
        @error('start_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="deadline" class="mb-2 block text-sm font-medium text-slate-700">Deadline</label>
        <input id="deadline" type="date" name="deadline" value="{{ old('deadline', $project?->deadline?->format('Y-m-d')) }}"
               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
        @error('deadline') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
    <a href="{{ $project ? route('projects.show', $project) : route('projects.index') }}"
       class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
    <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
        {{ $submitLabel }}
    </button>
</div>
