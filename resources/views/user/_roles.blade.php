<table class="table table-striped table-bordered text-center small no-vertical-padding table-vertical-align">
    <thead class="thead-dark py-0">
    <tr>
        <th colspan="3">
            <form class="form-inline" action="{{route('role.store')}}" method="post">
                @csrf
                <input class="form-control form-control-sm w-75 mr-2 {{$errors->has('role_name') ? 'is-invalid' : ''}}"
                       type="text" name="role_name" value="{{old('role_name')}}" placeholder="Role name"
                       title="Create new role">
                @if($errors->has('role_name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('role_name') }}</strong>
                    </div>
                @endif
                <button type="submit" class="btn btn-primary btn-sm">Create</button>
            </form>
        </th>
    </tr>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    @forelse($roles as $role)
        <tr>
            <td>{{$role->id}}</td>
            <td>{{$role->name}}</td>
            <td><form action="{{route('role.destroy', $role->id)}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
    @empty

    @endforelse
    </tbody>
</table>
