<div class="search-wrapper">
<div class="container-fluid">
    <div class="row">
    @section('main-search')
        <div id="category-menu" class="hidden-sm hidden-xs">
            <ul class="nav navbar-nav">
                @foreach ($businessCategories as $category)
                <li class="dropdown">
                    <a href="{{ route('search') }}?q={{ urlencode($category->name) }}">
                        <img src="{{ asset_path('core/img/new/icons/small/'.$category->icon.'.png') }}" alt="">
                        {{ $category->name }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                    @foreach ($category->children as $child)
                        <li><a href="{{ route('search') }}?q={{ urlencode($child->name) }}">{{ $child->name }}</a></li>
                    @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </div>

        {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline default-search-form', 'id' => 'main-search-form']) }}
            <div class="form-group row">
                <div class="input-group margin-bottom-md">
                    <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                    <input class="form-control" type="text" id="js-queryInput" name="q" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('q') }}}">
                </div>

                <div class="input-group margin-bottom-md">
                    <span class="input-group-addon"><i class="fa fa-map-marker fa-fw"></i></span>
                    <input type="text" class="form-control" id="js-locationInput" name="location" placeholder="{{ trans('home.search.location') }}" value="{{{ Input::get('location') }}}">
                </div>

                <button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
            </div>

            <div class="datetime-wrapper row">
                <a href="#" class="datetime">
                    <i class="fa fa-calendar fa-big"></i> Date
                    <i class="fa fa-chevron-down fa-small"></i>
                </a>

                <a href="#" class="datetime">
                    <i class="fa fa-clock-o fa-big"></i> Time
                    <i class="fa fa-chevron-down fa-small"></i>
                </a>
            </div>

            {{ Form::hidden('lat', Session::get('lat')) }}
            {{ Form::hidden('lng', Session::get('lng')) }}
        {{ Form::close() }}

    @show
    </div>
</div>
</div>
