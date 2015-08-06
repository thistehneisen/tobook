{{ Form::open(['route' => 'search', 'class' => 'form-search', 'method' => 'GET', 'id' => 'form-search']) }}
<input type="hidden" name="lat">
<input type="hidden" name="lng">
<input type="hidden" name="type">

<div class="form-group row">
    <div class="col-sm-4 col-md-4">
        <h2 class="heading">{{ trans('home.search.tagline') }}</h2>
        <div class="alert alert-warning force-selection" style="display: none;">@lang('home.search.force_selection')</div>
        <div class="input-group margin-bottom-lg">
            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
            <input autocomplete="off" data-data-source="{{ route('ajax.services') }}" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.q')" name="q" class="form-control input-lg input-keyword" type="text" placeholder="{{ trans('home.search.query') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3 col-md-3">
        <div class="input-group margin-bottom-lg" id="location-dropdown-wrapper">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input autocomplete="off" data-current-location="1" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.location')" data-target="#" name="location" class="form-control input-lg input-keyword {{ App::getLocale() }}" type="text" placeholder="{{ trans('home.search.location') }}" value="@lang('home.search.current_location')">
            <ul id="big-cities-dropdown" class="dropdown-menu big-cities-dropdown" role="menu"></ul>
        </div>
    </div>

    <div class="col-sm-1 col-md-1">
        <button type="submit" class="btn btn-lg btn-success btn-search">{{ trans('home.search.button') }}</button>
    </div>
     <div class="col-sm-4 col-md-4">
     </div>
</div>

@if (App::environment() === 'tobook')
<div style="position: absolute;left: 500px;top: 150px" class="hidden-md hidden-sm hidden-xs">
{{ Form::close() }}
    <iframe width="560" height="315" src="https://www.youtube.com/embed/YNVCHyxY-kg" frameborder="0" allowfullscreen></iframe>
</div>
@endif
