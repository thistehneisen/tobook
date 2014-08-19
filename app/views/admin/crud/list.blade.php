<div class="row">
    <div class="col-md-6 col-lg-6">
{{ Form::open(['route' => ['admin.crud.search', $modelName], 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
    <div class="form-group">
        <label class="sr-only" for="txt-keyword">{{ trans('admin.search_placeholder') }}</label>
    </div>
    <input type="search" name="q" class="form-control" id="txt-keyword">
    <button type="submit" class="btn btn-primary">{{ trans('admin.search') }}</button>
{{ Form::close(); }}
    </div>

    <div class="col-md-6 col-lg-6 text-right">
        <a href="{{ route('admin.crud.create', ['model' => $modelName]) }}" class="btn btn-success"><i class="fa fa-plus"></i> {{ trans('admin.create') }}</a>
    </div>
</div>

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
                        <li role="presentation"><a role="menuitem" href="{{ route('admin.crud.edit', ['model' => $modelName, 'id' => $item->id]) }}"><i class="fa fa-edit"></i> {{ trans('admin.edit') }}</a></li>
                    @if (method_exists($item, 'getExtraActionLinks'))
                        @foreach ($item->getExtraActionLinks() as $name => $href)
                        <li role="presentation"><a role="menuitem" href="{{ $href }}">{{ $name }}</a></li>
                        @endforeach
                    @endif
                        <li class="divider"></li>
                        <li role="presentation"><a class="link-delete" role="menuitem" href="{{ route('admin.crud.delete', ['model' => $modelName, 'id' => $item->id]) }}"><i class="fa fa-trash-o"></i> {{ trans('admin.delete') }}</a></li>
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

<div class="text-center ">
    <div class="pagination">{{ $items->links() }}</div>
</div>
