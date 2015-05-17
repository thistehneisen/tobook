{{ Form::open(['route' => 'search', 'class' => 'form-search', 'method' => 'GET', 'id' => 'form-search']) }}
<input type="hidden" name="current-location-selected" value="0">
<input type="hidden" name="lat" value="{{ Session::get('lat') }}">
<input type="hidden" name="lng" value="{{ Session::get('lng') }}">

<div class="form-group row">
    <div class="col-sm-4 col-md-4">
        <h2 class="heading">{{ trans('home.search.tagline') }}</h2>
        <div class="input-group margin-bottom-lg">
            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
            <input autocomplete="off" name="q" class="form-control input-lg input-keyword" type="text" placeholder="{{ trans('home.search.query') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3 col-md-3">
        <div class="input-group margin-bottom-lg">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input autocomplete="off" data-toggle="dropdown" data-target="#" name="location" class="form-control input-lg input-keyword {{ App::getLocale() }}" type="text" placeholder="{{ trans('home.search.location') }}">
            <ul id="big-cities-dropdown" class="dropdown-menu big-cities-dropdown" role="menu">
                <li role="presentation"><a href="#" class="city" id="ask-current-location"><strong>@lang('home.search.current_location')</strong></a></li>
                <li role="presentation" class="divider"></li>
                @foreach ($cities as $city)
                <li role="presentation"><a href="#" class="city">{{{ $city }}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-sm-1 col-md-1">
        <button type="submit" class="btn btn-lg btn-success btn-search">{{ trans('home.search.button') }}</button>
    </div>
</div>
{{--
<div class="form-group row">
    <div class="col-sm-4 col-md-4">
        <div class="datetime-wrapper">
            <a href="#" class="datetime-link">
                <i class="fa fa-calendar fa-big"></i> {{ trans('home.search.date') }}
                <i class="fa fa-chevron-down fa-small"></i>
            </a>
            <div class="datetime-control" data-format="YYYY-MM-DD" id="search-select-date">
                <input type="hidden" name="d">
            </div>
        </div>

        <div class="datetime-wrapper">
            <a href="#" class="datetime-link">
                <i class="fa fa-clock-o fa-big"></i> {{ trans('home.search.time') }}
                <i class="fa fa-chevron-down fa-small"></i>
            </a>
            <div class="datetime-control" data-format="HH:mm" id="search-select-time">
                <input type="hidden" name="t">
            </div>
        </div>
    </div>
</div>
--}}
{{ Form::close() }}
