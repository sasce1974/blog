@extends('layouts.app')

@section('content')
    <div class="">
        <div class="card col-md-10 col-lg-6 mx-auto px-0">
            <div class="card-header">
                User Profile
            </div>
            <div class="mb-3 mx-auto p-3 text-center">

                <img class="rounded-circle" height="150" width="150"
                     src="{{asset($user->image(150)) }}" alt="{{ $user->imageAlt }}">

                @if($user->photo)
                    <form class="text-center" action="{{route('user.photo.destroy', $user->id)}}"
                          method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger btn-sm"
                                onclick="return confirm('Are you sure?')">
                            {{__('Delete photo')}}
                        </button>
                    </form>

                @else
                    <form class="text-center mt-2 row" action="{{ route('user.photo.store', $user->id) }}"
                          method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-md-6">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                            <input type="file" class="form-control" name="image" id="image"
                                   accept="image/*" title="{{ __('Upload your photo') }}."
                                   placeholder="{{ __('Upload photo') }}">
                            @if($errors->has('image'))
                                <div class="text-danger">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </div>
                            @endif
                        <div class="text-danger" id="image_error"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <input type="text" name="alt" class="form-control form-control-sm
                                   {{$errors->has('alt') ? 'is-invalid' : ''}}"
                                   placeholder="{{ __('Short description') }}">
                            @if($errors->has('alt'))
                                <div class="text-danger">
                                    <strong>{{ $errors->first('alt') }}</strong>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-outline-success btn-sm">
                                {{ __('Upload photo') }}
                            </button>
                        </div>
                    </form>
                @endif

            </div>
            <div class="card-body mb-4 pt-3">


                <h6 class="mb-0">Name: {{ $user->name }}</h6>
                <h6 class="mb-0">Role: {{$user->role ? $user->role->name : ""}}</h6>
                <h6 class="mb-0">Email: {{ $user->email }}</h6>
                <h6 class="mb-0">Signed up: {{ $user->created_at->diffForHumans() }}</h6>
                <p><a href="{{ route('password.request') }}">
                        {{__('Request account password change')}}
                    </a>
                </p>

                @can('manage-profile', $user)
                    <a href="{{route('user.edit', $user->id)}}" class="btn btn-outline-secondary">
                        {{ __('Edit Profile') }}
                    </a>
                @endcan
            </div>

        </div>
    </div>
@endsection
