@extends ('modules.as.embed.embed')

@section ('content')
<div class="container">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>{{ trans('as.embed.layout_2.select_service') }}</strong></div>
            <div class="panel-body">
                <div class="row" id="as-main-panel">
                    @include ('modules.as.embed.layout-2.services')
                </div>

                <div id="as-timetable"></div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="employee-url" value="{{ route('as.embed.employees', Input::all()) }}">
<input type="hidden" name="timetable-url" value="{{ route('as.embed.l2.timetable', Input::all()) }}">
@stop

