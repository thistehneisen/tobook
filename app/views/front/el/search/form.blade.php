{{ Form::open(['route' => 'search', 'class' => 'form-search', 'method' => 'GET']) }}
<div class="form-group row">
    <div class="col-sm-4 col-md-4">
        <h2 class="heading">{{ trans('home.search.tagline') }}</h2>
        <div class="input-group margin-bottom-lg">
            <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
            <input required name="q" class="form-control input-lg" type="text" placeholder="{{ trans('home.search.query') }}">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-3 col-md-3">
        <div class="input-group margin-bottom-lg">
            <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
            <input name="location" class="form-control input-lg" type="text" placeholder="{{ trans('home.search.location') }}">
        </div>
    </div>

    <div class="col-sm-1 col-md-1">
        <button type="submit" class="btn btn-lg btn-success pull-right">{{ trans('home.search.button') }}</button>
    </div>
</div>

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
{{ Form::close() }}
