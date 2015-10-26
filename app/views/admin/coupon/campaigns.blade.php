@extends ('layouts.admin')

@section('content')
	@include ('admin.coupon.tabs')
    <h4 class="comfortaa">{{ trans($langPrefix.'.all') }}</h4>

    <div class="row">
    	<div class="col-md-6">
    		{{ Form::open(['route' => 'admin.coupon.campaigns.search', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'form-search', 'role' => 'form']) }}
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

    {{ Form::open(['route' => 'admin.coupon.campaigns.bulk', 'class' => 'form-inline form-table', 'id' => 'form-bulk', 'data-confirm' => trans('olut::olut.bulk_confirm')]) }}
    <table class="table table-hover table-crud">
    	<thead>
    		<tr>
    			<th><input type="checkbox" class="toggle-check-all-boxes check-all" data-checkbox-class="checkbox"></th>
    			@foreach ($fields as $field)
    			<th>{{ trans($langPrefix.'.'.$field) }}</th>
    			@endforeach
    			<th>&nbsp;</th>
    		</tr>
    	</thead>
    	<tbody id="js-crud-tbody">
    		@foreach ($items as $item)
    		<tr id="row-{{ $item->id }}" data-id="{{ $item->id }}" class="item-row js-sortable-{{ $sortable }}" data-toggle="tooltip" data-placement="top" data-title="{{ trans('olut::olut.sortable') }}">
    			<td><input type="checkbox" class="checkbox" name="ids[]" value="{{ $item->id }}" id="bulk-item-{{ $item->id }}"></td>
    			@foreach ($fields as $field)
    			<td>{{ $bartender->mix($field, $item) }}</td>
    			@endforeach
    			<td>
    				<div  class="pull-right">
    					@if ($actionsView !== null)
    					@include ($actionsView, ['item' => $item, 'routes' => $routes])
    					@endif
    					<a href="{{ route('admin.coupon.campaigns.edit', ['id'=> $item->id ]) }}" class="btn btn-xs btn-success" title="" id="row-{{ $item->id }}-edit"><i class="fa fa-edit"></i></a>
    					<a href="{{ route('admin.coupon.campaigns.delete', ['id'=> $item->id ]) }}" class="btn btn-xs btn-danger" title="" id="row-{{ $item->id }}-delete"><i class="fa fa-trash-o"></i></a>
    				</div>
    			</td>
    		</tr>
    		@endforeach
    		@if (empty($items->getTotal()))
    		<tr>
    			<td colspan="{{ count($fields) + 2 }}">{{ trans('olut::olut.empty') }}</td>
    		</tr>
    		@endif
    	</tbody>
    </table>

    <div class="row">
    	<div class="col-md-4">
    		@if (!empty($bulkActions))
    		<div class="form-group">
    			<label>{{ trans('olut::olut.with_selected')  }}</label>
    			<select name="action" id="olut-mass-action" class="form-control input-sm">
    				@foreach ($bulkActions as $action)
    				<option value="{{ $action }}">{{ Lang::has($langPrefix.'.'.$action) ? trans($langPrefix.'.'.$action) : trans('olut::olut.'.$action) }}</option>
    				@endforeach
    			</select>
    		</div>
    		<button type="submit" class="btn btn-primary btn-sm btn-submit-mass-action" id="btn-bulk">{{ trans('olut::olut.submit') }}</button>
    		@endif
    	</div>
    	<div class="col-md-6 text-right">
    		{{ $items->links() }}
    	</div>

    	<div class="col-md-2 text-right">
    		<div class="btn-group">
    			<button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
    				{{ trans('olut::olut.per_page') }} <span class="caret"></span>
    			</button>
    			<ul class="dropdown-menu dropdown-menu-right">
    				<li><a href="{{ route('admin.coupon.campaigns', ['perPage' => 5]) }}" id="per-page-5">5</a></li>
    				<li><a href="{{ route('admin.coupon.campaigns', ['perPage' => 10]) }}" id="per-page-10">10</a></li>
    				<li><a href="{{ route('admin.coupon.campaigns', ['perPage' => 20]) }}" id="per-page-20">20</a></li>
    				<li><a href="{{ route('admin.coupon.campaigns', ['perPage' => 50]) }}" id="per-page-50">50</a></li>
    				<li><a href="{{ route('admin.coupon.campaigns', ['perPage' => 500]) }}" id="per-page-500">500</a></li>
    			</ul>
    		</div>
    	</div>
    </div>
    {{ Form::close() }}
@stop
