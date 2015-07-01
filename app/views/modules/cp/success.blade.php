@extends ('layouts.default')

@section('scripts')
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section('content')
<div class="container">
    <h1 class="comfortaa orange text-center">{{ trans('cp.success') }}</h1>
    <div class="row">
        <div class="col-sm-offset-2 col-sm-8">
            <div class="alert alert-success">
                <p>{{ trans('cp.success_notice') }}</p>
            </div>
        </div>
    </div>
</div>
@stop
