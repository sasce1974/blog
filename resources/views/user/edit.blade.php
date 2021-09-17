@extends('layouts.app')
@section('content')
    <div class="row m-1">
        <div class="col-lg-4">
            <div class="form-container text-center mb-4 pt-3">
{{--                @if($user->has('photo'))--}}
                @if($user->photo())
                <div class="mb-3 mx-auto">
                    {{--                    <img class="rounded-circle" height="150" src="{{ $user->avatar(100) }}" alt="User photo">--}}
                    <img class="rounded-circle" height="150" width="150" src="{{ asset($user->photo(150)) }}" alt="User photo">
                </div>
                @endif
                <h4 class="mb-0">{{ $user->name }}</h4>
                <span class="text-muted d-block mb-2">Role: {{$user->role ? $user->role->name : "Unspecified"}}</span>
                <div class="progress-wrapper text-center">
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-container mt-3">
                {{--                <h5 class="border-bottom py-2 mb-4">Account Details</h5>--}}
                <div class="row">
                    <div class="col">
                        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic1">E-mail</span>
                                        </div>
                                        <input name="email" class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}"
                                               aria-describedby="basic1" type="text" value="{{old('email', $user->email)}}" title="Your email is required. Changing the email address is not recommended.">
                                        @if($errors->has('email'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group text-left col-lg-6 mt-1">
                                    <a href="{{route('password.request')}}">Change your password</a>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic3">Name</span>
                                        </div>
                                        <input class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                               aria-describedby="basic3" type="text" name="name" value="{{old('name', $user->name)}}"
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
                                        <select name='role_id' class='form-control'>
                                            <option>Please choose user role</option>
                                            @foreach(App\Role::all() as $role)
                                                <option value="{{$role->id}}"
                                                    {{$user->role ? ($role->id === $user->role->id ? 'selected' : '') : null}}>
                                                    {{$role->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--<div class="form-group d-flex flex-grow-1 mx-1">
                                        <input type="file" class="form-control-file" name="image">
                                    </div>--}}
                                @endcan
                                </div>

                            </div>

                            <div class="mb-3">



                                <div class="wrapper">
                                    <div class="form-control file-upload">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                                        <input type="file" id="image" name="image" accept="image/*" title="Upload your photo."/>
                                        <i class="fa fa-arrow-up"></i>
                                    </div>
                                    <span>Upload image</span>
                                </div>
                            </div>
                            <div id="image_error" style="color: red"></div>
                            <button type="submit" class="btn btn-success float-left">Update Profile</button>
                        </form>
                        <form class="float-right mr-3" action="{{route('user.destroy', $user->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')"
                                    title="Delete my account">Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>

        $(document).ready(function () {
            $('#image').change(function() {
                //check file if image and size...
                let error = false;
                if(this.files && this.files[0]) {
                    if (this.files[0].size > 4194304) {
                        $("#image_error").html("File too large! Max allowed file size is 4Mb.")
                        //alert("File too large!");
                        error = true;
                    }
                    console.log(this.files[0].type);
                    if ($.inArray(this.files[0].type, ["image/gif", "image/jpeg", "image/png", "image/bmp", "image/jpg"]) < 0) {
                        //alert("File is not image!");
                        $("#image_error").html("File is not image! Please upload only gif, jpg, png and bmp images.");
                        error = true;
                    }
                    //this.files[0].size gets the size of your file.
                    //alert(this.files[0].size);
                    if(error == true){
                        $("form").first().submit(function (e) {
                            e.preventDefault();
                        });
                    }
                }
            });
        });



    </script>
@endsection

