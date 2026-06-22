<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;

class IssueCommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->latest()
            ->paginate(5);

        return response()->json([
            'html' => view('comments._list', compact('comments'))->render(),
            'next_page_url' => $comments->hasMorePages()
                ? route('issues.comments.index', ['issue' => $issue, 'page' => $comments->currentPage() + 1], false)
                : null,
        ]);
    }

    public function store(StoreCommentRequest $request, Issue $issue): JsonResponse
    {
        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'message' => 'Comment added.',
            'html' => view('comments._comment', compact('comment'))->render(),
        ], 201);
    }
}
