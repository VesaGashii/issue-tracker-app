@php
    use App\Enums\IssuePriority;
    use App\Enums\IssueStatus;
@endphp

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
        <p class="font-semibold">There are a few things to fix:</p>
        <ul class="mt-2 list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="project_id" class="mb-2 block text-sm font-medium text-slate-700">Project</label>
    <select id="project_id" name="project_id" required
            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
        <option value="">Choose a project</option>
        @foreach ($projects as $project)
            <option value="{{ $project->id }}" @selected((string) old('project_id', $issue?->project_id ?? $selectedProjectId ?? '') === (string) $project->id)>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="title" class="mb-2 block text-sm font-medium text-slate-700">Title</label>
    <input id="title" name="title" value="{{ old('title', $issue?->title) }}" required
           class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
    @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
    <textarea id="description" name="description" rows="7" required
              class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">{{ old('description', $issue?->description) }}</textarea>
    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-5 sm:grid-cols-3">
    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
        <select id="status" name="status" required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
            @foreach (IssueStatus::cases() as $status)
                <option value="{{ $status->value }}" @selected(old('status', $issue?->status?->value ?? IssueStatus::Open->value) === $status->value)>
                    {{ str($status->value)->replace('_', ' ')->title() }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="priority" class="mb-2 block text-sm font-medium text-slate-700">Priority</label>
        <select id="priority" name="priority" required
                class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
            @foreach (IssuePriority::cases() as $priority)
                <option value="{{ $priority->value }}" @selected(old('priority', $issue?->priority?->value ?? IssuePriority::Medium->value) === $priority->value)>
                    {{ str($priority->value)->title() }}
                </option>
            @endforeach
        </select>
        @error('priority') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="due_date" class="mb-2 block text-sm font-medium text-slate-700">Due date</label>
        <input id="due_date" type="date" name="due_date" value="{{ old('due_date', $issue?->due_date?->format('Y-m-d')) }}"
               class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 outline-none focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100">
        @error('due_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3 border-t border-slate-200 pt-6">
    <a href="{{ $issue ? route('issues.show', $issue) : route('issues.index') }}"
       class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</a>
    <button class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
        {{ $submitLabel }}
    </button>
</div>
