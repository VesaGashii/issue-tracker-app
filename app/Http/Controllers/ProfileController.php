<?php

namespace App\Http\Controllers;

use App\Enums\IssueStatus;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Issue;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $projects = $user->ownedProjects()
            ->withCount('issues')
            ->latest()
            ->get();

        $summary = [
            'projects' => $projects->count(),
            'issues' => Issue::query()->whereIn('project_id', $projects->pluck('id'))->count(),
            'open' => Issue::query()
                ->whereIn('project_id', $projects->pluck('id'))
                ->where('status', '!=', IssueStatus::Closed)
                ->count(),
            'overdue' => Issue::query()
                ->whereIn('project_id', $projects->pluck('id'))
                ->where('status', '!=', IssueStatus::Closed)
                ->whereDate('due_date', '<', today())
                ->count(),
        ];

        return view('profile.show', compact('user', 'projects', 'summary'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->toString();
        }

        $request->user()->update($data);

        return to_route('profile.show')->with('success', 'Profile updated.');
    }
}
