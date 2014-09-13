@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('styles')
    {{ HTML::style('assets/css/home.css') }}
@stop

@section('main-classes') container-fluid home @stop

@section('content')
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
        @foreach (range(1, 8) as $count)
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
    {{--
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
    --}}
</div>
@stop
