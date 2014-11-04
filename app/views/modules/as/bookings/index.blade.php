@extends ('modules.as.crud.index')

@section ('content')
<div class="row">
    <div class="col-md-6">
        {{ Form::open(['route' => ['as.bookings.search'], 'method' => 'GET', 'class' => 'form-inline', 'role' => 'form', 'id' => 'form-search']) }}
            <div class="input-group">
                {{ Form::text('q', Input::get('q'), ['class' => 'form-control input-sm', 'placeholder' => trans('common.search')]) }}
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-6 text-right">
        <div class="btn-group btn-group-sm">
            <a href="{{ route('as.bookings.index') }}" class="btn btn-default {{ empty(Input::all()) ? 'active' : '' }}">{{ trans('common.all') }}</a>
            <a href="{{ route('as.bookings.index', ['is_active' => 1]) }}" class="btn btn-default {{ Input::get('is_active') === '1' ? 'active' : '' }}">{{ trans('common.active') }}</a>
            <a href="{{ route('as.bookings.index', ['is_active' => 0]) }}" class="btn btn-default {{ Input::get('is_active') === '0' ? 'active' : '' }}">{{ trans('common.inactive') }}</a>
        </div>
    </div>
</div>

{{ Form::open(['route' => $routes['bulk'], 'class' => 'form-inline form-table', 'id' => 'form-bulk', 'data-confirm' => trans('as.crud.bulk_confirm')]) }}
<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            <th>{{ trans('as.bookings.uuid') }}</th>
            <th>{{ trans('as.bookings.date') }}</th>
            <th>{{ trans('as.bookings.customers') }}</th>
            <th>{{ trans('as.bookings.total') }}</th>
            <th>{{ trans('as.bookings.notes') }}</th>
            <th>{{ trans('as.bookings.status') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" class="booking-row js-sortable-{{ $sortable }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}"></td>
            <td>{{ $item->uuid }}</td>
            <td>{{ $item->date }}</td>
            <td>@if(!empty($item->consumer->name)) {{ $item->consumer->name }} @endif</td>
            <td>{{ $item->total }} {{ trans('common.minutes') }}</td>
            <td>{{ nl2br($item->notes) }}</td>
            <td>{{ $item->status_text }}</td>
            <td>
            <div  class="pull-right">
                <a href="{{ route($routes['upsert'], ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title="" id="row-{{ $item->id }}-edit"><i class="fa fa-edit"></i></a>
                <a href="{{ route($routes['delete'], ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title="" id="row-{{ $item->id }}-delete"><i class="fa fa-trash-o"></i></a>
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
                <li><a href="{{ route($routes['index'], ['perPage' => 5]) }}" id="per-page-5">5</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 10]) }}" id="per-page-10">10</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 20]) }}" id="per-page-20">20</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 50]) }}" id="per-page-50">50</a></li>
            </ul>
        </div>
    </div>
</div>

{{ Form::close() }}
@stop
