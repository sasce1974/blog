@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="mx-lg-2">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-evenly">
                        <div class="card m-2 px-3 py-1 bg-light text-center">
                            <div class="small">Users</div>
                            <h4>{{$users->count()}}</h4>
                        </div>
                        <div class="card m-2 px-3 py-1 bg-light text-center">
                            <div class="small">Posts</div>
                            <h4>{{$posts->count()}}</h4>
                        </div>
                        <div class="card m-2 px-3 py-1 bg-light text-center">
                            <div class="small">Comments</div>
                            <h4>{{$comments_count}}</h4>
                        </div>
                        <div class="card m-2 px-3 py-1 bg-light text-center">
                            <div class="small">Roles</div>
                            <h4>{{$roles->count()}}</h4>
                        </div>
                        <div class="card m-2 px-3 py-1 bg-light text-center">
                            <div class="small">Categories</div>
                            <h4>{{$categories->count()}}</h4>
                        </div>
                    </div>


                        <div id="tabs">
                            <ul>
                                <li><a href="#tabs-1">Users</a></li>
                                <li><a href="#tabs-2">Posts/Comments</a></li>
                                <li><a href="#tabs-3">Post Categories</a></li>
                                <li><a href="#tabs-4">User roles</a></li>
                            </ul>
                            <div id="tabs-1">
                                @include('user._index', ['users'=> $users])
                            </div>
                            <div id="tabs-2">
                                @include('post._index', ['posts'=> $posts])
                            </div>
                            <div id="tabs-3">
                                @include('category._index', ['categories'=>$categories])
                            </div>
                            <div id="tabs-4">
                                @include('user._roles', ['roles'=>$roles])
                            </div>
                        </div>

                </div>


            </div>
        </div>
    </div>
</div>




@endsection

@section('scripts')
    <script src="{{asset('/js/jquery-3.3.1.min.js')}}"></script>
    <script defer src="{{asset('/js/jquery-ui.js')}}"></script>
    <script>
        $(function () {
            $("#tabs").tabs();
        });
    </script>

@endsection
