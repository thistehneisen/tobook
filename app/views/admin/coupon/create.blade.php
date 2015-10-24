@extends ('layouts.admin')

@section('content')
    <h3>{{ trans('admin.coupon.title')}}</h3>
    @include ('admin.coupon.tabs')

    @include ('el.messages')
     {{ Form::open(['route' => ['admin.coupon.campaigns.upsert', (isset($campaign->id)) ? $campaign->id: null], 'class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data']) }}
        @include ('el.messages')
        <div class="form-group">
            <div class="col-sm-5">
            @if (isset($campaign->id))
                <h4 class="comfortaa">{{ trans('admin.coupon.campaign.edit') }}</h4>
            @else
                <h4 class="comfortaa">{{ trans('admin.coupon.campaign.add') }}</h4>
            @endif
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="name" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.name') }} {{ Form::required('name', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('name','', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="name" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.is_reusable') }} {{ Form::required('is_reusable', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('name','', ['class' => 'form-control input-sm', 'id' => 'is_reusable']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('amount', $errors) }}">
            <label for="amount" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.amount') }} {{ Form::required('amount', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('amount','', ['class' => 'form-control input-sm', 'id' => 'amount']) }}
                {{ Form::errorText('amount', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="discount" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.discount') }} {{ Form::required('discount', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('discount','', ['class' => 'form-control input-sm', 'id' => 'discount']) }}
                {{ Form::errorText('discount', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="begin_at" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.begin_at') }} {{ Form::required('begin_at', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('begin_at','', ['class' => 'form-control input-sm', 'id' => 'begin_at']) }}
                {{ Form::errorText('begin_at', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('name', $errors) }}">
            <label for="expire_at" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.expire_at') }} {{ Form::required('expire_at', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('expire_at','', ['class' => 'form-control input-sm', 'id' => 'expire_at']) }}
                {{ Form::errorText('expire_at', $errors) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary" id="btn-save-employee">{{ trans('common.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
@stop
