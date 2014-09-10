@extends ('layouts.search')

@section('title')
    @parent :: {{ trans('common.search') }}
@stop

@section('left-content')
@if ($businesses->count() === 0)
    <p class="alert alert-info">
        {{ trans('search.no_result') }}
    </p>
@else
    @foreach ($businesses as $biz)
    <div class="business-row row">
        <img src="{{ asset('assets/img/slides/1.jpg') }}" alt="" class="img-responsive col-md-6" />
        <div class="col-md-6">
            <h4>{{{ $biz->first_name }}} {{{ $biz->last_name }}}</h4>
            <p>{{{ $biz->address }}}, {{{ $biz->city }}}</p>
            <p>60-80€</p>
        </div>
    </div>
    @endforeach
@endif
@stop
