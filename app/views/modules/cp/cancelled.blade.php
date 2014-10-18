@extends ('layouts.default')

@section('content')
<div class="container">
    <h1 class="comfortaa orange text-center">{{ trans('cp.cancelled') }}</h1>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="alert alert-warning">
                <p>{{ trans('cp.cancelled_notice') }}</p>
            </div>
        </div>
    </div>
</div>
@stop
