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
    @foreach ($pages as $id => $settings)
        <div role="tabpanel" class="tab-pane" id="tab-{{ $id }}">
            {{ Form::open(['route' => ['admin.pages'], 'class' => 'form-horizontal well']) }}

            <div class="form-group">
                <label for="{{ $id }}" class="col-sm-1 control-label">{{ trans('common.content') }}</label>
                <div class="col-sm-11">
                    <input type="hidden" name="name" value="{{ $id }}">
                    <ul class="nav nav-tabs" role="tablist">
                    @foreach ($settings as $lang => $content)
                        <li role="presentation" class="{{ $content['active'] }}"><a href="#tab-html-multilang-{{ $id }}-{{ $lang }}" role="tab" data-toggle="tab">{{ $content['title'] }}</a></li>
                    @endforeach
                    </ul>

                    <div class="tab-content">
                    @foreach ($settings as $lang => $content)
                        <div role="tabpanel" class="tab-pane {{ $content['active'] }}" id="tab-html-multilang-{{ $id }}-{{ $lang }}">
                            <textarea name="content[{{ $lang }}]" rows="10" class="form-control ckeditor">{{ $content['content'] }}</textarea>
                        </div>
                    @endforeach
                    </div>
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
