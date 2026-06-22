<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->withCount('issues')
            ->orderBy('name')
            ->paginate(20);

        return view('tags.index', compact('tags'));
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::query()->create($request->validated());

        return to_route('tags.index')->with('success', 'Tag added.');
    }
}
