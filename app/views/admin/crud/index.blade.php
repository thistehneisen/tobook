
@extends ('layouts.admin')

@section ('content')
    @parent
    
    <h1 class="comfortaa">List of {{ Request::segment(2) }}</h1>

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
@foreach ($model->visible as $field)
            <th>{{ studly_case($field) }}</th>
@endforeach
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
@foreach ($items as $item)
        <tr>
    @foreach ($model->visible as $field)
            <td>{{ $item->$field }}</td>
    @endforeach
            <td>
                <a href="{{ route('admin.crud.edit', ['model' => Request::segment(2), 'id' => $item->id]) }}">Edit</a> &nbsp;
                <a href="">Delete</a>
            </td>
        </tr>
@endforeach
    </tbody>
</table>
</div>

<div class="pagination">{{ $items->links() }}</div>

@stop
