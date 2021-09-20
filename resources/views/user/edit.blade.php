@extends('layouts.app')

@section('content')
    <div class="row m-1">
        <div class="col-lg-4">
            <div class="form-container text-center mb-4 pt-3">
                <div class="mb-3 mx-auto">
                    <img class="rounded-circle" height="150" width="150"
                         src="{{ asset($user->image(150)) }}" alt="{{$user->imageAlt}}">
                </div>
                <h4 class="mb-0">{{ $user->name }}</h4>
                <span class="text-muted d-block mb-2">
                    {{__('Role') }}: {{$user->role ? $user->role->name : "Unspecified"}}
                </span>
                <div class="progress-wrapper text-center">
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-container mt-3">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('user.update', $user->id) }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic1">{{ __('E-mail') }}</span>
                                        </div>
                                        <input name="email" class="form-control
                                            {{$errors->has('email') ? 'is-invalid' : ''}}"
                                               aria-describedby="basic1" type="text"
                                               value="{{old('email', $user->email)}}"
                                               title="Your email is required.
                                               Changing the email address is not recommended.">
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group text-left col-lg-6 mt-1">
                                    <a href="{{route('password.request')}}">{{ __('Change your password') }}</a>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic3">{{ __('Name') }}</span>
                                        </div>
                                        <input class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                               aria-describedby="basic3" type="text" name="name"
                                               value="{{old('name', $user->name)}}"
                                               title="Insert your full name">
                                        @if($errors->has('name'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">

                                @can('admin-management')
                                    <div class="form-group mx-1">
                                        <label>
                                            <select name='role_id' class='form-control d-inline'>
                                                <option>{{ __('Please choose user role') }}</option>
                                                @foreach(App\Role::all() as $role)
                                                    <option value="{{$role->id}}"
                                                        {{$user->role ? ($role->id === $user->role->id ? 'selected' : '') : null}}>
                                                        {{$role->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            User role
                                        </label>
                                    </div>
                                @endcan

                                </div>

                            </div>

                            <div class="mb-3">
                                <div class="wrapper">
                                    <div class="form-control file-upload">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                        <input type="file" id="image" name="image" accept="image/*"
                                               title="Upload your photo."/>
                                        <i class="fa fa-arrow-up"></i>
                                    </div>
                                    <span>{{ __('Upload image') }}</span>
                                </div>
                                @if($errors->has('image'))
                                    <div class="text-danger">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </div>
                                @endif
                                <div class="text-danger" id="image_error"></div>
                            </div>

                            <button type="submit" class="btn btn-success float-left">
                                {{ __('Update Profile') }}
                            </button>
                        </form>
                        @can('admin-management')
                        <form class="float-right mr-3" action="{{route('user.destroy', $user->id)}}"
                              method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')"
                                    title="Delete my account">
                                {{ __('Delete') }}
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
