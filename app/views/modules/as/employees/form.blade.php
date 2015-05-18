@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2-bootstrap.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js') }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2_locale_'.App::getLocale().'.min.js') }}
    <script>
$(function () {
    $('select.select2').select2();
    $('#status').change(function(e) {
        console.log($(this).val());
        if($(this).val() == 1) {//freelancer
            $('.freelancer').show();
        } else {
            $('.freelancer').hide();
        }
    });
    $('#status').trigger('change');
});
    </script>
@stop

@section ('content')
    @include ('modules.as.employees.tab', $employee)

    @include ('el.messages')
<div id="form-add-employee">
    {{ Form::open(['route' => ['as.employees.upsert', (isset($employee->id)) ? $employee->id: null], 'class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data']) }}
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
            <label for="name" class="col-sm-2 control-label">{{ trans('as.employees.name') }} {{ Form::required('name', $employee) }}</label>
            <div class="col-sm-5">
                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">{{ trans('as.employees.phone') }} {{ Form::required('phone', $employee) }}</label>
            <div class="col-sm-5 {{ Form::errorCSS('phone', $errors) }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    {{ Form::text('phone', (isset($employee)) ? $employee->phone:'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                </div>
                {{ Form::errorText('phone', $errors) }}
            </div>
            <div class="checkbox col-sm-5">
                <label> {{ Form::checkbox('is_subscribed_sms', 1, (isset($employee)) ? $employee->is_subscribed_sms: false ); }} {{  trans('as.employees.is_subscribed_sms') }} {{ Form::required('is_subscribed_sms', $employee) }}</label>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">{{ trans('as.employees.email') }} {{ Form::required('email', $employee) }}</label>
            <div class="col-sm-5 {{ Form::errorCSS('email', $errors) }}">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    {{ Form::text('email', (isset($employee)) ? $employee->email:'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                </div>
                {{ Form::errorText('email', $errors) }}
            </div>
            <div class="checkbox col-sm-5">
                <label> {{ Form::checkbox('is_subscribed_email', 1, (isset($employee)) ? $employee->is_subscribed_email: false ); }} {{  trans('as.employees.is_subscribed_email') }} {{ Form::required('is_subscribed_email', $employee) }}</label>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">{{ trans('as.employees.description') }} {{ Form::required('description', $employee) }}</label>
            <div class="col-sm-5">
                {{ Form::textarea('description', (isset($employee)) ? $employee->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
            </div>
            <div class="checkbox col-sm-5">
                <label> {{ Form::checkbox('is_received_calendar_invitation', 1, (isset($employee)) ? $employee->is_received_calendar_invitation: false ); }} {{  trans('as.employees.is_received_calendar_invitation') }} {{ Form::required('is_received_calendar_invitation', $employee) }}</label>
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.services') }} {{ Form::required('services', $employee) }}</label>
            <div class="col-sm-5">
                 {{ Form::select('services[]', $services, $employee->services->lists('id'), ['class' => 'form-control input-sm select2', 'id' => 'services', 'multiple' => 'multiple']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.activation') }} {{ Form::required('is_active', $employee) }}</label>
              <div class="col-sm-5">
                 {{ Form::select('is_active', [0 => trans('common.inactive'), 1 => trans('common.active')], isset($employee) ? $employee->is_active : 1, ['class' => 'form-control input-sm', 'id' => 'activation']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.status') }} {{ Form::required('is_active', $employee) }}</label>
              <div class="col-sm-5">
                 {{ Form::select('status', [0 => trans('as.employees.employee'), 1 => trans('as.employees.freelancer')], isset($employee) ? $employee->status : 1, ['class' => 'form-control input-sm', 'id' => 'status']) }}
            </div>
        </div>
        <div class="form-group freelancer" style="display: none">
            <label class="col-sm-2 control-label">{{  trans('as.employees.business_id') }} {{ Form::required('is_active', $employee) }}</label>
              <div class="col-sm-5">
                 {{ Form::text('business_id', (isset($employee)) ? $employee->business_id:'', ['class' => 'form-control input-sm', 'id' => 'business_id']) }}
            </div>
        </div>
        <div class="form-group freelancer" style="display: none">
            <label class="col-sm-2 control-label">{{  trans('as.employees.account') }} {{ Form::required('is_active', $employee) }}</label>
              <div class="col-sm-5">
                 {{ Form::text('account', (isset($employee)) ? $employee->account:'', ['class' => 'form-control input-sm', 'id' => 'account']) }}
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.avatar') }} {{ Form::required('avatar', $employee) }}</label>
            <div class="col-sm-5">
                <p><img src="{{ !empty($employee->avatar) ? $employee->getAvatarUrl() : asset_path('core/img/avatar.jpg') }}" width="100" alt="" class="img-thumbnail"></p>
                {{ Form::file('avatar','',array('id'=>'','class'=>'')) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary" id="btn-save-employee">{{ trans('common.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
</div>
@stop
