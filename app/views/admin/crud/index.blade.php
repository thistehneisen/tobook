@extends ('layouts.admin')

@section ('content')
    @parent
    
    <h1 class="comfortaa">List of {{ get_class($model) }}</h1>

<div class="table-responsive">
<table class="table table-condensed table-hover">
    <thead>
        <tr>
@foreach ($model->visible as $field)
            <th>{{ studly_case($field) }}</th>
@endforeach
        </tr>
    </thead>
    <tbody>
@foreach ($items as $item)
        <tr>
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
