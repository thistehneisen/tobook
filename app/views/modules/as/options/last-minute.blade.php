@extends ('modules.as.layout')

@section ('content')
<h3>{{ trans('as.options.discount.last-minute') }}</h3>

<br>

@include ('el.messages')

@include ('modules.as.options.el.discount-tabs')
<br>

{{ Form::open(['route' => ['as.options.discount', 'last-minute'], 'class' => 'form-horizontal']) }}
    <div class="tab-content">
        <div class="form-group">
           <label class="control-label col-sm-3">{{ trans('as.options.discount.last-minute-1')}}</label>
           <div class="col-sm-8">{{ Form::checkbox('is_active', 0, 0, ['id'=>'is_active']) }}</div>
        </div>
        <div class="form-group">
           <label class="control-label col-sm-3">{{ trans('as.options.discount.last-minute-2')}}</label>
           <div class="col-sm-8">{{ Form::select('discount', $discount, 0, ['class'=> 'form-control']) }}</div>
        </div>
        <div class="form-group">
           <label class="control-label col-sm-3">{{ trans('as.options.discount.last-minute-3')}}</label>
           <div class="col-sm-8">{{ Form::select('before', $before, 0, ['class'=> 'form-control']) }}</div>
        </div>
    </div>

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
