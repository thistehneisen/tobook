@extends ('modules.as.layout')

@section ('content')
<div class="row">
    <div class="col-md-6">
        <form class="form-inline" role="form">
            <div class="input-group">
              <input type="text" class="form-control input-sm" placeholder="Haku">
              <span class="input-group-btn">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
              </span>
            </div><!-- /input-group -->
        </form>
    </div>
    <div class="col-md-6 text-right">
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn active btn-default">{{ trans('common.all') }}</button>
            <button type="button" class="btn btn-default">{{ trans('common.active') }}</button>
            <button type="button" class="btn btn-default">{{ trans('common.inactive') }}</button>
        </div>
    </div>
</div>

{{ Form::open(['route' => $routes['bulk'], 'class' => 'form-inline form-table', 'id' => 'form-bulk', 'data-confirm' => trans('as.crud.bulk_confirm')]) }}
<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
        @foreach ($fields as $field)
            <th>{{ trans($langPrefix.'.'.$field) }}</th>
        @endforeach
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}"></td>
        @foreach ($fields as $field)
            @if ($field === 'is_active')
                <td>
                @if ((bool) $item->$field === true)
                    <span class="label label-success">{{ trans('common.active') }}</span>
                @else
                    <span class="label label-danger">{{ trans('common.inactive') }}</span>
                @endif
                </td>
            @elseif ($field === 'avatar')
                <td><img src="{{ $item->getAvatarUrl() }}" alt="" class="img-rounded"></td>
            @elseif (starts_with($field, 'is_'))
                <td>
                @if ((bool) $item->$field === true)
                    <span class="label label-success">{{ trans('common.yes') }}</span>
                @else
                    <span class="label label-danger">{{ trans('common.no') }}</span>
                @endif
                </td>
            @else
                <td>{{ $item->$field }}</td>
            @endif
        @endforeach
            <td>
            <div  class="pull-right">
                <a href="{{ route($routes['upsert'], ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route($routes['delete'], ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </div>
            </td>
        </tr>
    @endforeach
        @if (empty($items->getTotal()))
        <tr>
            <td colspan="{{ count($fields) + 2 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        @if (!empty($bulkActions))
        <div class="form-group">
            <label>@lang('as.with_selected')</label>
            <select name="action" id="mass-action" class="form-control input-sm">
            @foreach ($bulkActions as $action)
                <option value="{{ $action }}">{{ trans('common.'.$action) }}</option>
            @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
        @endif
    </div>
    <div class="col-md-6 text-right">
        {{  $items->appends(Input::only('perPage'))->links() }}
    </div>

    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            @lang('as.items_per_page') <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route($routes['index'], ['perPage' => 5]) }}">5</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 10]) }}">10</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 10]) }}">20</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 50]) }}">50</a></li>
            </ul>
        </div>
    </div>
</div>

{{ Form::close() }}
@stop
