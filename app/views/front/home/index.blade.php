@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('main-classes')container-fluid @stop

@section('content')
<div class="search-wrapper row">
    <div class="search-inner col-md-6 col-md-offset-3 text-center">
        <h2>{{ trans('home.search_anything') }}</h2>
        <form class="form-inline" role="form" action="">
            <div class="form-group">
                <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                <label class="sr-only" for="query">{{ trans('home.search_query') }}</label>
                <input type="text" class="form-control" id="id_query" placeholder="{{ trans('home.search_query') }}">
            </div>
            <div class="form-group">
                <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-map-marker"></i></div>
                <input type="text" class="form-control" id="id_place" placeholder="{{ trans('home.search_place') }}">
            </div>
            <button type="submit" class="btn btn-default btn-success">{{ trans('home.search') }}</button>
        </form>
    </div>
</div>
@stop
