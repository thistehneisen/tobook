@extends ('layouts.admin')
@section('styles')
{{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css') }}
@stop

@section('scripts')
{{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
@if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
@endif
<script type="text/javascript">
    $(function(){
        $('#is_reusable').click(function(e){
            var fn = $(this).is(':checked') ? 'slideUp' : 'slideDown';
            $('.reusable_code')[fn]();
        });

        // Date picker
        $(document).on('focus', '.date-picker', function () {
          $(this).datepicker({
            format: 'dd.mm.yyyy',
            weekStart: 1,
            autoclose: true,
            language: $('body').data('locale')
          })
        })
    });
</script>
@stop

@section('content')
     <h4 class="comfortaa">{{ trans('admin.coupon.title')}}</h4>
    @include ('admin.coupon.tabs')
     {{ Form::open(['route' => ['admin.coupon.campaigns.create', (isset($campaign->id)) ? $campaign->id: null], 'class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data']) }}
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
                {{ Form::text('name', !empty($campaign) ? $campaign->name : '', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('is_reusable', $errors) }}">
            <label for="is_reusable" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.is_reusable') }} {{ Form::required('is_reusable', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::checkbox('is_reusable', 1, !empty($campaign) ? $campaign->is_reusable : false,  ['class' => 'input-sm', 'id' => 'is_reusable']) }}
                {{ Form::errorText('is_reusable', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('amount', $errors) }}">
            <label for="amount" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.amount') }} {{ Form::required('amount', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('amount', !empty($campaign) ? $campaign->amount : '', ['class' => 'form-control input-sm', 'id' => 'amount']) }}
                {{ Form::errorText('amount', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('discount', $errors) }}">
            <label for="discount" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.discount') }} {{ Form::required('discount', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('discount',!empty($campaign) ? $campaign->discount : '', ['class' => 'form-control input-sm', 'id' => 'discount']) }}
                {{ Form::errorText('discount', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('discount_type', $errors) }}">
            <label for="discount" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.discount_type') }} {{ Form::required('discount_type', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::select('discount_type', [trans('common.options_select')]+$discountType, !empty($campaign) ? $campaign->discount_type : '', ['class' => 'form-control input-sm', 'id' => 'discount_type']) }}
                {{ Form::errorText('discount_type', $errors) }}
            </div>
        </div>              
        <div class="form-group {{ Form::errorCSS('begin_at', $errors) }} reusable_code">
            <label for="reusable_code" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.reusable_code') }} {{ Form::required('reusable_code', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('reusable_code', !empty($campaign) ? $campaign->reusable_code : '', ['class' => 'form-control input-sm', 'id' => 'reusable_code']) }}
                {{ Form::errorText('begin_at', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('begin_at', $errors) }}">
            <label for="begin_at" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.begin_at') }} {{ Form::required('begin_at', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('begin_at', !empty($campaign) ? str_standard_to_local($campaign->begin_at) : str_date($today), ['class' => 'form-control input-sm date-picker', 'id' => 'begin_at']) }}
                {{ Form::errorText('begin_at', $errors) }}
            </div>
        </div>
        <div class="form-group {{ Form::errorCSS('expire_at', $errors) }}">
            <label for="expire_at" class="col-sm-2 control-label">{{ trans('admin.coupon.campaign.expire_at') }} {{ Form::required('expire_at', $campaign) }}</label>
            <div class="col-sm-5">
                {{ Form::text('expire_at',!empty($campaign) ? str_standard_to_local($campaign->expire_at) : '', ['class' => 'form-control input-sm date-picker', 'id' => 'expire_at']) }}
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
