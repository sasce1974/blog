@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row m-0">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow-lg">
                    <div class="card-header">
                        Edit post
                        @if($post->photo()->count() > 0)
                            <img class="float-right rounded" width="250" height="auto" src="{{$post->image(250, 150)}}" alt="{{$post->imageAlt}}">
                            <form class="text-right" action="{{route('post.photo.destroy', $post->slug)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger btn-sm" onclick="return confirm('Are you sure?')">Delete photo</button>
                            </form>
                        @endif
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
                            <div class="row">
                                @if($post->photo()->count() === 0)
                                <div class="form-group col-md-6">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                    <input type="file" id="image" class="form-control-file" name="image" accept="image/*"
                                           title="Upload your photo."/>

                                        @if($errors->has('image'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('image') }}</strong>
                                            </div>
                                        @endif
                                    <label for="image">Post image</label>
                                    <div class="text-danger" id="image_error"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="alt" class="form-control" placeholder="Image short description">
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

