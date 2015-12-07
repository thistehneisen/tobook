@extends ('layouts.blank')

@section('styles')
	{{ HTML::style(asset('packages/sky-form/css/demo.css')) }}
	{{ HTML::style(asset('packages/sky-form/css/sky-forms.css')) }}
	{{ HTML::style(asset('packages/sky-form/css/sky-forms-orange.css')) }}
@stop

@section('scripts')
	{{ HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') }}
	{{ HTML::script(asset('packages/sky-form/js/jquery.form.min.js')) }}
	{{ HTML::script(asset('packages/sky-form/js/jquery.validate.min.js')) }}
	<script type="text/javascript">
	$(function()
	{
		// Validation
		$("#sky-form").validate(
		{					
			// Rules for form validation
			rules:
			{
				name:
				{
					required: true
				},
				review:
				{
					required: true,
					minlength: 20
				},
				environment:
				{
					required: true
				},
				service:
				{
					required: true
				},
				price_ratio:
				{
					required: true
				}
			},
								
			// Messages for form validation
			messages:
			{
				name:
				{
					required: 'Please enter your name'
				},
				review:
				{
					required: 'Please enter your review'
				},
				environment:
				{
					required: 'Please rate quality of the product'
				},
				service:
				{
					required: 'Please rate reliability of the product'
				},
				price_ratio:
				{
					required: 'Please rate the product'
				}
			},
			// Do not change code below
			errorPlacement: function(error, element)
			{
				error.insertAfter(element.parent());
			}
		});
		@if (!empty(Session::get('showSuccess')) && (bool)Session::get('showSuccess'))
		$("#sky-form").addClass('submited');
		@endif
	});			
</script>
@stop
@section('body_class')
bg-orange
@stop

@section('content')
<div class="body body-s">
	
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

	{{ Form::open(['route' => ['businesses.doReview', $id, $name], 'method' => 'POST', 'id' => 'sky-form','class' => 'sky-form']) }}
		@if (empty(Session::get('showSuccess')))
		<header>{{ trans('as.review.review-form') }}</header>
		<fieldset>					
			<section>
				<label class="input">
					<i class="icon-append fa fa-user"></i>
					<input type="text" name="name" id="name" placeholder="{{ trans('common.name') }}">
				</label>
			</section>
			
			<section>
				<label class="label"></label>
				<label class="textarea">
					<i class="icon-append fa fa-comment"></i>
					<textarea rows="3" name="review" id="review" placeholder="{{ trans('as.review.comment') }}"></textarea>
				</label>
			</section>
			
			<section>
				<div class="rating">
					<input type="radio" name="environment" value="5" id="environment-5">
					<label for="environment-5"><i class="fa fa-star"></i></label>
					<input type="radio" name="environment" value="4" id="environment-4">
					<label for="environment-4"><i class="fa fa-star"></i></label>
					<input type="radio" name="environment" value="3" id="environment-3">
					<label for="environment-3"><i class="fa fa-star"></i></label>
					<input type="radio" name="environment" value="2" id="environment-2">
					<label for="environment-2"><i class="fa fa-star"></i></label>
					<input type="radio" name="environment" value="1" id="environment-1">
					<label for="environment-1"><i class="fa fa-star"></i></label>
					{{ trans('as.review.environment') }}
				</div>						
				
				<div class="rating">
					<input type="radio" name="service" value="5" id="service-5">
					<label for="service-5"><i class="fa fa-star"></i></label>
					<input type="radio" name="service" value="4" id="service-4">
					<label for="service-4"><i class="fa fa-star"></i></label>
					<input type="radio" name="service" value="3" id="service-3">
					<label for="service-3"><i class="fa fa-star"></i></label>
					<input type="radio" name="service" value="2" id="service-2">
					<label for="service-2"><i class="fa fa-star"></i></label>
					<input type="radio" name="service" value="1" id="service-1">
					<label for="service-1"><i class="fa fa-star"></i></label>
					{{ trans('as.review.service') }}
				</div>				
				
				<div class="rating">
					<input type="radio" name="price_ratio" value="5" id="price_ratio-5">
					<label for="price_ratio-5"><i class="fa fa-star"></i></label>
					<input type="radio" name="price_ratio" value="4" id="price_ratio-4">
					<label for="price_ratio-4"><i class="fa fa-star"></i></label>
					<input type="radio" name="price_ratio" value="3" id="price_ratio-3">
					<label for="price_ratio-3"><i class="fa fa-star"></i></label>
					<input type="radio" name="price_ratio" value="2" id="price_ratio-2">
					<label for="price_ratio-2"><i class="fa fa-star"></i></label>
					<input type="radio" name="price_ratio" value="1" id="price_ratio-1">
					<label for="price_ratio-1"><i class="fa fa-star"></i></label>
					{{ trans('as.review.price_ratio') }}
				</div>
			</section>

			<section>
				<label class="label"></label>
				<label class="textarea">
					{{ Form::captcha([ 'lang' => App::getLocale(), 'class' => 'input' ]); }}
				</label>
			</section>
		</fieldset>
		<footer>
			<button type="submit" class="button">{{ trans('common.submit') }}</button>
		</footer>
		@endif

		@if (!empty(Session::get('showSuccess')) && (bool)Session::get('showSuccess'))
		<div class="message">
			<i class="fa fa-check"></i>
			<p>{{ trans('as.review.review-sent') }}</p>
		</div>
		@endif
	</form>			
</div>
@stop