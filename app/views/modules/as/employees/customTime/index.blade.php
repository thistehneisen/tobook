@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('title')
    {{ trans('as.employees.workshifts') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
    <script>
$(function () {
    $('input.time-picker').datetimepicker({
        pickDate: false,
        minuteStepping: 15,
        format: 'HH:mm',
        language: '{{ App::getLocale() }}'
    });
});
    </script>
@stop

@section ('content')
    @include ('modules.as.employees.tabCustomTime')
    @include ('modules.as.employees.customTime.form')

<table class="table table-hover">
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
        <tr id="row-{{ $item->id }}" class="custom-time-row" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
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
                    <a href="{{ route('as.employees.customTime.upsert', ['customTimeId' => $item->id]) }}" class="btn btn-xs btn-success" title="" id="row-{{ $item->id }}-edit"><i class="fa fa-edit"></i></a>
                    <a href="{{ route('as.employees.customTime.delete', ['customTimeId' => $item->id]) }}" class="btn btn-xs btn-danger" title="" id="row-{{ $item->id }}-delete"><i class="fa fa-trash-o"></i></a>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

@stop
