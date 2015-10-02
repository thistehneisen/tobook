<div class="text-center" name="timetable">
    <h3>{{ trans('as.embed.layout_2.choose') }}</h3>
    <div class="btn-group">
        @if($prev >= $today)
        <a href="#" id="btn-date-prev" class="btn btn-lg btn-as-timetable" data-date="{{ $prev->toDateString() }}" id="btn-date-prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
        @endif
        @foreach ($nav as $item)
        <a href="#" class="btn btn-default btn-as-timetable" data-date="{{ $item->start->toDateString() }}" id="btn-timetable-{{ $item->start->format('Ymd') }}">
        <div class="week-of-year">{{ trans('common.short.week') }} {{ $item->start->weekOfYear }}</div> {{ $item->start->format('d') }}. {{ trans('common.short.'.strtolower($item->start->format('M'))) }} &ndash; {{ $item->end->format('d') }}</a>
        @endforeach
        <a href="#" id="btn-date-next" class="btn btn-lg btn-as-timetable" data-date="{{ $next->toDateString() }}" id="btn-date-next"><i class="glyphicon glyphicon-chevron-right"></i></a>
    </div>

    <input type="hidden" name="start-date" value="{{ $date->toDateString() }}">

    <div class="row">
        <table class="table-timetable">
            <thead>
                <tr>
                @foreach ($dates as $date)
                    <th><h5 class="text-muted"><div class="day-in-week">{{ Util::td($date->format('D')) }}</div>{{ str_date($date) }}</h5></th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                @foreach ($timetable as $item)
                    <td class="{{ empty($item->time) ? 'empty' : '' }}">
                        @if (!empty($item->time))
                            @foreach ($item->time as $time => $employee)
                            <p><a href="#" data-date="{{ $item->date->toDateString() }}" data-employee-id="{{ $employee->id }}" class="as-time" id="btn-slot-{{ $item->date->format('Ymd') }}-{{ substr(preg_replace('#[^0-9]#', '', $time), 0, 4) }}">{{ $time }}</a></p>
                            @endforeach
                        @else
                            <p>{{ trans('as.embed.layout_2.unavailable') }}</p>
                        @endif
                    </td>
                @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
