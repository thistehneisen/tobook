@extends ('layouts.admin')

@section('content')
    <h3>{{ trans('admin.coupon.title')}}</h3>
    @include ('admin.coupon.tabs')

    @include ('el.messages')
     {{ Form::open(['route' => ['admin.coupon.campaigns.upsert', (isset($campaign->id)) ? $campaign->id: null], 'class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data']) }}
        @include ('el.messages')
        <div class="form-group">
            <div class="col-sm-5">
            @if (isset($employee->id))
                <h4 class="comfortaa">{{ trans('as.employees.edit') }}</h4>
            @else
                <h4 class="comfortaa">{{ trans('as.employees.add') }}</h4>
            @endif
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="name" class="col-sm-2 control-label">{{ trans('admin.coupon.name') }} {{ Form::required('name', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('name','', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary" id="btn-save-employee">{{ trans('common.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
@stop
