@extends ('layouts.admin')

@section('content')

@if ($showTab === true)
    @include($tabsView, ['routes' => $routes, 'langPrefix' => $langPrefix])
@endif

<h4 class="comfortaa">{{ trans($langPrefix.'.all') }}</h4>

<div class="row">
    <div class="col-md-6">
        {{ Form::open(['route' => $routes['search'], 'method' => 'GET', 'class' => 'form-inline', 'id' => 'form-search', 'role' => 'form']) }}
        <div class="form-group">
            <div class="input-group">
                {{ Form::text('q', Input::get('q'), ['class' => 'form-control input-sm', 'placeholder' => trans('common.search')]) }}
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </div>
        {{ Form::close() }}
    </div>
    <div class="col-md-6 text-right">
    </div>
</div>

<table class="table table-hover table-crud">
    <thead>
        <tr>
            <th>{{ trans('admin.master-cats.name') }}</th>
            <th>{{ trans('admin.master-cats.description') }}</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
     <tbody id="js-crud-tbody">
    @foreach ($items as $item)
        <tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('as.crud.sortable') }}">
            <td>{{ $item->name }}</td>
            <td>{{ $item->description }}</td>
            <td>
                <div  class="pull-right">
                    <a href="{{ route('admin.master-cats.upsert', ['id' => $item->id]) }}" class="btn btn-xs btn-success" title=""><i class="fa fa-edit"></i></a>
                    <a href="{{ route('admin.master-cats.delete', ['id' => $item->id]) }}" class="btn btn-xs btn-danger" title=""><i class="fa fa-trash-o"></i></a>
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
    <div class="col-md-6 text-right">
        {{ $items->links() }}
    </div>

    <div class="col-md-6 text-right">
        <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            {{ trans('olut::olut.per_page') }} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="{{ route($routes['index'], ['perPage' => 5]) }}" id="per-page-5">5</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 10]) }}" id="per-page-10">10</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 20]) }}" id="per-page-20">20</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 50]) }}" id="per-page-50">50</a></li>
                <li><a href="{{ route($routes['index'], ['perPage' => 500]) }}" id="per-page-500">500</a></li>
            </ul>
        </div>
    </div>
</div>
@stop
