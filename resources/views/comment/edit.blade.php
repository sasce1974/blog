@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row m-0">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow-lg">
                    <div class="card-header">
                        Edit comment
                    </div>
                    <div class="card-body">
                        <form action="{{route('post.comment.update', [$post->slug, $comment->id])}}" method="post">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="comment">Comment</label>
                                <textarea class="form-control {{$errors->has('comment') ? 'is-invalid' : ''}}"
                                          rows="4" id="comment" name="comment" title="Max 1000 characters">{{ old('comment', $comment->comment) }}</textarea>
                                @if($errors->has('comment'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('comment') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mt-3 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


