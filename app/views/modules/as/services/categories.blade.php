@extends ('modules.as.layout')

@section ('sub-content')

@include('modules.as.services.category.tabs')
<br>
<h4 class="comfortaa">{{ trans('as.services.all_categories') }}</h4>
<form action="" class="form-inline">
<table class="table table-hover">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>{{ trans('as.services.name') }}</th>
            <th>{{ trans('as.services.is_show_front') }}</th>
            <th>{{ trans('as.services.description') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $category)
        <tr>
            <td><input type="checkbox"></td>
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
            <a href="#" class="btn btn-xs btn-info" title=""><i class="fa fa-edit"></i></a>
            <a href="#" class="btn btn-xs btn-default" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>Valitse toiminto</label>
                    <select name="" id="" class="form-control input-sm">
                        <option value="">Delete</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    Yksiköitä yhteensä <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">5</a></li>
                        <li><a href="#">10</a></li>
                        <li><a href="#">20</a></li>
                        <li><a href="#">50</a></li>
                    </ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
</form>
@stop
