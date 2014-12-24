@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.add') }}</strong></p>
    <p>{{ trans('as.services.add_desc') }}</p>
</div>
@if(!empty($service))
    @include ('modules.as.services.service.tab', $service)
@endif

    @include ('modules.as.services.customTime.form')

<table class="table table-hover table-crud">
    <thead>
        <tr>
            @foreach ($fields as $field)
            <th>{{ trans($langPrefix. '.' .$field) }}</th>
            @endforeach
            <th>&nbsp;</th>
        </tr>
    </thead>
     <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
        @foreach ($fields as $field)
            @if (starts_with($field, 'is_'))
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
                    <a href="{{ route('as.services.customTime.upsert', ['id' => $service->id, 'customTimeId' => $item->id]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                    <a href="{{ route('as.services.customTime.delete', ['id' => $service->id, 'customTimeId' => $item->id]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
      @if (empty($items->getTotal()))
        <tr>
            <td colspan="{{ count($fields) + 1 }}">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
</table>

<div class="row">
    <div class="col-md-10 text-right">
        {{  $items->appends(Input::only('perPage'))->links() }}
    </div>

    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            {{ trans('common.per_page') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route('as.services.customTime', ['id' => $service->id, 'perPage' => 5]) }}">5</a></li>
                <li><a href="{{ route('as.services.customTime', ['id' => $service->id, 'perPage' => 10]) }}">10</a></li>
                <li><a href="{{ route('as.services.customTime', ['id' => $service->id, 'perPage' => 10]) }}">20</a></li>
                <li><a href="{{ route('as.services.customTime', ['id' => $service->id, 'perPage' => 50]) }}">50</a></li>
            </ul>
        </div>
    </div>
</div>

@stop
