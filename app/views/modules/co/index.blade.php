@extends ('modules.as.crud.index')

@section ('scripts')
    @parent
    {{ HTML::script(asset('assets/js/modules/co.js')) }}
@stop

@section ('content')
<div class="row">
    <div class="col-md-6">
        {{ Form::open(['route' => ['co.consumers.search'], 'method' => 'GET', 'class' => 'form-inline', 'role' => 'form']) }}
            <div class="input-group">
                {{ Form::text('q', Input::get('q'), ['class' => 'form-control input-sm', 'placeholder' => trans('common.search')]) }}
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        {{ Form::close() }}
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
            <th>{{ trans($langPrefix.'.services') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" class="js-sortable-{{ $sortable }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}"></td>
        @foreach ($fields as $field)
            <td>{{ $item->$field }}</td>
        @endforeach
            <td>
                <ul class="list-unstyle">
                @foreach ($item->getServiceAttribute() as $key => $value)
                    <li><a href="#" id="js-showHistory" data-consumerid="{{ $item->id }}" data-service="{{ $key }}">{{ $value }}</a></li>
                @endforeach
                </ul>
            </td>
            <td>
                <div class="pull-right">
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

<!-- History Modal Dialog -->
<div class="modal fade" id="js-historyModal" role="dialog" aria-labelledby="js-historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('History') }}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
            </div>
        </div>
    </div>
</div>
@stop
