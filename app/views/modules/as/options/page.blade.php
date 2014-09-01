@extends ('modules.as.layout')

@section ('scripts')
    @parent
<script>
$(function () {
    // Activate the first tab
    $('ul.nav-tabs').children('li:first').addClass('active');
    $('div.tab-pane:first').addClass('active');
});
</script>
@stop

@section ('content')
<h3>{{ trans('as.options.'.$page.'.index') }}</h3>

<div class="alert alert-info">
    <p><strong>{{ trans('as.options.'.$page.'.heading') }}</strong></p>
    <p>{{ trans('as.options.'.$page.'.info') }}</p>
</div>

<ul class="nav nav-tabs" role="tablist">
    @foreach ($sections as $section)
    <li><a href="#section-{{ $section }}" role="tab" data-toggle="tab">{{ trans('as.options.'.$page.'.'.$section) }}</a></li>
    @endforeach
</ul>

<br>


{{ Form::open(['class' => 'form-horizontal']) }}
<div class="tab-content">
    @foreach ($fields as $section => $controls)
    <div class="tab-pane" id="section-{{ $section }}">
        @foreach ($controls as $field)
        <div class="form-group">
            <label class="control-label col-sm-3">{{ trans('as.options.'.$page.'.'.$field->getName()) }}</label>
            <div class="col-sm-6">{{ $field }}</div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
