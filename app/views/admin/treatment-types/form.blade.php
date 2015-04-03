@extends ('layouts.admin')

@section('content')
    @if ($showTab === true)
        @include($tabsView, ['routes' => $routes, 'langPrefix' => $langPrefix])
    @endif

<div id="form-olut-upsert" class="modal-form">
    @include ('el.messages')

    @if (isset($item->id))
        <h4 class="comfortaa">{{ trans($langPrefix.'.edit') }}</h4>
    @else
        <h4 class="comfortaa">{{ trans($langPrefix.'.add') }}</h4>
    @endif
    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" id="language-tabs">
            @foreach (Config::get('varaa.languages') as $locale)
            <li role="presentation" class="@if ($locale === 'fi') {{ 'active' }} @endif "><a href="#{{$locale}}" aria-controls="{{$locale}}" role="tab" data-toggle="tab">{{ trans('home.languages.'.$locale)}}</a></li>
            @endforeach
        </ul>
        {{ Form::open(['route' => ['admin.treatment-types.upsert', isset($item->id) ? $item->id : ''], 'class' => 'form-horizontal well', 'role' => 'form']) }}
            <div class="tab-content">
            <div class="form-group">
                <label for="master_category" class="col-sm-2 control-label">{{ trans('admin.treatment-types.master_category') }}</label>
                <div class="col-sm-5">
                    <div class="input-group">
                        {{ Form::select('master_category_id', [trans('common.options_select')]+$masterCategories, isset($item->master_category_id) ? $item->master_category_id :0, ['class' => 'form-control input-sm', 'id' => 'master_category_id']) }}
                    </div>
                </div>
            </div>
            @foreach (Config::get('varaa.languages') as $locale)
                <div role="tabpanel" class="tab-pane @if ($locale === 'fi') {{ 'active' }} @endif" id="{{ $locale }}">
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">{{ trans('admin.master-cats.name') }}</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                {{ Form::text('name[' . $locale .']', !empty($data[$locale]['name']) ? ($data[$locale]['name']) : '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                   <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">{{ trans('admin.master-cats.description') }}</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                {{ Form::textarea('description[' . $locale .']', !empty($data[$locale]['description']) ? ($data[$locale]['description']) : '', ['class' => 'form-control input-sm']) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
