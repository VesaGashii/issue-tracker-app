<article class="comment-item rounded-xl bg-slate-50 p-4" data-comment-id="{{ $comment->id }}">
    <div class="flex justify-between gap-3">
        <p class="font-medium">{{ $comment->author_name }}</p>
        <time class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</time>
    </div>
    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $comment->body }}</p>
</article>
