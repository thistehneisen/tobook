@extends ('modules.rb.layout')

@section ('content')
<h3>{{ trans('rb.options.'.$page.'.index') }}</h3>

<!-- <div class="alert alert-info">
    <p><strong>{{ trans('rb.options.'.$page.'.heading') }}</strong></p>
    <p>{{ trans('rb.options.'.$page.'.info') }}</p>
</div> -->

<ul class="nav nav-tabs" role="tablist">
    @foreach ($sections as $index => $section)
    <li class="{{ $index === 0 ? 'active' : '' }}"><a href="#section-{{ $section }}" role="tab" data-toggle="tab">{{ trans('rb.options.'.$page.'.'.$section) }}</a></li>
    @endforeach
</ul>
<br>

@include ('el.messages')

{{ Form::open(['route' => ['rb.options', $page], 'class' => 'form-horizontal']) }}
<div class="tab-content">
    <?php $index = 0 ?>
    @foreach ($fields as $section => $controls)
    <div class="tab-pane {{ $index === 0 ? 'active' : '' }}" id="section-{{ $section }}">
        @foreach ($controls as $field)
        <div class="form-group">
            <label class="control-label col-sm-3">{{ trans('rb.options.'.$page.'.'.$field->name) }}</label>
            <div class="col-sm-6">{{ $field->render() }}</div>
        </div>
        @endforeach
    </div>
    <?php $index++; ?>
    @endforeach
</div>

    <div class="form-group form-group-sm">
        <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">{{ trans('common.save') }}</button>
        </div>
    </div>
{{ Form::close() }}
@stop
