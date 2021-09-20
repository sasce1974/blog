@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row m-0">
            <div class="col-lg-8 mx-auto">
                <div class="card mb-4 shadow-lg">
                    <div class="card-body">
                        <form action="{{route('post.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="title">Post title</label>
                                <input class="form-control {{$errors->has('title') ? 'is-invalid' : ''}}"
                                       id="title" type="text" name="title" value="{{old('title')}}"
                                       title="Some memorable title here">
                                @if($errors->has('title'))
                                    <div class="text-danger">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="title">Post content</label>
                                <textarea class="form-control {{$errors->has('content') ? 'is-invalid' : ''}}"
                                    rows="4" id="content"name="content" title="Max 2000 characters">{{ old('content') }}</textarea>
                                @if($errors->has('content'))
                                    <div class="text-danger">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </div>
                                @endif
                            </div>

{{--                            choose category here --}}
                            <h6>Category</h6>
                            <div class="mb-3 d-flex flex-wrap">

                                @foreach($categories as $category)
                                    <div class="d-block pl-4 w-25">

                                        <input id="{{$category->name}}" type="checkbox" class="form-check-input" name="category_id[]" value="{{$category->id}}">
                                        <div>{{$category->name}}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row">

                                <div class="form-group col-md-6">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="2100000" />
                                    <input type="file" id="image" class="form-control-file" name="image" accept="image/*"
                                           title="Upload post photo."/>

                                    @if($errors->has('image'))
                                        <div class="text-danger">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </div>
                                    @endif
                                    <label for="image">Post image</label>
                                    <div class="text-danger" id="image_error"></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="alt" class="form-control" placeholder="Image short description">
                                </div>

                            </div>

                            <div class="text-center mt-3 pt-3 border-top">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
