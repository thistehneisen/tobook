@extends ('modules.as.embed.embed')

@section ('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>{{ trans('as.embed.layout_2.select_service') }}</strong></div>
            <div class="panel-body">
                @include ('modules.as.embed.layout-2.services')
            </div>
        </div>
    </div>
</div>
@stop
