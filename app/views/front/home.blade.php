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
    {{--
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
    --}}

    <!-- Flash deals -->
    @include ('modules.fd.front', ['deals' => $deals])
</div>
@stop
