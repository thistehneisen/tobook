@extends ('modules.as.layout')

@section ('content')
@include ('modules.as.employees.editTabs')
<br>
<div id="form-add-employee">
    {{ Form::open(['route' => ['as.employees.upsert', (isset($employee->id)) ? $employee->id: null], 'class' => 'form-horizontal well', 'role' => 'form']) }}
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
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">{{ trans('as.employees.phone') }}</label>
            <div class="col-sm-5">
                {{ Form::text('phone', (isset($employee)) ? $employee->phone:'', ['class' => 'form-control input-sm', 'id' => 'phone']) }}
            </div>
            <div class="checkbox col-sm-5">
                <label> {{ Form::checkbox('is_subsribed_phone', 1, (isset($employee)) ? $employee->is_subsribed_email: false ); }} {{  trans('as.employees.is_subscribed_sms') }}</label>
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">{{ trans('as.employees.email') }}</label>
            <div class="col-sm-5">
                {{ Form::text('email', (isset($employee)) ? $employee->email:'', ['class' => 'form-control input-sm', 'id' => 'email']) }}
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
                 {{ Form::select('service', $services, isset($employee) ? $employee->service_id : 1, ['class' => 'form-control input-sm', 'id' => 'services']) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.status') }}</label>
              <div class="col-sm-5">
                 {{ Form::select('status', ['0'=> trans('as.employees.active'),'1'=> trans('as.employees.in_active')], isset($employee) ? $employee->status : 1, ['class' => 'form-control input-sm', 'id' => 'status']) }}
            </div>
        </div>
         <div class="form-group">
            <label class="col-sm-2 control-label">{{  trans('as.employees.avatar') }}</label>
            <div class="col-sm-5">
                @if (isset($employee->avatar))
                <img src="{{ $employee->avatar }}" width="100" alt="" class="img-thumbnail"/>
                @else
                <img src="{{asset('assets/img/avatar.jpg')}}" width="100" alt="" class="img-thumbnail"/>
                @endif
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
