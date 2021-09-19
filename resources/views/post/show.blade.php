@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <!-- Post content-->
                <article>
                    <!-- Post header-->
                    <header class="mb-4">
                        <!-- Post title-->
                        <h1 class="fw-bolder mb-1">{{$post->title}}</h1>
                        <!-- Post meta content-->
                        <div class="text-muted fst-italic mb-2">Posted {{$post->created_at->diffForHumans()}} by {{$post->author->name}}

                            <span class="ml-3">
                                @can('edit-post', $post)
                                <a class="mx-1" href="{{route('post.edit', $post->slug)}}">{{__('Edit')}}</a>
                                @endcan

                                @can('delete-post', $post)
                                <form class="d-inline" action="{{route('post.destroy', $post->slug)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-link text-danger" style="margin-top: -5px" value="{{ __('Delete') }}"
                                            onclick="return confirm('Are you sure?')">
                                </form>
                                @endcan
                            </span>

                        </div>
                        <!-- Post categories-->
                        @foreach($postCategories as $category)
                        <a class="badge bg-secondary text-decoration-none link-light" href="{{route('post.category', $category->id)}}">{{$category->name}}</a>
{{--                        <a class="badge bg-secondary text-decoration-none link-light" href="#!">Freebies</a>--}}
                        @endforeach
                    </header>
                    <!-- Preview image figure-->
                    <figure class="mb-4"><img class="img-fluid rounded" src="https://dummyimage.com/900x400/ced4da/6c757d.jpg" alt="..." /></figure>
                    <!-- Post content-->
                    <section class="mb-5">
                        <p>{!! $post->content !!}</p>
                    </section>


                </article>
                <!-- Comments section-->
                @if($errors)
                    <h5 class="text-danger">{{ $errors->comment->first('comment') }}</h5>
                @endif
                <section class="mb-5">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6>Comments</h6>
                        </div>
                        <div class="card-body">
                            <!-- Comment form-->
                            @if(\Auth::check())
                            <form class="mb-4" action="{{route('post.comment.store', $post->slug)}}" method="post">
                                @csrf
                                <textarea name="comment" class="form-control" rows="3" placeholder="Join the discussion and leave a comment!"></textarea>
                                <div class="text-right my-2">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Submit</button>
                                </div>

                            </form>
                            @endif

                            @include('comment._comments', ['comments' => $post->comments, 'post_slug' => $post->slug])

                        </div>
                    </div>
                </section>

            </div>
            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">Search</div>
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                            <button class="btn btn-primary" id="button-search" type="button">Go!</button>
                        </div>
                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">Categories</div>
                    <div class="card-body">
                        <ul class="list-unstyled d-flex flex-wrap mb-0">
                            @foreach($categories as $category)
                                <li class="w-50 text-left"><a href="{{route('post.category', $category->id)}}">{{$category->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Side widget-->
                {{--<div class="card mb-4">
                    <div class="card-header">Side Widget</div>
                    <div class="card-body">You can put anything you want inside of these side widgets. They are easy to use, and feature the Bootstrap 5 card component!</div>
                </div>--}}
            </div>
        </div>
    </div>



@endsection
