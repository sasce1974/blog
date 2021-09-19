<table class="table table-striped table-bordered text-center small no-vertical-padding table-vertical-align">
    <thead class="thead-dark py-0">

    <tr>
        <th>ID</th>
        <th>Author</th>
        <th>Title</th>
        <th>Created</th>
        <th>Viewed</th>
        <th>Approve</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    @forelse($posts as $post)
        <tr class="bg-secondary text-light">
            <td>{{$post->id}}</td>
            <td>{{$post->author->name}}</td>
            <td>{{$post->title}}</td>
            <td>{{$post->created_at->diffForHumans()}}</td>
            <td>{{$post->viewed}}</td>
            <td>
                @if($post->isApproved())
                    <form action="{{route('post.disapprove', $post->id)}}" method="post">
                        @csrf
                        @method("PATCH")
                        <button type="submit" class="btn btn-danger btn-sm">Disapprove</button>
                    </form>
                @else
                    <form action="{{route('post.approve', $post->id)}}" method="post">
                        @csrf
                        @method("PATCH")
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                @endif
            </td>
            <td>
                <form action="{{route('post.destroy', $post->slug)}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        <tr>
            <td colspan="7" class="small text-left">{{$post->content}}</td>
        </tr>

        @foreach($post->allComments as $comment)
        <tr class="bg-light text-secondary">
            <td>{{$comment->id}}</td>
            <td class="text-left small" colspan="4">{{$comment->comment}}</td>
            <td>
                @if($comment->isApproved())
                    <form action="{{route('comment.disapprove', $comment->id)}}" method="post">
                        @csrf
                        @method("PATCH")
                        <button type="submit" class="btn btn-outline-danger btn-sm">Disapprove</button>
                    </form>
                @else
                    <form action="{{route('comment.approve', $comment->id)}}" method="post">
                        @csrf
                        @method("PATCH")
                        <button type="submit" class="btn btn-outline-success btn-sm">Approve</button>
                    </form>
                @endif
            </td>
            <td>
                <form action="{{route('post.comment.destroy', [$post->slug, $comment->id])}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    @empty
        <tr>
            <td colspan="7">There are no posts</td>
        </tr>
    @endforelse
    </tbody>
</table>
