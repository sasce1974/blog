
<!-- Comment with nested comments-->
@foreach($comments as $comment)
<div class="d-flex mt-2">
    <!-- Parent comment-->
    <div class="flex-shrink-0"><img class="rounded-circle" width="40px" height="40px" src="https://dummyimage.com/40x40/ced4da/6c757d.jpg" alt="..." /></div>
    <div class="ms-3">
        <div class="fw-bold">{{$comment->user->name}}</div>
        {{$comment->comment}}

    <!-- Child comment -->

        @if(\Auth::check())

            @can('edit-comment', $comment)
                <a class="btn btn-link btn-sm mx-2" href="{{route('post.comment.edit', [$post->slug, $comment->id])}}">Edit</a>
            @endcan

        @can('delete-comment', $comment)
            <form class="d-inline text-right" action="{{route('post.comment.destroy', [$post->slug, $comment->id])}}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-link btn-sm text-danger py-0"
                        onclick="return confirm('Are you sure?')">
                    {{ __('Delete') }}
                </button>
            </form>
        @endcan



        <form class="my-2" method="post" action="{{ route('comment.reply', $post_slug) }}">
            @csrf
            <div class="form-group mb-0">
                <input type="text" name="comment" class="form-control form-control-sm" placeholder="Add reply" />
                <input type="hidden" name="comment_id" value="{{ $comment->id }}" />
            </div>
            <div class="form-group pt-0 text-right">
                <button type="submit" class="btn btn-sm btn-outline-success py-0">Reply</button>
            </div>
        </form>

        @endif

        @include('comment._comments', ['comments' => $comment->replies])
    </div>
</div>
@endforeach

