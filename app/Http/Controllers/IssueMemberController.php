<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IssueMemberController extends Controller
{
    public function store(Issue $issue, User $user): JsonResponse
    {
        $issue->members()->syncWithoutDetaching([$user->id]);

        return response()->json([
            'message' => 'Member assigned.',
            'member' => $user->only(['id', 'name', 'email']),
        ]);
    }

    public function destroy(Issue $issue, User $user): JsonResponse
    {
        $issue->members()->detach($user);

        return response()->json([
            'message' => 'Member removed.',
            'user_id' => $user->id,
        ]);
    }
}
