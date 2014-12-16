@extends ('modules.as.embed.embed')

@section ('content')
<div class="container-fluid">
    <div class="panel-group" id="varaa-as-bookings" data-inhouse="{{ isset($inhouse) ? (int) $inhouse : 0 }}">
        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-1" href="#as-step-1">
                <h4 class="panel-title">
                    5. <span>{{ trans('as.embed.layout_3.confirm_service') }}</span>
                </h4>
            </div>
            <div id="as-step-1" data-parent="#varaa-as-bookings" class="panel-collapse collapse in">
                <div class="panel-body">
                    @include('modules.as.embed.layout-3.confirm-plain')
                </div>
            </div>
        </div>
    </div>
</div>
@stop
