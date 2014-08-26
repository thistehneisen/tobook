@extends ('modules.as.layout')

@section ('styles')
    @parent
<style>
.pagination {
    margin: 0 !important;
}
</style>
@stop

@section ('content')
    @include('modules.as.services.category.tabs')
    <br>
    @include ('el.messages')
<h4 class="comfortaa">{{ trans('as.services.all_categories') }}</h4>

{{ Form::open(['class' => 'form-inline form-table']) }}
<table class="table table-hover">
    <thead>
        <tr>
            <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            <th>{{ trans('as.services.name') }}</th>
            <th>{{ trans('as.services.is_show_front') }}</th>
            <th>{{ trans('as.services.description') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr>
        <td><input type="checkbox" class="checkbox" name="categories[]" value="{{ $item->id }}"></td>
            <td>{{ $item->name }}</td>
            <td>
            @if ($item->is_show_front)
                <span class="label label-success">{{ trans('common.yes') }}</span>
            @else
                <span class="label label-danger">{{ trans('common.no') }}</span>
            @endif
            </td>
            <td>{{ $item->description }}</td>
            <td>
            <div  class="pull-right">
                <a href="{{ route('as.services.categories.upsert', ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.categories.delete', ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </div>
            </td>
        </tr>
        @endforeach
        @if (empty($items->getTotal()))
        <tr>
            <td colspan="4">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>@lang('as.with_selected')</label>
            <select name="" id="mass-action" class="form-control input-sm">
                <option value="{{ route('as.services.categories.destroy') }}" data-action-name="delete all selected categories">Delete</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
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
                <li><a href="{{ route('as.services.categories.index', ['perPage' => 5]) }}">5</a></li>
                <li><a href="{{ route('as.services.categories.index', ['perPage' => 10]) }}">10</a></li>
                <li><a href="{{ route('as.services.categories.index', ['perPage' => 10]) }}">20</a></li>
                <li><a href="{{ route('as.services.categories.index', ['perPage' => 50]) }}">50</a></li>
            </ul>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop
