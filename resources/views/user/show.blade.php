@extends('layouts.app')
@section('content')
    <div class="">
        <div class="card w-50 mx-auto">
            <div class="card-header">
                User Profile
            </div>
            <div class="mb-3 mx-auto">
                @if($user->photo()->count() > 0)
                    <img class="rounded-circle" height="150" width="150" src="{{asset($user->image(150)) }}" alt="{{ $user->photo->alt }}">
                @else
                    <form action="{{ route('user.photo.store', $user->id) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="MAX_FILE_SIZE" value="2100000" />
                        <input type="file" class="form-control-file" name="image"
                               accept="image/*" title="Upload your photo.">
                        <input type="text" name="alt" class="form-control form-control-sm"
                               placeholder="Short description">
                        <button class="btn btn-outline-success btn-sm">Save</button>
                    </form>
                @endif
            </div>
            <div class="card-body mb-4 pt-3">


                <h6 class="mb-0">Name: {{ $user->name }}</h6>
                <h6 class="mb-0">Role: {{$user->role ? $user->role->name : ""}}</h6>
                <h6 class="mb-0">Email: {{ $user->email }}</h6>
                <h6 class="mb-0">Signed up: {{ $user->created_at->diffForHumans() }}</h6>
                <p><a href="{{ route('password.request') }}"> {{__('Request account password change')}} </a></p>

                @can('manage-profile', $user)
                    <a href="{{route('user.edit', $user->id)}}" class="btn btn-outline-secondary">Edit Profile</a>
                @endcan
            </div>

        </div>
    </div>
@endsection
