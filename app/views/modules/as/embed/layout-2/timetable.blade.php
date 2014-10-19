<div class="text-center">
    <h3>{{ trans('as.embed.layout_2.choose') }}</h3>
    <div class="btn-group">
        @foreach ($nav as $item)

        <a href="#" class="btn btn-default btn-as-timetable" data-date="{{ $item->start->toDateString() }}">
        <div class="week-of-year">{{ trans('common.short.week') }} {{ $item->start->weekOfYear }}</div> {{ $item->start->format('d') }}. {{ trans('common.short.'.strtolower($item->start->format('M'))) }} &ndash; {{ $item->end->format('d') }}</a>
        @endforeach
    </div>

    <input type="hidden" name="start-date" value="{{ $date->toDateString() }}">

    <div class="row">
        <table class="table-timetable">
            <thead>
                <tr>
                @foreach ($dates as $date)
                    <th><h5 class="text-muted"><div class="day-in-week">{{ Util::td($date->format('D')) }}</div>{{ $date->format('d-m-Y') }}</h5></th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                @foreach ($timetable as $item)
                    <td class="{{ empty($item->time) ? 'empty' : '' }}">
                        @if (!empty($item->time))
                            @foreach ($item->time as $time => $employee)
                            <p><a href="#" data-date="{{ $item->date->toDateString() }}" data-employee-id="{{ $employee->id }}" class="as-time">{{ $time }}</a></p>
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
