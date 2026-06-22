@forelse ($comments as $comment)
    @include('comments._comment')
@empty
    <p class="comments-empty py-6 text-center text-sm text-slate-500">No comments yet.</p>
@endforelse
