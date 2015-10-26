@extends ('layouts.admin')

@section('content')
    @include ('admin.coupon.tabs')

  <h4 class="comfortaa">{{ trans('admin.coupon.title')}}</h4>

{{ Form::open(['route' => 'admin.coupon.setting', 'class' => 'form-horizontal']) }}
     <div class="form-group">
        <label class="control-label col-sm-3">{{ trans('admin.coupon.setting') }}</label>
        <div class="col-sm-6">{{ $control->render() }}</div>
    </div>
	<div class="form-group">
	    <div class="col-sm-offset-3 col-sm-6">
	        <button class="btn btn-sm btn-primary" type="submit">{{ trans('common.save') }}</button>
	    </div>
	</div>
{{ Form::close() }}
@stop
