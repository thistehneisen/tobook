@extends ('modules.as.crud.index')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.index') }}</strong></p>
    <p>{{ trans('as.services.desc') }}</p>
</div>
@if ($errors->top->isEmpty() === false)
    <div class="alert alert-danger">
        <p><strong>{{ trans('common.errors') }}!</strong></p>
    @foreach ($errors->top->all() as $message)
        <p>{{ $message }}</p>
    @endforeach
    </div>
@endif
<div class="row">
    <div class="col-md-6">
        {{ Form::open(['route' => ['as.services.search'], 'method' => 'GET', 'class' => 'form-inline', 'role' => 'form']) }}
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
            <a href="{{ route('as.services.index') }}" class="btn btn-default {{ empty(Input::all()) ? 'active' : '' }}">{{ trans('common.all') }}</a>
            <a href="{{ route('as.services.index', ['is_active' => 1]) }}" class="btn btn-default {{ Input::get('is_active') === '1' ? 'active' : '' }}">{{ trans('common.active') }}</a>
            <a href="{{ route('as.services.index', ['is_active' => 0]) }}" class="btn btn-default {{ Input::get('is_active') === '0' ? 'active' : '' }}">{{ trans('common.inactive') }}</a>
        </div>
    </div>
</div>

{{ Form::open(['route' => $routes['bulk'], 'class' => 'form-inline form-table', 'id' => 'form-bulk', 'data-confirm' => trans('as.crud.bulk_confirm')]) }}
<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>{{ trans('as.services.categories.name') }}</th>
            <th>{{ trans('as.services.employees') }}</th>
            <th>{{ trans('as.services.price') }}</th>
            <th>{{ trans('as.services.duration') }}</th>
            <th>{{ trans('as.services.total') }}</th>
            <th>{{ trans('as.services.category') }}</th>
            <th>{{ trans('as.services.is_active') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" class="service-row">
            <td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}"></td>
            <td>{{ $item->name }}</td>
            <td>
            @foreach ($item->employees as $e)
                {{ $e->name }},
            @endforeach
            </td>
            <td>&euro;{{ $item->price }}</td>
            <td>{{ $item->during }}</td>
            <td>{{ $item->length }}</td>
            <td>{{ $item->category->name }}</td>
            <td>
                @if ($item->is_active)
                    <span class="label label-success">{{ trans('common.active') }}</span>
                @else
                    <span class="label label-danger">{{ trans('common.inactive') }}</span>
                @endif
            </td>
            <td>
                <a href="{{ route('as.services.upsert', ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title="" id="row-{{ $item->id }}-edit"><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.delete', ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title="" id="row-{{ $item->id }}-delete"><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
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
