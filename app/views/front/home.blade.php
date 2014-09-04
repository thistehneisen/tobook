@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('main-classes') container-fluid home @stop

@section('content')
<!-- Search box -->
<div class="search-wrapper row">
    <div class="search-inner col-md-8 col-md-offset-2 text-center">
        <h2>{{ trans('home.search_tagline') }}</h2>
        {{ Form::open(['route' => 'search', 'method' => 'GET', 'class' => 'form-inline']) }}
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <div class="input-group-addon"><i class="fa fa-search"></i></div>
                    <input type="text" class="form-control" id="js-queryInput" name="query" placeholder="{{ trans('home.search_query') }}" />
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-lg">
                    <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                    <input type="text" class="form-control" id="js-locationInput" name="location" placeholder="{{ trans('home.search_place') }}" />
                </div>
            </div>
            <button type="submit" class="btn btn-lg btn-orange">{{ trans('common.search') }}</button>
        {{ Form::close() }}
    </div>
</div>

<div class="container text-center">
    <!-- Next available time slot -->
    <div class="row">
        <h3 class="comfortaa">{{ trans('Next available time slot') }}</h3>
        <div class="form-group col-md-4 col-md-offset-4">
        <select name="" id="" class="form-control">
            <option value="">{{ trans('Hairdresser') }}</option>
        </select>
        </div>
        <div class="clearfix"></div>
        @foreach ([0, 1, 2, 3] as $count)
        <div class="available-slot col-md-3 col-sm-3">
            <img src="{{ asset('assets/img/slides/1.jpg') }}" alt="" class="img-responsive" />
            <div class="info text-left">
                <h4>Ms. Hesku Parturi</h4>
                <p>Lönnrotinkatu 19</p>
                <p></p>
                <p>60-80€</p>
                <p>Tue 24th
                    <a href="" class="btn btn-sm btn-default">10:00</a>
                    <a href="" class="btn btn-sm btn-default">11:00</a>
                    <a href="" class="btn btn-sm btn-default">12:00</a>
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Flash deals -->
    <div class="row">
        <h3 class="comfortaa">{{ trans('Flashdeals') }}</h3>
        @foreach ([0, 1, 2, 3] as $count)
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Hairdresser</div>
                <ul class="list-group">
                    @foreach ([0, 1, 2, 3, 4, 5] as $count)
                    <li class="list-group-item">
                        <div class="flashdeal-item text-left">
                            <h4 class="text-center">Mikael Parturi <span class="orange">-90%</span></h4>
                            <p>Malminkatu 8</p>
                            <p></p>
                            <p>20€</p>
                            <p>Wed 25th
                                <a href="" class="btn btn-orange">20:00</a>
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="panel-footer">Explore more</div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@stop
