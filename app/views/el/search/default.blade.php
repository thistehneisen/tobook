<div class="search-wrapper container-fluid">
    <div class="row">
    @section('main-search')
        <div id="category-menu" class="hidden-sm hidden-xs">
            <ul class="nav navbar-nav">
                @foreach ($businessCategories as $category)
                <li class="dropdown">
                    <a href="{{ route('search') }}?q={{ urlencode($category->name) }}">
                        <i class="fa {{ $category->icon }}"></i>
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

        {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline', 'id' => 'main-search-form']) }}
            <div class="form-group">
                <div class="input-group input-group">
                    <div class="input-group-addon"><i class="fa fa-search"></i></div>
                    <input type="text" class="form-control typeahead" id="js-queryInput" name="q" placeholder="{{ trans('home.search.query') }}" value="{{{ Input::get('q') }}}" />
                </div>
            </div>

            <div class="form-group">
                <div class="input-group input-group">
                    <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                    <input type="text" class="form-control" id="js-locationInput" name="location" placeholder="{{ trans('home.search.location') }}" value="{{{ Input::get('location') }}}" />
                </div>
            </div>

            {{ Form::hidden('lat', Session::get('lat')) }}
            {{ Form::hidden('lng', Session::get('lng')) }}

            <button type="submit" class="btn btn-success">{{ trans('common.search') }}</button>
        {{ Form::close() }}

    @show
    </div>
</div>
