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
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="title">Post content</label>
                                <textarea class="form-control {{$errors->has('content') ? 'is-invalid' : ''}}"
                                    rows="4" id="content"name="content" title="Max 2000 characters">{{ old('content') }}</textarea>
                                @if($errors->has('content'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </div>
                                @endif
                            </div>

{{--                            choose category here --}}
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
