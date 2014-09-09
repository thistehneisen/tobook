@extends ('layouts.default')

@section('styles')
    {{ HTML::style(asset('assets/css/search.css')) }}
@stop

@section('content')
<div class="row">
    <!-- left sidebar -->
    <div class="col-sm-4 col-md-4 col-lg-4 search-left">
        @yield('left-content')
    </div>

    <!-- right content -->
    <div class="col-sm-8 col-md-8 col-lg-8 search-right">
        @yield('right-content')
    </div>
</div>
@stop
