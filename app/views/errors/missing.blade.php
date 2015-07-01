@extends ('layouts.default')

@section ('title')
    404
@stop

@section('scripts')
    {{ HTML::script(asset_path('core/scripts/home.js')) }}
    {{ HTML::script(asset_path('core/scripts/business.js')) }}
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <h1>404</h1>
        <p>The requested URL does not exist. Please check again or contact our support.</p>
    </div>
</div>
@stop
