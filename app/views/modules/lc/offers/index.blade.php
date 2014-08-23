@extends('modules.lc.layout')

@section('top-buttons')
<a href="{{ URL::route('lc.offers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.offer_list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!--<th><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>-->
                <th>{{ trans('loyalty-card.number') }}</th>
                <th>{{ trans('loyalty-card.offer_name') }}</th>
                <th>{{ trans('loyalty-card.total_used') }}</th>
                <th>{{ trans('loyalty-card.required') }}</th>
                <th>{{ trans('loyalty-card.free_service') }}</th>
                <th>{{ trans('loyalty-card.auto_add') }}</th>
                <th>{{ trans('loyalty-card.active') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($offers as $key => $value)
            <tr>
                <!--<td>
                    <input type="checkbox" id="chkStampId" value="{{ $value->id }}" />
                </td>-->
                <td>{{ $key+1 }}</td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->total_used }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->required }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->free_service }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        @if ($value->is_auto_add === 0)
                            {{ trans('common.no') }}
                        @else
                            {{ trans('common.yes') }}
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.offers.edit', ['id' => $value->id]) }}">
                        @if ($value->is_active === 0)
                            {{ trans('common.no') }}
                        @else
                            {{ trans('common.yes') }}
                        @endif
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $offers->links() }}</div>
</div>
@stop
