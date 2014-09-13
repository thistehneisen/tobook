@extends ('modules.as.layout')

@section ('sub-content')

@include('modules.as.services.resource.tabs')

<h4 class="comfortaa">{{ trans('as.services.resources.all') }}</h4>
<form action="" class="form-inline form-table">
<table class="table table-hover">
    <thead>
        <tr>
             <th><input type="checkbox" class="toggle-check-all-boxes" data-checkbox-class="checkbox"></th>
            <th>{{ trans('as.services.categories.category_name') }}</th>
            <th>{{ trans('as.services.categories.is_show_front') }}</th>
            <th>{{ trans('as.services.categories.description') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
         @foreach ($extras as $extra)
        <tr>
            <td><input type="checkbox" class="checkbox" name="extras[]" value="{{ $extra->id }}"></td>
            <td>{{ $extra->name }}</td>
            <td>{{ $extra->quanity }}</td>
            <td>{{ $extra->description }}</td>
            <td class="pull-right">
                <a href="{{ route('as.services.extras.edit', ['id'=> $extra->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.extras.delete', ['id'=> $extra->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
        @if (empty($extras->getTotal()))
        <tr>
            <td colspan="4">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>{{ trans('as.with_selected') }}</label>
                    <select name="" id="mass-action" class="form-control input-sm">
                        <option value="{{ route('as.services.resources.destroy') }}" data-action-name="delete all selected resources">{{ trans('common.delete') }}</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    {{ trans('as.items_per_page') }} <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('as.services.resources', ['perPage' => 5]) }}">5</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 10]) }}">10</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 20]) }}">20</a></li>
                        <li><a href="{{ route('as.services.resources', ['perPage' => 50]) }}">50</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
</form>
@stop
