@extends ('layouts.default')

@section('styles')
	{{ HTML::style(asset('packages/jquery.raty/jquery.raty.css')) }}
@stop

@section('scripts')
	@parent
	{{ HTML::script(asset('packages/jquery.raty/jquery.raty.js')) }}
	<script type="text/javascript">
		$(document).ready(function() {
			$('.raty').raty({
				scoreName: function() {
				   return $(this).data('name');
				},
				starOff : '{{ asset('packages/jquery.raty/images') }}/star-off.png',
  				starOn  : '{{ asset('packages/jquery.raty/images') }}/star-on.png'
			});
		});
	</script>
@stop

@section('content')
<div class="row" style="margin-top: 30px">
	<div class="col-sm-12">
		@if ($errors->top && $errors->top->isEmpty() === false)
		    <div class="alert alert-danger">
		        <p><strong>{{ trans('common.errors') }}!</strong></p>
		    @foreach ($errors->top->all() as $message)
		        <p>{{ $message }}</p>
		    @endforeach
		    </div>
		@endif

		@if ($errors->any())
		<div class="alert alert-danger">
		    <ul>
		        {{ implode('', $errors->all('<li>:message</li>')) }}
		    </ul>
		</div>
		@endif
	</div>
</div>
<div class="row">
	<div class="col-sm-offset-2 col-sm-6">

		<h1 class="comfortaa orange text-center">{{ trans('as.review.leave_review') }}</h1>
		{{ Form::open(['route' => ['businesses.doReview', $id, $name], 'method' => 'POST', 'class' => 'form-horizontal']) }}
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">{{ trans('as.review.environment') }}</label>
				<div class="col-sm-9">
					<div class="raty" data-name="environment"></div>
				</div>
			</div>			
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">{{ trans('as.review.service') }}</label>
				<div class="col-sm-9">
					<div class="raty" data-name="service"></div>
				</div>
			</div>			
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">{{ trans('as.review.price_ratio') }}</label>
				<div class="col-sm-9">
					<div class="raty" data-name="price_ratio"></div>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">{{ trans('common.name') }}</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('common.name') }}">
				</div>
			</div>
			<div class="form-group">
				<label for="comment" class="col-sm-3 control-label">{{ trans('as.review.comment') }}</label>
				<div class="col-sm-9">
					<textarea class="form-control" rows="7" name="comment" id="comment"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label for="name" class="col-sm-3 control-label">{{ trans('common.captcha') }}</label>
				<div class="col-sm-9">
					{{ Form::captcha(); }}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-lg btn-orange to-upper comfortaa">{{ trans('common.submit') }}</button>
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>
@stop
