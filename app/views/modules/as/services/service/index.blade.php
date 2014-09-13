@extends ('modules.as.layout')

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.index') }}</strong></p>
    <p>{{ trans('as.services.desc') }}</p>
</div>

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
<table class="table table-hover">
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
        <tr>
            <td><input type="checkbox"></td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->name }}</td>
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
                <a href="{{ route('as.services.upsert', ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                <a href="{{ route('as.services.delete', ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4">
                <div class="form-group">
                    <label>{{ trans('as.with_selected') }}</label>
                    <select name="" id="" class="form-control input-sm">
                        <option value="">{{ trans('common.delete') }}</option>
                        <option value="">Blahde</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">{{ trans('common.save') }}</button>
            </td>
            <td colspan="5" class="text-right">
                <div class="dropdown">
                    <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                    {{ trans('as.items_per_page') }} <span class="caret"></span>
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
{{ Form::close() }}
@stop
