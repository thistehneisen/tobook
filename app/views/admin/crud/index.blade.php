
@extends ('layouts.admin')

@section ('scripts')
    @parent
    <script>
$(function() {
    $('a.link-delete').on('click', function(e) {
        var confirmed = confirm('Are you sure?');
        if (confirmed === false) {
            e.preventDefault();
        }
    });
});
    </script>
@stop

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
                <div class="dropdown">
                  <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="menu-action-{{ $item->id }}" data-toggle="dropdown">
                        Actions
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu-action-{{ $item->id }}">
                        <li role="presentation"><a role="menuitem" href="{{ route('admin.crud.edit', ['model' => Request::segment(2), 'id' => $item->id]) }}"><i class="fa fa-edit"></i> Edit</a></li>
                    @if (method_exists($item, 'getExtraActionLinks'))
                        @foreach ($item->getExtraActionLinks() as $name => $href)
                        <li role="presentation"><a role="menuitem" href="{{ $href }}">{{ $name }}</a></li>
                        @endforeach
                    @endif
                        <li class="divider"></li>
                        <li role="presentation"><a class="link-delete" role="menuitem" href="{{ route('admin.crud.delete', ['model' => Request::segment(2), 'id' => $item->id]) }}"><i class="fa fa-trash-o"></i> Delete</a></li>
                    </ul>
                </div>

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
