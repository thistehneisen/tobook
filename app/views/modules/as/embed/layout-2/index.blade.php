@extends ('modules.as.embed.embed')

@section ('content')
<div class="container">
    <div class="row">
        <div id="as-select">
            <div class="panel panel-default">
                <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.select_service') }}</strong></div>
                <div class="panel-body">
                    <div class="row" id="as-main-panel">
                        @include ('modules.as.embed.layout-2.services')
                    </div>

                    <div class="as-timetable-wrapper" id="as-timetable"></div>

                    <div class="row">
                        <div class="col-sm-12">
                            <a id="btn-book" href="{{ route('as.bookings.service.front.add') }}" class="btn btn-success disabled pull-right">{{ trans('as.embed.book') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="as-checkout" id="as-checkout"></div>
        <div class="as-success" id="as-success">
            <div class="panel panel-default">
                <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.thanks') }}</strong></div>
                <div class="panel-body">
                    <div class="alert alert-success">
                        <p>{{ trans('as.embed.layout_2.thanks_msg') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="employee-url" value="{{ route('as.embed.employees', Input::all()) }}">
<input type="hidden" name="timetable-url" value="{{ route('as.embed.l2.timetable', Input::all()) }}">
<input type="hidden" name="checkout-url" value="{{ route('as.embed.l2.checkout', Input::all()) }}">
@stop

