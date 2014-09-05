@extends ('layouts.search')

@section('title')
    @parent :: {{ trans('common.search') }}
@stop

@section('left-content')
@foreach (range(0, 100) as $count)
<div class="business-item row">
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
@stop
