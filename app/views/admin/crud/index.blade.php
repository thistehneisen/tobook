
@extends ('layouts.admin')

@section ('content')
    @parent
    
    <h3 class="comfortaa text-success">List of {{ Request::segment(2) }}</h3>
    @include ('el.messages')

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>&nbsp;</th>
@foreach ($model->visible as $field)
            <th>{{ studly_case($field) }}</th>
@endforeach
        </tr>
    </thead>
    <tbody>
@foreach ($items as $item)
        <tr>
            <td>
                <a href="{{ route('admin.crud.edit', ['model' => Request::segment(2), 'id' => $item->id]) }}">Edit</a> &nbsp;
                <a href="">Delete</a>
            </td>
    @foreach ($model->visible as $field)
            <td>{{ $item->$field }}</td>
    @endforeach
        </tr>
@endforeach
    </tbody>
</table>
</div>

<div class="pagination">{{ $items->links() }}</div>

@stop
