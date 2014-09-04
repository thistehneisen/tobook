@extends ('layouts.default')

@section('styles')
@stop

@section('content')
<div class="row">
    <!-- left sidebar -->
    <div class="col-sm-3 col-md-3 col-lg-2">
        blah
    </div>

    <!-- right content -->
    <div class="col-sm-9 col-md-9 col-lg-9">
        @yield('intro_content')
    </div>
</div>
@stop
