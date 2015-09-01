@section ('styles')
    @parent
    {{ HTML::style(asset('packages/flipclock/flipclock.css')) }}
    <style type="text/css">
    .clock{
       zoom: 0.3;
       -moz-transform: scale(0.3);
       margin-left: 30px;
    }
    </style>
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/flipclock/flipclock.min.js')) }}
    <script type="text/javascript">
        var clock;
        $(document).ready(function() {
            // Instantiate a counter
            clock = new FlipClock($('.clock'), 101, {
                clockFace: 'Counter'
            });
            // Use this code if you want to autoincrement the counter.
            var timer = new FlipClock.Timer(clock, {
                callbacks: {
                    interval: function() {
                        clock.increment();
                    }
                }
            });
            timer.start();
        });
    </script>
@stop

{{ Form::open(['route' => 'search', 'class' => 'form-search', 'method' => 'GET', 'id' => 'form-search']) }}
<div class="row">
    <input type="hidden" name="lat">
    <input type="hidden" name="lng">
    <input type="hidden" name="type">
    <!--search form-->
    <div class="col-sm-4">
        <div class="form-group row">
            <div class="col-sm-12 col-md-12">
                <h2 class="heading">{{ trans('home.search.tagline') }}</h2>
                <div class="alert alert-warning force-selection" style="display: none;">@lang('home.search.force_selection')</div>
                <div class="input-group margin-bottom-lg">
                    <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                    <input autocomplete="off" data-data-source="{{ route('ajax.services') }}" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.q')" name="q" class="form-control input-lg input-keyword" type="text" placeholder="{{ trans('home.search.query') }}">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-8 col-md-8">
                <div class="input-group margin-bottom-lg" id="location-dropdown-wrapper">
                    <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                    <input autocomplete="off" data-current-location="1" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.location')" data-target="#" name="location" class="form-control input-lg input-keyword {{ App::getLocale() }}" type="text" placeholder="{{ trans('home.search.location') }}" value="@lang('home.search.current_location')">
                    <ul id="big-cities-dropdown" class="dropdown-menu big-cities-dropdown" role="menu"></ul>
                </div>
            </div>
            <div class="col-sm-4 col-md-4">
                <button type="submit" class="btn btn-lg btn-success btn-search">{{ trans('home.search.button') }}</button>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-7 col-md-7">
                <h4>{{ trans('home.current_total_bookings') }}</h4>
            </div>
            <div class="col-sm-5 col-md-5 clock-wrapper">
                <div class="clock"></div>
            </div>
        </div>
    </div>
    <!--end search form-->
    <!--tutorial video link -->
    <div class="col-md-offset-2 col-sm-4 hidden-md hidden-sm hidden-xs">
        <a class="view-video" title="ToBook.lv - {{ trans('home.video_tutorial_text')}}" href="{{ trans('home.video_tutorial_link') }}">
        <img class="tutorial-video" width="560" height="315" src="{{ trans('home.image_tutorial_link') }}">
        </a>
    </div>
    <!--end tutorial video link -->
</div>
{{ Form::close() }}


