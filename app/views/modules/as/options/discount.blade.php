@extends ('modules.as.layout')

@section ('content')
<h3>{{ trans('as.options.discount.discount') }}</h3>

<br>

@include ('el.messages')

@include ('modules.as.options.el.discount-tabs')
<br>

{{ Form::open(['route' => ['as.options.discount', 'discount'], 'class' => 'form-horizontal']) }}
<div class="tab-content">
 <table class="table table-bordered">
    <thead>
        <tr>
            <td>&nbsp;</td>
            <td>{{ trans('common.mon')}}</td>
            <td>{{ trans('common.tue')}}</td>
            <td>{{ trans('common.wed')}}</td>
            <td>{{ trans('common.thu')}}</td>
            <td>{{ trans('common.fri')}}</td>
            <td>{{ trans('common.sat')}}</td>
            <td>{{ trans('common.sun')}}</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ trans('common.morning') }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
        </tr>
        <tr>
            <td>{{ trans('common.afternoon') }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
        </tr>
        <tr>
            <td>{{ trans('common.evening') }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
            <td>{{ Form::select('discount', $discount, 0) }}</td>
        </tr>
    </tbody>
 </table>
</div>

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
