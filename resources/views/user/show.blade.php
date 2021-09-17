@extends('layouts.app')
@section('content')
    <div class="">
        <div class="card w-50 mx-auto">
            <div class="mb-3 mx-auto">
                @if($user->photo())
                    <img class="rounded-circle" height="150" width="150" src="{{ $user->photo(150) }}" alt="User photo">
                @endif
            </div>
            <div class="card-body text-center mb-4 pt-3">


                <h4 class="mb-0">Name: {{ $user->name }}</h4>
                <h4 class="mb-0">Role: {{$user->role ? $user->role->name : ""}}</h4>
                <h4 class="mb-0">Email: {{ $user->email }}</h4>
                <h4 class="mb-0">Signed up: {{ $user->created_at->diffForHumans() }}</h4>

                @can('manage-profile', $user)
                    <a href="{{route('user.edit', $user->id)}}" class="btn btn-outline-secondary">Edit Profile</a>
                @endcan
            </div>

        </div>
    </div>
@endsection
