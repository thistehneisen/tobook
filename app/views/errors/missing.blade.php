@extends ('layouts.default')

@section ('title')
    @parent :: 404
@stop

@section ('header')
    <h1 class="text-header">404</h1>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <p>The requested URL does not exist. Please check again or contact our support.</p>
    </div>
</div>
@stop
