@extends ('layouts.admin')

@section ('scripts')
{{ HTML::script(asset('packages/ckeditor/ckeditor.js')) }}
<script>
$(function () {
    $('#js-static-pages-tabs').find('a').first().click();
});
</script>
@stop

@section('content')
<h3>{{ trans('admin.nav.pages') }}</h3>
<div role="tabpanel">

    <ul class="nav nav-tabs" role="tablist" id="js-static-pages-tabs">
    @foreach ($pages as $id => $_)
        <li role="presentation"><a href="#tab-{{ $id }}" aria-controls="tab-{{ $id }}" role="tab" data-toggle="tab">@lang('home.pages.'.$id)</a></li>
    @endforeach
    </ul>

    <br>

    <div class="tab-content">
    @foreach ($pages as $id => $content)
        <div role="tabpanel" class="tab-pane" id="tab-{{ $id }}">
            {{ Form::open(['class' => 'form-horizontal well']) }}

            <div class="form-group">
                <label for="{{ $id }}" class="col-sm-1 control-label">Content</label>
                <div class="col-sm-11">
                    <textarea class="form-control ckeditor" name="{{ $id }}" id="{{ $id }}" rows="30">{{ $content }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-11">
                    <button type="submit" class="btn btn-primary">@lang('common.save')</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    @endforeach
    </div>

</div>
@stop
