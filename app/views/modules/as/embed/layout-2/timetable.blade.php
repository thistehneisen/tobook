<div class="text-center">
    <h3>Valitse päivämäärä</h3>
    <div class="btn-group">
        @foreach ($nav as $item)
        <a href="#" class="btn btn-default">{{ $item['start']->format('d') }}. {{ trans('common.short.'.strtolower($item['start']->format('M'))) }} &ndash; {{ $item['end']->format('d') }}</a>
        @endforeach
    </div>

    <div class="row">
        <table class="table-timetable">
            <thead>
                <tr>
                @foreach ($dates as $date)
                    <th><h5 class="text-muted">{{ $date->format('d-m-Y') }}</h5></th>
                @endforeach
                </tr>
            </thead>
            <tbody>
                <tr>
                @foreach ($timetable as $item)
                    <td class="{{ empty($item) ? 'empty' : '' }}">
                        @if (!empty($item))
                            @foreach ($item as $time => $employee)
                            <p><a data-employee-id="{{ $employee->id }}" class="as-time" href="">{{ $time }}</a></p>
                            @endforeach
                        @else
                            <p>Ei saatavilla</p>
                        @endif
                    </td>
                @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
