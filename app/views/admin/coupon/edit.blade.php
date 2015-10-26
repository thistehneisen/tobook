@extends ('layouts.admin')

@section('content')
    @include ('admin.coupon.tabs')

 	 <h4 class="comfortaa">{{ trans('admin.coupon.campaign.edit')}}</h4>
 	 <div class="row">
 	 	<div class="col-md-6">
 	 		<ul>
 	 			<li>{{ $campaign->name }}</li>
 	 			<li>{{ $campaign->expireAt->toDateString(); }}</li>
 	 		</ul>
 	 	</div>
 	 </div>

 	 <div class="row">
 	 	<div class="col-md-6">
 	 		@if (!(boolean)$campaign->is_reusable)
 	 		<table class="table table-hover">
 	 			<thead>
 	 				<tr>
 	 					<th>#</th>
 	 					<th>{{ trans('admin.coupon.code') }}</th>
 	 					<th>{{ trans('admin.coupon.is_used') }} </th>
 	 				</tr>
 	 			</thead>
 	 			<tbody>
 	 				<?php $count = 1;?>
 	 				@foreach($campaign->coupons as $coupon)
 	 				<tr>
 	 					<td>{{ $count }}</td>
 	 					<td>{{ $coupon->code }}</td>
 	 					<td>{{ trans('admin.coupon.campaign.' . $coupon->isUsed) }}</td>
 	 				</tr>
 	 				<?php $count++;?>
 	 				@endforeach
 	 			</tbody>
 	 		</table>
 	 		@endif
 	 	</div>
 	 	<div class="col-md-6">
 	 		
 	 	</div>
 	 </div>
@stop
