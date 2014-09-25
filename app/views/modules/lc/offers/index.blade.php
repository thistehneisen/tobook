@extends('modules.lc.layout')

@section('top-buttons')
<a href="{{ URL::route('offers.upsert') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('scripts')
    {{ HTML::script('assets/js/modules/lc.js') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.offer_list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.offer_name') }}</th>
                <th>{{ trans('loyalty-card.total_used') }}</th>
                <th>{{ trans('loyalty-card.required') }}</th>
                <th>{{ trans('loyalty-card.auto_add') }}</th>
                <th>{{ trans('loyalty-card.active') }}</th>
                <th>{{{ trans('common.edit') }}}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $key => $value)
            <tr>
                <td>{{ $value->id }}</td>
                <td>
                    {{ $value->name }}
                </td>
                <td>
                    {{ $value->total_used }}
                </td>
                <td>
                    {{ $value->required }}
                </td>
                <td>
                    @if ($value->is_auto_add === 0)
                        {{ trans('common.no') }}
                    @else
                        {{ trans('common.yes') }}
                    @endif
                </td>
                <td>
                    @if ($value->is_active === 0)
                        {{ trans('common.no') }}
                    @else
                        {{ trans('common.yes') }}
                    @endif
                </td>
                <td class="no-display">
                    <a href="{{ URL::route('offers.upsert', ['id' => $value->id]) }}">
                        <button class="btn btn-sm btn-success" type="button">
                            <span class="glyphicon glyphicon-pencil"></span> {{ trans('common.edit') }}
                        </button>
                    </a>
                </td>
                <td>
                    <a data-href="{{ URL::route('offers.delete', ['id' => $value->id]) }}" data-toggle="modal" data-target="#js-confirmDeleteModal" href="#">
                        <button class="btn btn-sm btn-danger" type="button">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $items->links() }}</div>
</div>
@stop
