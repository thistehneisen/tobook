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
    {{ HTML::script(asset('packages/bootstrap-spinner/bootstrap-spinner.min.js')) }}
    <script>
$(function () {
    $('select.select2').select2();
});
    </script>
@stop

@section ('content')
<div class="alert alert-info">
    <p><strong>{{ trans('as.services.add') }}</strong></p>
    <p>{{ trans('as.services.add_desc') }}</p>
</div>
@if ($errors->any())
<div class="alert alert-warning">
    <ul>
        {{ implode('', $errors->all('<li>:message</li>')) }}
    </ul>
</div>
@endif
@if(!empty($service))
    @include ('modules.as.services.service.tab', $service)
@endif
{{ Form::open(['route' => ['as.services.upsert', isset($service->id) ? $service->id : ''], 'class' => 'form-horizontal well', 'role' => 'form']) }}
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="language-tabs">
                @foreach (Config::get('varaa.languages') as $locale)
                <li role="presentation" class="@if ($locale === App::getLocale()) {{ 'active' }} @endif "><a href="#{{$locale}}" aria-controls="{{$locale}}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="tab-content">
        @foreach (Config::get('varaa.languages') as $locale)
        <div role="tabpanel" class="tab-pane @if ($locale === App::getLocale()) {{ 'active' }} @endif" id="{{ $locale }}">
            <div class="form-group {{ Form::errorCSS('name', $errors) }}">
                <label for="names" class="col-sm-2 control-label">{{ trans('as.services.name') }}</label>
                <div class="col-sm-5">
                    {{ Form::text('names[' . $locale .']', !empty($data[$locale]['name']) ? ($data[$locale]['name']) : '', ['class' => 'form-control input-sm']) }}
                    {{ Form::errorText('name', $errors) }}
                </div>
            </div>
            <div class="form-group {{ Form::errorCSS('description', $errors) }}">
                <label for="names" class="col-sm-2 control-label">{{ trans('as.services.description') }}</label>
                <div class="col-sm-5">
                    {{ Form::textarea('descriptions[' . $locale .']', !empty($data[$locale]['description']) ? ($data[$locale]['description']) : '', ['class' => 'form-control input-sm']) }}
                    {{ Form::errorText('description', $errors) }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="form-group">
        <label for="price" class="col-sm-2 control-label">{{ trans('as.services.price') }}</label>
        <div class="col-sm-5">
            <div class="input-group">
                <span class="input-group-addon">{{ Settings::get('currency') }}</span>
                {{ Form::text('price', isset($service->price) ? $service->price : '', ['class' => 'form-control input-sm', 'id' => 'price']) }}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="duration" class="col-sm-2 control-label">{{ trans('as.services.duration') }}</label>
        <div class="col-sm-5">
            <div class="input-group input-group-sm spinner" data-inc="5" data-positive="true">
                {{ Form::text('during', isset($service->during) ? $service->during : 0, ['class' => 'form-control', 'id' => 'during']) }}
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
                {{ Form::text('before', isset($service->before) ? $service->before : 0, ['class' => 'form-control', 'id' => 'before']) }}
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
                {{ Form::text('after', isset($service->after) ? $service->after : 0, ['class' => 'form-control', 'id' => 'after']) }}
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
            {{ Form::text('total', isset($service->length) ? $service->length : 0, ['class' => 'form-control input-sm', 'id' => 'total', 'disabled'=>'disabled']) }}
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('master_category_id', $errors) }}">
        <label for="category" class="col-sm-2 control-label">{{ trans('as.services.master_categories') }}</label>
        <div class="col-sm-5">
            {{ Form::select('master_category_id', [trans('common.options_select')]+$master_categories, isset($service->master_category_id) ? $service->master_category_id :0, ['class' => 'form-control input-sm', 'id' => 'master_category_id']) }}
            {{ Form::errorText('master_category_id', $errors) }}
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('treatment_type_id', $errors) }}">
        <label for="category" class="col-sm-2 control-label">{{ trans('as.services.treatment_types') }}</label>
        <div class="col-sm-5">
            {{ Form::select('treatment_type_id', [trans('common.options_select')]+$treatment_types, isset($service->treatment_type_id) ? $service->treatment_type_id :0, ['class' => 'form-control input-sm', 'id' => 'treatment_type_id']) }}
            {{ Form::errorText('treatment_type_id', $errors) }}
        </div>
    </div>
    <div class="form-group {{ Form::errorCSS('category_id', $errors) }}">
        <label for="category" class="col-sm-2 control-label">{{ trans('as.services.category') }}</label>
        <div class="col-sm-5">
            {{ Form::select('category_id', [trans('common.options_select')]+$categories, isset($service->category_id) ? $service->category_id :0, ['class' => 'form-control input-sm', 'id' => 'category']) }}
            {{ Form::errorText('category_id', $errors) }}
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{ trans('as.services.is_active') }}</label>
        <div class="col-sm-5">
            <div class="radio">
                <label>
                    {{ Form::radio('is_active', 1, Input::get('is_active', isset($service->id) ? $service->is_active : 1)) }}
                    {{ trans('common.active') }}
                </label>
            </div>
            <div class="radio">
                <label>
                    {{ Form::radio('is_active', 0, Input::get('is_active', isset($service->id) ? !$service->is_active : 0)) }}
                    {{ trans('common.inactive') }}
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{{ trans('as.services.is_discount_included') }}</label>
        <div class="col-sm-5">
            <div class="radio">
                <label>
                    {{ Form::radio('is_discount_included', 1, Input::get('is_discount_included', isset($service->id) ? $service->is_discount_included : 1)) }}
                    {{ trans('common.yes') }}
                </label>
            </div>
            <div class="radio">
                <label>
                    {{ Form::radio('is_discount_included', 0, Input::get('is_discount_included', isset($service->id) ? !$service->is_discount_included : 0)) }}
                    {{ trans('common.no') }}
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="resource" class="col-sm-2 control-label">{{ trans('as.services.resource') }}</label>
        <div class="col-sm-5">
           {{ Form::select('resources[]', $resources, isset($service->resources->first()->id) ? $service->resources->first()->id : -1, ['class' => 'form-control input-sm select2', 'id' => 'resources', 'multiple' => 'multiple']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="room" class="col-sm-2 control-label">{{ trans('as.services.room') }}</label>
        <div class="col-sm-5">
           {{ Form::select('rooms[]', $rooms, $service->rooms->lists('id'), ['class' => 'form-control input-sm select2', 'id' => 'rooms', 'multiple' => 'multiple']) }}
    </div>
    </div>
    <div class="form-group">
        <label for="extras" class="col-sm-2 control-label">{{ trans('as.services.extra') }}</label>
        <div class="col-sm-5">
            {{ Form::select('extras[]', $extras, $service->extraServices()->lists('id'), ['class' => 'form-control input-sm select2', 'id' => 'extra', 'multiple' => 'multiple']) }}
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label">{{ trans('as.services.employees') }}</label>
        <div class="col-sm-5">
            @if ($employees->isEmpty())
                <p><small><em>{{ trans('as.services.no_employees') }}</em></small></p>
            @endif
            <?php $selectedEmployees = $service->employees->lists('id'); ?>
            @foreach ($employees as $employee)
            <div class="row" style="margin-bottom: 5px;">
                <div class="col-sm-6">
                    <div class="checkbox">
                    <label for="">{{ Form::checkbox('employees[]', $employee->id, in_array($employee->id, $selectedEmployees), ['id' => 'employee-' . $employee->id]); }} {{ $employee->name}}</label>
                    </div>
                </div>
                <div class="col-sm-6">
                   {{ Form::select("plustimes[$employee->id]", array_combine(range(-60, 60, 5), range(-60, 60, 5)), $employee->getPlustime($service->id), ['class' => 'form-control input-sm', 'id' => 'plustime']) }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            <button type="submit" class="btn btn-primary" id="btn-save-service">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
<input type="hidden" id="get_treatment_types_url" value="{{ route('as.master-cats.treatment-types')}}"/>
@stop

@section ('scripts')
<script type="text/javascript">
    $(function(){
        $treatments = $('#treatment_type_id');
        $('#master_category_id').change(function(e){
            var master_category_id = $(this).val();
            $treatments.empty();
            if (master_category_id !== '-1' && master_category_id !== '') {
                $.ajax({
                    url: $('#get_treatment_types_url').val(),
                    data: {
                        master_category_id: master_category_id,
                    },
                    dataType: 'json'
                }).done(function (data) {
                    $treatments.empty();

                    $.each(data, function (index, value) {
                        $treatments.append(
                            $('<option>', {
                                value: value.id,
                                text: value.name
                            })
                        );
                    });
                });
            } else {
                $treatments.empty();
            }
        });

        $('#language-tabs a').click(function (e) {
          e.preventDefault();
          $(this).tab('show');
        });
    });
</script>
@stop
