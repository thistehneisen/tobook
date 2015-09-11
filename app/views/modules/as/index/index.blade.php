@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2-bootstrap.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js') }}
    @if(App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2_locale_'.App::getLocale().'.min.js') }}
    @endif
     @if(App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    <script type="text/javascript">
        $(window).load(function () {
            CUSTOM_TIME = {{ $customTimes }};
        });
    </script>
@stop

@section('main-classes') as-wrapper @stop

@section ('content')
<?php
    $selectedDate = str_date($date);
    $dayOfWeek = $date->dayOfWeek;
    $routeName = 'as.index';
?>
<div class="container alert alert-info hidden-print hidden-xs hidden-sm">
    <p><strong>{{ trans('as.index.heading') }}</strong></p>
    <p>{{ trans('as.index.description') }}</p>
</div>

@include('modules.as.index._date_nav')

<div class="container-fluid row-no-padding">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
        <ul class="as-col-left-header">
            <li id="as-left-col-header" class="as-col-header">&nbsp;</li>
            @foreach ($workingTimes as $hour => $minutes)
                @foreach ($minutes as $minuteShift)
                    <li class="as-col-time">{{ sprintf('%02d', $hour) }} : {{ sprintf('%02d', $minuteShift) }}</li>
                @endforeach
            @endforeach
        </ul>
    </div>
    <div class="as-calendar col-lg-11 col-md-11 col-sm-11 col-xs-11">
        @foreach ($employees as $employee)
            @if ($employee->is_active)
            <div class="as-col">
            <ul id="as-ul">
                <li class="as-col-header as-col-fixed">
                    <a class="as-col-name" href="{{ route('as.employee', ['id'=> $employee->id ]) }}">{{ $employee->name }}</a>
                    @if($user->asOptions['show_quick_workshift_selection'])
                    <a class="btn-workshift-switch" data-custom-time-id="{{ $employee->getActiveWorkshift($selectedDate) }}" data-date="{{ $selectedDate }}" data-employee-id="{{ $employee->id }}" href="#"><i class="fa fa-clock-o as-workshift-switch"></i></a>
                    @endif
                </li>
                @foreach ($workingTimes as $hour => $minutes)
                    @foreach ($minutes as $minuteShift)
                        <?php $slotClass = $employee->getSlotClass(str_standard_date($selectedDate), $hour, $minuteShift); ?>
                        @include('modules.as.index._calendar')
                    @endforeach
                @endforeach
            </ul>
            </div>
            @endif
        @endforeach
    </div>
</div>
@include('modules.as.index._modals')
</div>
@stop
