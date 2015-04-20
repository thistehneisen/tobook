@extends ('layouts.admin')

@section('content')
<h3>{{ trans('admin.nav.seo') }}</h3>

{{ Form::open(['route' => 'admin.seo', 'class' => 'form-horizontal']) }}
<div role="tabpanel">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
    @foreach($languages as $lang)
        <li role="presentation" @if ($lang === $locale) class="active" @endif><a href="#tab-{{ $lang }}" role="tab" data-toggle="tab" aria-expanded="true">{{ strtoupper($lang) }}</a></li>
    @endforeach
    </ul>

    <br>

    <div class="tab-content">
    @foreach($languages as $lang)
    <!-- Tab panes -->
        <div role="tabpanel" class="tab-pane @if ($lang === $locale) active @endif" id="tab-{{ $lang }}">

        @foreach ($urls as $url)
            <h4><code>{{ $url }}</code></h4>
            @foreach (['meta_description', 'meta_title', 'meta_keywords'] as $key)
            <div class="form-group">
                <label class="form-label col-sm-offset-1 col-sm-2">{{ trans('user.business.'.$key) }}</label>
                <div class="col-sm-6">
                    <input class="form-control" name="{{ $key.'['.$lang.']' }}" type="text" value="">

                </div>
            </div>
            @endforeach
        @endforeach

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button class="btn btn-primary" type="submit">{{ trans('common.save') }}</button>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
{{ Form::close() }}

@stop
