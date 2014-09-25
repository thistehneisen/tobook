@extends ('modules.as.embed.embed')

@section ('content')

<div class="container-fluid">
    <div class="panel-group" id="varaa-as-bookings">
        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-1" href="#as-step-1">
                <h4 class="panel-title">
                    1. <span>Valitse palvelun tyyppi</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-1" data-parent="#varaa-as-bookings" class="panel-collapse collapse in">
                <div class="panel-body">
                    @include ('modules.as.embed.layout-3.services')
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-2" href="#as-step-2">
                <h4 class="panel-title">
                    2. <span>Valitse henkilö</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-2" data-parent="#varaa-as-bookings" class="panel-collapse collapse" data-url="{{ route('as.embed.employees', Input::all()) }}">
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> Now loading&hellip;
                    </p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-3" href="#as-step-3">
                <h4 class="panel-title">
                    3. <span>Valitse päivä &amp; aika</span>
                    <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                    <i id="as-datepicker" class="glyphicon glyphicon-calendar pull-right"></i>
                </h4>
            </div>
            <div id="as-step-3" data-parent="#varaa-as-bookings" class="panel-collapse collapse" data-url="{{ route('as.embed.timetable', Input::all()) }}">
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> Now loading&hellip;
                    </p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-4" href="#as-step-4">
                <h4 class="panel-title">
                    4. <span>Yhteystietosi</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-4" data-parent="#varaa-as-bookings" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> Now loading&hellip;
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
