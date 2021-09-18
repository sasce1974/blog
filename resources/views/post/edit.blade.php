@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row m-0">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow-lg">
                    <div class="card-header">
                        Edit post
                    </div>
                    <div class="card-body">
                        <form action="{{route('post.update', $post->slug)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="title">Post title</label>
                                <input class="form-control {{$errors->has('title') ? 'is-invalid' : ''}}"
                                       id="title" type="text" name="title" value="{{old('title', $post->title)}}"
                                       title="Some memorable title here">
                                @if($errors->has('title'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="title">Post content</label>
                                <textarea class="form-control {{$errors->has('content') ? 'is-invalid' : ''}}"
                                          rows="4" id="content" name="content" title="Max 2000 characters">{{ old('content', $post->content) }}</textarea>
                                @if($errors->has('content'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </div>
                                @endif
                            </div>

                            {{--                            choose category here --}}
                            <h6>Category</h6>
                            <div class="mb-3 d-flex flex-wrap">

                                @foreach($categories as $category)
                                    <div class="d-block pl-4 w-25">
                                        <input id="{{$category->name}}" type="checkbox" class="form-check-input" name="category_id[]" value="{{$category->id}}"
                                        @if(in_array($category->id, $postCategoriesArray) )
                                            checked
                                        @endif
                                        >
                                        <div>{{$category->name}}</div>
                                    </div>
                                @endforeach
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

