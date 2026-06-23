<?php

namespace App\Http\Controllers;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->validate([
            'status' => ['nullable', 'string'],
            'priority' => ['nullable', 'string'],
            'tag' => ['nullable', 'integer', 'exists:tags,id'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->when(
                in_array($filters['status'] ?? null, array_column(IssueStatus::cases(), 'value'), true),
                fn ($query) => $query->where('status', $filters['status'])
            )
            ->when(
                in_array($filters['priority'] ?? null, array_column(IssuePriority::cases(), 'value'), true),
                fn ($query) => $query->where('priority', $filters['priority'])
            )
            ->when(
                $filters['tag'] ?? null,
                fn ($query, $tagId) => $query->whereHas('tags', fn ($tagQuery) => $tagQuery->whereKey($tagId))
            )
            ->when(
                $filters['q'] ?? null,
                fn ($query, $search) => $query->where(function ($searchQuery) use ($search): void {
                    $searchQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $tags = Tag::query()->orderBy('name')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('issues._results', compact('issues'))->render(),
                'count' => $issues->total(),
            ]);
        }

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create(Request $request): View
    {
        $projects = Project::query()->orderBy('name')->get();
        $selectedProjectId = $request->integer('project') ?: null;

        return view('issues.create', compact('projects', 'selectedProjectId'));
    }

    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::query()->create($request->validated());

        return to_route('issues.show', $issue)
            ->with('success', 'Issue created.');
    }

    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags', 'members'])
            ->loadCount('comments');

        $comments = $issue->comments()->latest()->paginate(5);
        $availableTags = Tag::query()->orderBy('name')->get();
        $availableMembers = User::query()->orderBy('name')->get();

        return view('issues.show', compact('issue', 'comments', 'availableTags', 'availableMembers'));
    }

    public function edit(Issue $issue): View
    {
        $projects = Project::query()->orderBy('name')->get();

        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());

        return to_route('issues.show', $issue)
            ->with('success', 'Issue updated.');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $project = $issue->project;
        $issue->delete();

        return to_route('projects.show', $project)
            ->with('success', 'Issue deleted.');
    }
}
