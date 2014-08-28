@extends('modules.lc.layout')

@section('top-buttons')
<a href="{{ URL::route('lc.vouchers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
@stop

@section('scripts')
    {{ HTML::script('assets/js/loyalty.js') }}
@stop

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('loyalty-card.voucher_list') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!--<th><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>-->
                <th>No</th>
                <th>{{ trans('loyalty-card.voucher_name') }}</th>
                <th>{{ trans('loyalty-card.total_used') }}</th>
                <th>{{ trans('loyalty-card.required') }}</th>
                <th>{{ trans('loyalty-card.discount') }}</th>
                <th>{{ trans('loyalty-card.active') }}</th>
                <th>{{ trans('common.delete') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $key => $value)
            <tr>
                <!--<td>
                    <input type="checkbox" id="chkStampId" value="{{ $value->id }}" />
                </td>-->
                <td>{{ $key+1 }}</td>
                <td>
                    <a href="{{ URL::route('lc.vouchers.edit', ['id' => $value->id]) }}">
                        {{ $value->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.vouchers.edit', ['id' => $value->id]) }}">
                        {{ $value->total_used }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.vouchers.edit', ['id' => $value->id]) }}">
                        {{ $value->required }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.vouchers.edit', ['id' => $value->id]) }}">
                        {{ $value->value }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('lc.vouchers.edit', ['id' => $value->id]) }}">
                        @if ($value->is_active === 0)
                            {{ trans('common.no') }}
                        @else
                            {{ trans('common.yes') }}
                        @endif
                    </a>
                </td>
                <td>
                    {{ Form::open(['route' => ['lc.vouchers.delete', $value->id], 'method' => 'delete']) }}
                        <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#confirmDelete">
                            <span class="glyphicon glyphicon-trash"></span> {{ trans('common.delete') }}
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pull-right">{{ $vouchers->links() }}</div>
</div>
@stop
