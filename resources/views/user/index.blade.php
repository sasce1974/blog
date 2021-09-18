@extends('layouts.app')

@section('content')


<div class="overflow-auto flex-grow-1">
    @if($users && count($users) > 0)
        <table class='table table-striped table-bordered text-center small no-vertical-padding table-vertical-align'>
            <thead class='thead-dark py-0'>
            <tr>
                <th class="px-0 position-relative"><div>ID</div></th>
                <th class="px-0 position-relative"><div>Verified</div></th>
                <th class="px-0 position-relative"><div>Name</div></th>
                <th class="px-0 position-relative"><div>Email</div></th>
                <th class="px-0 position-relative"><div>Role</div></th>
                <th class="px-0 position-relative"><div>Signed from</div></th>
                <th class="px-0 position-relative"><div>Posts #</div></th>
                <th class="px-0 position-relative"><div>Edit</div></th>
                <th class="px-0 position-relative"><div>Delete</div></th>
            </tr>
            </thead>
            <tbody>

            @foreach($users as $user)
                <tr>
                    <td><span class="col2">{{ $user->id }}</span></td>
                    <td><span class="col5">{{ $user->email_verified_at ? $user->email_verified_at->diffForHumans() : "NO" }}</span></td>
                    <td><span class="col3"><a href="{{route('user.show', $user->id)}}">{{ $user->name }}</a></span></td>
                    <td><span class="col4">{{ $user->email }}</span></td>
                    <td><span class="col11">{{ $user->role ? $user->role->name : "Unspecified" }}</span></td>
                    <td><span class="col10">{{ $user->created_at->diffForHumans() }}</span></td>
                    <td><span class="col10">{{ $user->posts()->count() }}</span></td>
                    <td><span><a href="{{route('user.edit', $user->id)}}" class="btn btn-primary btn-sm">Edit</a> </span></td>
                    <td>
                        <form action="{{route('user.destroy', $user->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </td>
                </tr>

            @endforeach

            </tbody>
        </table>
    @else
        <h5>There are no records</h5>
    @endif
    @if(method_exists($users, 'links'))
        <div class="w-100 d-flex flex-row justify-content-center">
            {{$users->links()}}
        </div>
    @endif

</div>

@endsection
