<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()
            ->withCount('issues')
            ->latest()
            ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = Project::query()->create($request->validated());

        return to_route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $issues = $project->issues()
            ->with('tags')
            ->withCount('comments')
            ->latest()
            ->paginate(10);

        return view('projects.show', compact('project', 'issues'));
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return to_route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return to_route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
