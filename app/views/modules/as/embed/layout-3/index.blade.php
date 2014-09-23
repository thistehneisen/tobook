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
                    <span class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> Now loading&hellip;
                    </span>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-3" href="#as-step-3">
                <h4 class="panel-title">
                    3. <span>Valitse päivä &amp; aika</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-3" data-parent="#varaa-as-bookings" class="panel-collapse collapse">
                <div class="panel-body">
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
                    Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                </div>
            </div>
        </div>
    </div>
</div>
@stop
