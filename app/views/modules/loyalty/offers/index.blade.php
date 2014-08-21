@extends('modules.loyalty.layout')

@section('top-buttons')
<a href="{{ URL::route('modules.lc.offers.create') }}" class="btn btn-default btn-success"><span class="glyphicon glyphicon-plus"></span> {{ trans('common.add') }}</a>
<!--<button class="btn btn-default btn-danger js-deleteConsumer"><span class="glyphicon glyphicon-remove"></span> {{ trans('common.delete') }}</button>-->
@stop

<!--@section('scripts')
    {{ HTML::script('assets/js/loyalty.js') }}
@stop-->

@section('sub-content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{ trans('Offers List') }}</h3>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <!--<th><input type="checkbox" id="checkAll" onclick="onCheckAll(this)" /></th>-->
                <th>No</th>
                <th>{{ trans('Offer name') }}</th>
                <th>{{ trans('Total Used') }}</th>
                <th>{{ trans('Required') }}</th>
                <th>{{ trans('Free Service') }}</th>
                <th>{{ trans('Auto Add') }}</th>
                <th>{{ trans('Active') }}</th>
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
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->total_used }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->required }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        {{ $value->free_service }}
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        @if ($value->is_auto_add === 0)
                            {{ trans('No') }}
                        @else
                            {{ trans('Yes') }}
                        @endif
                    </a>
                </td>
                <td>
                    <a href="{{ URL::route('modules.lc.offers.edit', ['id' => $value->id]) }}">
                        @if ($value->is_active === 0)
                            {{ trans('N') }}
                        @else
                            {{ trans('Y') }}
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
