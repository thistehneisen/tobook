@extends ('modules.as.layout')

@section('content')
    @if ($showTab === true)
        @include($tabsView, ['routes' => $routes, 'langPrefix' => $langPrefix])
    @endif

@if ($errors->any())
<div class="alert alert-warning">
    <ul>
        {{ implode('', $errors->all('<li>:message</li>')) }}
    </ul>
</div>
@endif

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif
    <div role="tabpanel">
        {{ Form::open(['route' => ['as.services.categories.upsert', isset($item->id) ? $item->id : ''], 'class' => 'form-horizontal well', 'role' => 'form']) }}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist" id="language-tabs">
                        @foreach (Config::get('varaa.languages') as $locale)
                        <li role="presentation" class="@if ($locale === App::getLocale()) {{ 'active' }} @endif"><a href="#{{$locale}}" aria-controls="{{$locale}}" role="tab" data-toggle="tab">{{ strtoupper($locale) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="tab-content">
            @foreach (Config::get('varaa.languages') as $locale)
                <div role="tabpanel" class="tab-pane @if ($locale === App::getLocale()) {{ 'active' }} @endif" id="{{ $locale }}">
                    <div class="form-group {{ Form::errorCSS('name', $errors) }}">
                        <label for="names" class="col-sm-2 control-label">{{ trans('as.services.categories.name') }}@if ($locale === Config::get('varaa.default_language')) {{ '*' }} @endif </label>
                        <div class="col-sm-5">
                            {{ Form::text('names[' . $locale .']', !empty($data[$locale]['name']) ? ($data[$locale]['name']) : '', ['class' => 'form-control input-sm']) }}
                            {{ Form::errorText('name', $errors) }}
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="descriptions" class="col-sm-2 control-label">{{ trans('as.services.categories.description') }}</label>
                        <div class="col-sm-5">
                            {{ Form::textarea('descriptions[' . $locale .']', !empty($data[$locale]['description']) ? ($data[$locale]['description']) : '', ['class' => 'form-control input-sm']) }}
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
            <div class="form-group ">
                <label for="is_show_front" class="col-sm-2 control-label">{{ trans('as.services.categories.is_show_front') }}</label>
                <div class="col-sm-5">
                    <div class="radio">
                        <label>
                            {{ Form::radio('is_show_front', 0, Input::get('is_show_front', isset($category->id) ? $category->is_show_front : 1)) }}
                            {{ trans('common.no') }}
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {{ Form::radio('is_show_front', 1, Input::get('is_show_front', isset($category->id) ? $category->is_show_front : 1)) }}
                            {{ trans('common.yes') }}
                        </label>
                    </div>
                    <!-- Validation error -->
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-5">
                    <button type="submit" class="btn btn-primary" id="btn-save-service">{{ trans('common.save') }}</button>
                </div>
            </div>
        {{ Form::close() }}
    </div>
</div>
@stop

@section ('bottom_script')
<script>
$(function () {
    $('#language-tabs a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    });
});
</script>
@stop
