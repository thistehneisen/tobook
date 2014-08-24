@extends ('modules.as.layout')

@section ('content')

@include('modules.as.services.category.tabs')
<br>
@include ('el.messages')
<h4 class="comfortaa">{{ trans('as.services.all_categories') }}</h4>
<form action="" class="form-inline form-table">
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
        @foreach ($categories as $category)
        <tr>
        <td><input type="checkbox" class="checkbox" name="categories[]" value="{{ $category->id }}"></td>
            <td>{{ $category->name }}</td>
            <td>
            @if ($category->is_show_front)
                <span class="label label-success">{{ trans('common.yes') }}</span>
            @else
                <span class="label label-danger">{{ trans('common.no') }}</span>
            @endif
            </td>
            <td>{{ $category->description }}</td>
            <td>
            <div  class="pull-right">
                <a href="{{ route('as.services.categories.edit', ['id'=> $category->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.categories.delete', ['id'=> $category->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </div>
            </td>
        </tr>
        @endforeach
        @if (empty($categories->getTotal()))
        <tr>
            <td colspan="4">{{ trans('common.no_records') }}</td>
        </tr>
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>Valitse toiminto</label>
                    <select name="" id="mass-action" class="form-control input-sm">
                        <option value="{{ route('as.services.categories.destroy') }}" data-action-name="delete all selected categories">Delete</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    Yksiköitä yhteensä <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ route('as.services.categories', ['perPage' => 5]) }}">5</a></li>
                        <li><a href="{{ route('as.services.categories', ['perPage' => 10]) }}">10</a></li>
                        <li><a href="{{ route('as.services.categories', ['perPage' => 10]) }}">20</a></li>
                        <li><a href="{{ route('as.services.categories', ['perPage' => 50]) }}">50</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
{{  $categories->appends(Input::only('perPage'))->links() }}
</form>
@stop
