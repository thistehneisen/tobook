<div class="search-wrapper default">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @section('main-search')
                    <div id="category-menu" class="hidden-sm hidden-xs">
                        <ul class="nav navbar-nav front-nav">
                            @foreach ($masterCategories as $category)
                            <li class="dropdown">
                                <a href="{{ $category->url }}" title="{{{ $category->name }}}">{{{ $category->name }}}
                            @if ($category->treatments->isEmpty() === false)
                                <span class="caret"></span>
                            @endif
                                </a>

                            @if ($category->treatments->isEmpty() === false)
                                <ul class="dropdown-menu" @if(!empty($category->background_image)) style="background: #fff url({{ $category->background_image }}) center center no-repeat; background-size: cover;" @endif>
                                @foreach ($category->treatments as $treatment)
                                    <li><a href="{{ $treatment->url }}" title="{{{ $treatment->name }}}">{{{ $treatment->name }}}</a></li>
                                @endforeach
                                </ul>
                            @endif
                            </li>
                            @endforeach
                            @if (App::environment() === 'tobook' || App::environment() === 'local')
                                <li class="tutorial-video-link"><a class="view-video" title="ToBook.lv - {{ trans('home.video_tutorial_text')}}?" href="{{ Config::get('varaa.tutorial_video') }}">{{ trans('home.video_tutorial_text')}}</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="row" id="js-geolocation-info" style="display: none;">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="alert alert-warning" style="margin-top: 10px;">
                                <p><i class="fa fa-info-circle"></i> <span class="content">{{ trans('home.search.geo.info') }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{ Form::open(['route' => 'search', 'class' => 'form-inline default-search-form', 'method' => 'GET', 'id' => 'form-search']) }}
                        <input type="hidden" name="lat">
                        <input type="hidden" name="lng">
                        <input type="hidden" name="type">

                        <div class="form-group navbar-form-group">
                            <div class="alert alert-warning force-selection" style="display: none;">@lang('home.search.force_selection')</div>
                            <div class="input-group margin-bottom-md">
                                <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                                <input autocomplete="off" data-data-source="{{ route('ajax.services') }}" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.q')" name="q" class="form-control input-keyword" type="text" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('q') }}}" style="width: 250px;" >
                            </div>

                            <div class="input-group margin-bottom-md" id="location-dropdown-wrapper">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                                <input autocomplete="off" data-current-location="1" data-trigger="manual" data-placement="bottom" title="@lang('home.search.validation.location')" data-target="#" name="location" class="form-control input-keyword {{ App::getLocale() }}" type="text" placeholder="{{ trans('home.search.location') }}"  value="{{{ Input::get('location', trans('home.search.current_location')) }}}">

                                <ul id="big-cities-dropdown" class="dropdown-menu big-cities-dropdown" role="menu"></ul>
                            </div>

                            <button type="submit" class="btn btn-success btn-square">{{ trans('common.search') }}</button>
                        </div>
                    {{ Form::close() }}
                @show
            </div>
        </div>
    </div>
</div>
