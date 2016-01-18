@if ($layout)
    @extends ($layout)
@endif

@section ('content')
    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#tab-general" role="tab" data-toggle="tab">{{ trans('common.general')}}</a></li>
@if ($item->is_business)
    <li role="presentation"><a href="#tab-business" role="tab" data-toggle="tab">Business Information</a></li>
    <li role="presentation"><a href="#tab-services" role="tab" data-toggle="tab">Active Services</a></li>
@endif
</ul>

<br>
@include ('el.messages')
<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="tab-general">
        {{ $lomake->open() }}

        @foreach ($lomake->fields as $field)
            @include('varaa-lomake::fields.group')
        @endforeach

        <div class="row">
            <div class="col-sm-offset-3 col-sm-6">
                <div class="alert alert-info">
                    <p>{{ trans('admin.create_note') }}</p>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="col-sm-2 col-sm-offset-1 control-label">{{ @trans('user.password') }}*</label>
            <div class="col-sm-6">
                {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="col-sm-2 col-sm-offset-1 control-label">{{ @trans('user.password_confirmation') }}*</label>
            <div class="col-sm-6">
                {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="business_name" class="col-sm-2 col-sm-offset-1 control-label">{{ @trans('user.business.name') }}*</label>
            <div class="col-sm-6">
                {{ Form::text('business_name', Input::get('business_name'), ['class' => 'form-control', 'id' => 'business_name']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="business_phone" class="col-sm-2 col-sm-offset-1 control-label">{{ @trans('user.business.phone') }}*</label>
            <div class="col-sm-6">
                {{ Form::text('business_phone', Input::get('business_phone'), ['class' => 'form-control', 'id' => 'business_phone']) }}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-6 col-sm-offset-3">
            <div class="checkbox">
                <label>
                {{ Form::checkbox('auto_confirm', 1, Input::get('auto_confirm') == 1) }}
                {{ @trans('user.business.auto_confirm') }}</label>
            </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </div>
        </div>

        {{ $lomake->close() }}
    </div> <!-- tab general -->

@if ($item->is_business)
    <div role="tabpanel" class="tab-pane" id="tab-business">
        @include ('user.el.business')
    </div> <!-- tab business -->

    <div role="tabpanel" class="tab-pane" id="tab-services">
        @include ('admin.users.modules', ['user' => $item])
    </div> <!-- tab services -->
@endif

</div>
@stop
