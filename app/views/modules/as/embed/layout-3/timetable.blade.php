    <ul class="list-inline as-calendar">
        <li><a href="#" class="as-date" data-date="{{ $prev->toDateString() }}" id="btn-date-prev"><i class="glyphicon glyphicon-chevron-left"></i></a></li>
    <?php $i = 0; ?>
    @while ($i < 5)
        <li @if ($startDate->toDateString() === $date->toDateString()) class="active" @endif>
            <?php if($startDate <= $final):?>
            <a href="#" class="as-date" data-date="{{ $startDate->toDateString() }}" id="btn-date-{{ $startDate->format('Ymd') }}">{{ trans('common.short.'.strtolower($startDate->format('D'))) }} <br>
            {{ $startDate->format('d.m') }}
            </a>
            <?php else: ?>
                {{ trans('common.short.'.strtolower($startDate->format('D'))) }} <br> {{ $startDate->format('d.m') }}
            <?php endif;?>
        </li>
        <?php $startDate = $startDate->addDay(); $i++; ?>
    @endwhile
        <li><a href="#" class="as-date" data-date="{{ $next->toDateString() }}" id="btn-date-next"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
    </ul>

    <div class="as-timetable-content text-center" id="timetable" data-date="{{ $date->toDateString() }}">
    @if (empty($timetable))
        <p>{{ trans('as.embed.layout_3.empty') }}</p>
    @endif
        <?php $i = 1; ?>
    @foreach ($timetable as $time => $employee)
        <button data-employee-id="{{ $employee->id }}" data-time="{{ $time }}" type="button" class="btn btn-default btn-as-time" id="btn-slot-{{ substr(preg_replace('#[^0-9]#', '', $time), 0, 4) }}">{{ $time }}</button>
        @if ($i++ % 4 === 0)
            <br>
        @endif
    @endforeach
    </div>

    <div class="as-loading">
        <p class="text-center">
            <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
        </p>
    </div>
