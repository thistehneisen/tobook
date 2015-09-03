@extends ('modules.as.layout')

@section ('content')
<h3>{{ trans('as.options.discount.discount') }}</h3>

<br>

@include ('el.messages')

@include ('modules.as.options.el.discount-tabs')
<br>

{{ Form::open(['route' => ['as.options.discount', 'discount'], 'class' => 'form-horizontal']) }}
<div class="tab-content">
    <div class="form">
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
                    @foreach ($weekdays as $weekday)
                    <td>{{ Form::select('discount['. $weekday .'][morning]', $discount, (!empty($me[$weekday]['morning']) ? $me[$weekday]['morning'] : 0), ['class'=> 'form-control']) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>{{ trans('common.afternoon') }}</td>
                    @foreach ($weekdays as $weekday)
                    <td>{{ Form::select('discount['. $weekday .'][afternoon]', $discount, (!empty($me[$weekday]['morning']) ? $me[$weekday]['afternoon'] : 0), ['class'=> 'form-control']) }}</td>
                    @endforeach
                </tr>
                <tr>
                    <td>{{ trans('common.evening') }}</td>
                    @foreach ($weekdays as $weekday)
                    <td>{{ Form::select('discount['. $weekday .'][evening]', $discount, (!empty($me[$weekday]['morning']) ? $me[$weekday]['evening'] : 0), ['class'=> 'form-control']) }}</td>
                    @endforeach
                </tr>
            </tbody>
         </table>
    </div>
    <div class="form-horizontal well">
        <div class="form-group">
            <label class="control-label col-sm-3">{{ trans('as.options.discount.afternoon_starts_at')}}</label>
            <div class="col-sm-2">{{ Form::select('afternoon_starts_at', $hours, $afternoon->hour, ['class'=> 'form-control']) }}</div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3">{{ trans('as.options.discount.evening_starts_at')}}</label>
            <div class="col-sm-2">{{ Form::select('evening_starts_at', $hours, $evening->hour, ['class'=> 'form-control']) }}</div>
        </div>
         <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@stop
