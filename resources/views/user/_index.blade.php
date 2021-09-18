
<div class="overflow-auto flex-grow-1">
    @if($users && count($users) > 0)
        <table class='table table-striped table-bordered text-center small no-vertical-padding table-vertical-align'>
            <thead class='thead-dark py-0'>
            <tr>
{{--Create New User --}}
                    <form action="{{route('user.store')}}" method="post">
                        @csrf
                        <th colspan="2">
                            <input class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}"
                                   type="text" name="name" value="{{old('name')}}"
                                   title="Insert full name" required autocomplete="name" placeholder="Full Name">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </div>
                            @endif
                        </th>
                        <th colspan="2">
                            <input name="email" class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}"
                                   type="email" value="{{old('email')}}" title="Email is required."
                                   required autocomplete="email" placeholder="Email">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </div>
                            @endif
                        </th>

                        <th colspan="2">
                            <input name="password" class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}"
                                   type="password" value="{{old('password')}}" title="Password is required, min 8 characters."
                                   required autocomplete="new-password" placeholder="Password">
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </div>
                            @endif
                        </th>

                        <th>
                            <input name="password_confirmation" class="form-control {{$errors->has('password_confirmation') ? 'is-invalid' : ''}}"
                                   type="password" value="{{old('password_confirmation')}}" title="Repeat the password"
                                   required autocomplete="new-password" placeholder="Repeat password">
                            @if($errors->has('password_confirmation'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </div>
                            @endif
                        </th>

                        <th>
                            <select name="role_id" class="form-control {{$errors->has('role_id') ? 'is-invalid' : ''}}"
                                    title="Assign a role">
                                <option>Assign a role</option>
                                @forelse($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @empty
                                    <option>There are no roles</option>
                                @endforelse
                            </select>
                            @if($errors->has('role_id'))
                                <div class="invalid-feedback">
                                    <strong>{{ $errors->first('role_id') }}</strong>
                                </div>
                            @endif
                        </th>

                        <th>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </th>

                    </form>

            </tr>
{{--            End of Create New User Form--}}

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
