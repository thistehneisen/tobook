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
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">{{ trans('as.employees.name') }}</label>
            <div class="col-sm-5">
                {{ Form::text('name', (isset($employee)) ? $employee->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
                {{ Form::errorText('name', $errors) }}
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">{{ trans('as.employees.phone') }}</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    {{ Form::text('phone', (isset($employee)) ? $employee->phone:'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
                </div>
            </div>
            <div class="checkbox col-sm-5">
                <label> {{ Form::checkbox('is_subsribed_phone', 1, (isset($employee)) ? $employee->is_subsribed_email: false ); }} {{  trans('as.employees.is_subscribed_sms') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">{{ trans('as.employees.email') }}</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    {{ Form::text('email', (isset($employee)) ? $employee->email:'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
                </div>
            </div>
            <div class="checkbox col-sm-5">
                    <label> {{ Form::checkbox('is_subsribed_email', 1, (isset($employee)) ? $employee->is_subsribed_email: false ); }} {{  trans('as.employees.is_subscribed_email') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">{{ trans('as.employees.description') }}</label>
            <div class="col-sm-5">
                {{ Form::textarea('description', (isset($employee)) ? $employee->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.services') }}</label>
            <div class="col-sm-5">
                 {{ Form::select('services[]', $services, $employee->services->lists('id'), ['class' => 'form-control input-sm select2', 'id' => 'services', 'multiple' => 'multiple']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.status') }}</label>
              <div class="col-sm-5">
                 {{ Form::select('is_active', [0 => trans('common.inactive'), 1 => trans('common.active')], isset($employee) ? $employee->is_active : 1, ['class' => 'form-control input-sm', 'id' => 'status']) }}
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.avatar') }}</label>
            <div class="col-sm-5">
                <p><img src="{{ !empty($employee->avatar) ? $employee->getAvatarUrl() : asset('assets/img/avatar.jpg') }}" width="100" alt="" class="img-thumbnail"></p>
                {{ Form::file('avatar','',array('id'=>'','class'=>'')) }}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-5">
                <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
</div>
@stop
