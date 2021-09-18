<table class="table table-striped table-bordered text-center small no-vertical-padding table-vertical-align">
    <thead class="thead-dark py-0">
    <tr>
        <th colspan="3">
            <form class="form-inline" action="{{route('category.store')}}" method="post">
                @csrf
                <input class="form-control form-control-sm w-75 mr-2 {{$errors->has('category_name') ? 'is-invalid' : ''}}"
                       type="text" name="category_name" value="{{old('category_name')}}" placeholder="Category name"
                       title="Create new category">
                @if($errors->has('category_name'))
                    <div class="invalid-feedback">
                        <strong>{{ $errors->first('category_name') }}</strong>
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
    @forelse($categories as $category)
        <tr>
            <td>{{$category->id}}</td>
            <td>{{$category->name}}</td>
            <td><form action="{{route('category.destroy', $category->id)}}" method="post">
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
