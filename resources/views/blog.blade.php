@extends('layouts.app')

@section('content')

    <!-- Page content-->
    <div class="container">
        <div class="row">
            <!-- Blog entries-->
            <div class="col-lg-8">

            @if($posts->count() < 1)
                <h4>{{ __('There are no posts yet. Would you like to create the first one?') }}</h4>
                @if(\Auth::check())
                <div class="text-center">
                    <a href="{{route('post.create')}}" class="btn btn-primary btn-lg m-5">
                        {{__('CREATE POST')}}
                    </a>
                </div>
                @else
                    <h4>Please <a href="{{route('login')}}">login</a> to create new post</h4>
                @endif
            @else

                <!-- Featured blog post-->
                @if(isset($featured))
                <div class="card mb-4">
                    <a href="{{route('post.show', $featured->slug)}}">
                        <div class="card-img-top w-100 show-post-image"
                             style="background-image: url('{{$featured->image(850, 350)}}');">
                        </div>
                    </a>
                    <div class="card-body">
                        <div class="small text-muted">Created {{$featured->created_at->diffForHumans()}}
                            by {{$featured->author->name}}

                            <div class="d-inline ml-3">
                                @can('edit-post', $featured)
                                <a class="mx-1" href="{{route('post.edit', $featured->slug)}}">
                                    {{__('Edit')}}
                                </a>
                                @endcan

                                @can('delete-post', $featured)
                                <form class="d-inline p-0 m-0"
                                      action="{{route('post.destroy', $featured->slug)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger py-0"
                                            style="font-size: 100%; margin-top: -1px"
                                            onclick="return confirm('Are you sure?')">
                                        {{__('Delete')}}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                        <h2 class="card-title">{{$featured->title}}</h2>
                        <!-- Post categories-->
                        @foreach($featured->categories as $category)
                            <a class="badge bg-primary text-decoration-none link-light"
                               href="{{route('post.category', $category->id)}}">
                                {{$category->name}}
                            </a>
                        @endforeach

                        <p class="card-text">
                            {!! \Illuminate\Support\Str::limit($featured->content, 200) !!}
                        </p>
                        <a class="btn btn-primary" href="{{route('post.show', $featured->slug)}}">
                            {{__('Read more')}} ???
                        </a>
                    </div>
                </div>
                @endif

                <!-- Nested row for non-featured blog posts-->
                <div class="d-flex flex-wrap" style="gap: 15px">

                @foreach($posts as $post)
                        <div class="card mb-4 border-0 shadow listed-post">
                            <div class="listed-post-image"
                                 style="background-image: url({{$post->image(250, 150)}})">
                            </div>
                            <div class="card-body">
                                <div class="small text-muted">{{$post->created_at->format('M d Y')}}

                                    <div class="d-inline text-right">
                                        @can('edit-post', $post)
                                        <a class="mx-1" href="{{route('post.edit', $post->slug)}}">
                                            {{__('Edit')}}
                                        </a>
                                        @endcan

                                        @can('delete-post', $post)
                                        <form class="d-inline" action="{{route('post.destroy', $post->slug)}}"
                                              method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                                    style="font-size: 100%; margin-top: -3px"
                                                    onclick="return confirm('Are you sure?')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                        @endcan
                                    </div>

                                </div>
                                <h2 class="card-title h4">{{$post->title}}</h2>
                                <div class="small mb-2">
                                    <!-- Post categories-->
                                    @foreach($post->categories as $category)
                                        <a class="badge bg-secondary text-decoration-none link-light"
                                           href="{{route('post.category', $category->id)}}">
                                            {{$category->name}}
                                        </a>
                                    @endforeach
                                </div>
                                <p class="card-text">{{ Str::limit($post->content, 50) }}</p>
                                <a class="btn btn-primary" href="{{route('post.show', $post->slug)}}">
                                    {{__('Read more')}} ???
                                </a>
                            </div>
                        </div>
                @endforeach

                </div>

                <!-- Pagination-->

                    @if(method_exists($posts, 'links'))
                        <div class="w-100 d-flex flex-row justify-content-center">
                            {{$posts->links()}}
                        </div>
                    @endif
            @endif

            </div>

            <!-- Side widgets-->
            <div class="col-lg-4">
                <!-- Search widget-->
                <div class="card mb-4">
                    <div class="card-header">{{ __('Search') }}</div>
                    <div class="card-body">
                        <form action="{{ route('post.search') }}" method="get">
                            <div class="input-group">
                                <input class="form-control" type="text" name="search"
                                       placeholder="Enter search term..."
                                       aria-label="Enter search term..."
                                       aria-describedby="button-search" />
                                <button class="btn btn-primary" id="button-search" type="submit">
                                    {{ __('Go') }}
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- Categories widget-->
                <div class="card mb-4">
                    <div class="card-header">{{ __('Categories') }}</div>
                    <div class="card-body">
                        <ul class="list-unstyled d-flex flex-wrap mb-0">
                            @foreach($categories as $category)
                            <li class="w-50 text-left">
                                <a href="{{route('post.category', $category->id)}}">
                                    {{$category->name}}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Side widget-->
                {{--<div class="card mb-4">
                    <div class="card-header">Side Widget</div>
                    <div class="card-body">something here</div>
                </div>--}}
            </div>
        </div>
    </div>
@endsection
