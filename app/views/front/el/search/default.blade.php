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
                        </ul>
                    </div>

                    <div class="row" id="js-geolocation-info" style="display: none;">
                        <div class="col-sm-offset-3 col-sm-6">
                            <div class="alert alert-warning" style="margin-top: 10px;">
                                <p><i class="fa fa-info-circle"></i> <span class="content">{{ trans('home.search.geo.info') }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline default-search-form', 'id' => 'main-search-form', 'data-update-location-url' => route('search.location')]) }}
                        <div class="form-group row">
                            <div class="input-group margin-bottom-md">
                                <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                                <input required style="width: 250px;" class="form-control" type="text" id="js-queryInput" name="q" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('q') }}}">
                            </div>

                            <div class="input-group margin-bottom-md">
                                <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                                <input type="text" class="form-control" id="js-locationInput" name="location" placeholder="{{ trans('home.search.location') }}" value="{{{ Input::get('location') }}}">
                            </div>

                            <button type="submit" class="btn btn-success btn-square">{{ trans('common.search') }}</button>
                        </div>
{{--
                        <div class="row datetime-selector">
                            <div class="col-sm-12">
                                <div class="datetime-wrapper">
                                    <a href="#" class="datetime-link">
                                        <i class="fa fa-calendar fa-big"></i> {{ trans('home.search.date') }}
                                        <i class="fa fa-chevron-down fa-small"></i>
                                    </a>
                                    <div class="datetime-control" data-format="YYYY-MM-DD" id="search-select-date">
                                        <input type="hidden" name="date">
                                    </div>
                                </div>

                                <div class="datetime-wrapper">
                                    <a href="#" class="datetime-link">
                                        <i class="fa fa-clock-o fa-big"></i> {{ trans('home.search.time') }}
                                        <i class="fa fa-chevron-down fa-small"></i>
                                    </a>
                                    <div class="datetime-control" data-format="HH:mm" id="search-select-time">
                                        <input type="hidden" name="time">
                                    </div>
                                </div>
                            </div>
                        </div>
--}}
                        {{ Form::hidden('lat', Session::get('lat')) }}
                        {{ Form::hidden('lng', Session::get('lng')) }}
                    {{ Form::close() }}
                @show
            </div>
        </div>
    </div>
</div>
