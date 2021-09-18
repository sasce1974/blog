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
        <tr>
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
    @empty
        <tr>
            <td colspan="7">There are no posts</td>
        </tr>
    @endforelse
    </tbody>
</table>
