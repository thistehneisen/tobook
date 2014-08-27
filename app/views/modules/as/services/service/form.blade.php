@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-spinner/bootstrap-spinner.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/bootstrap-spinner/bootstrap-spinner.min.js')) }}
@stop

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.add') }}</strong></p>
    <p>{{ trans('as.services.add_desc') }}</p>
</div>

{{ Form::open(['route' => 'as.services.create', 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">{{ trans('as.services.nimi') }}</label>
        <div class="col-sm-5">
           {{ Form::text('name', (isset($service)) ? $service->name:'', ['class' => 'form-control input-sm', 'id' => 'name']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
        <div class="col-sm-5">
            {{ Form::textarea('description', (isset($service)) ? $service->description:'', ['class' => 'form-control input-sm', 'id' => 'description']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">{{ trans('as.services.hinta') }}</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">&euro;</span>
                {{ Form::text('price', (isset($service)) ? $service->price:'', ['class' => 'form-control input-sm', 'id' => 'price']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">{{ trans('as.services.duration') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('length', (isset($service)) ? $service->length : 0, ['class' => 'form-control', 'id' => 'length']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="before" class="col-sm-2 control-label">{{ trans('as.services.before') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('before', (isset($service)) ? $service->before : 0, ['class' => 'form-control', 'id' => 'before']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="after" class="col-sm-2 control-label">{{ trans('as.services.after') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('after', (isset($service)) ? $service->after : 0, ['class' => 'form-control', 'id' => 'after']) }}
                <div class="input-group-btn-vertical">
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-up"></i></button>
                    <button type="button" class="btn btn-default"><i class="fa fa-caret-down"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="total" class="col-sm-2 control-label">{{ trans('as.services.total') }}</label>
        <div class="col-sm-5">
           {{ Form::text('total', (isset($service)) ? $service->total:'', ['class' => 'form-control input-sm', 'id' => 'total', 'disabled'=>'disabled']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="category" class="col-sm-2 control-label">{{ trans('as.services.category') }}</label>
        <div class="col-sm-5">
            {{ Form::select('category_id', array(0=> trans('common.options_select'))+$categories, isset($service) ? $service->category_id :0, ['class' => 'form-control input-sm', 'id' => 'category']) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{ trans('as.services.is_active') }}</label>
        <div class="col-sm-5">
            <div class="radio">
                <label>
                    {{ Form::radio('is_active', 0, Input::get('is_active', isset($service->id) ? $service->is_active : true)) }}
                    {{ trans('common.active') }}
                </label>
            </div>
            <div class="radio">
                <label>
                    {{ Form::radio('is_active', 1, Input::get('is_active', isset($service->id) ? $service->is_active : false)) }}
                    {{ trans('common.inactive') }}
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="resource" class="col-sm-2 control-label">{{ trans('as.services.resource') }}</label>
        <div class="col-sm-5">
           {{ Form::select('resource', [trans('common.options_select')] + $resources, 1, ['class' => 'form-control input-sm', 'id' => 'resource']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="extra" class="col-sm-2 control-label">{{ trans('as.services.extra') }}</label>
        <div class="col-sm-5">
            {{ Form::select('extra', $extras, 1, ['class' => 'form-control input-sm', 'id' => 'extra']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">{{ trans('as.services.employees') }}</label>
        <div class="col-sm-5">
            @if ($employees->isEmpty())
                <p><small><em>{{ trans('as.services.no_employees') }}</em></small></p>
            @endif

            @foreach ($employees as $employee)
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for="">{{ Form::checkbox('employees[]', 1); }} {{ $employee->name}}</label>
                    </div>
                </div>
                <div class="col-sm-6">
                   {{ Form::select("plustimes[$employee->id]", array(5, 10, 15, 30), 0, ['class' => 'form-control input-sm', 'id' => 'plustime']) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
