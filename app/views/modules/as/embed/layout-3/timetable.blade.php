<ul class="list-inline as-calendar">
    <li><a href="#"><i class="glyphicon glyphicon-chevron-left"></i></a></li>
<?php $i = 0; ?>
@while ($i < 5)
    <li @if ($i === 2) class="active" @endif><a href="#">{{ trans('common.short.'.strtolower($date->format('D'))) }} <br> {{ $date->format('d.m') }}</a></li>
    <?php $date = $date->addDay(); $i++; ?>
@endwhile
    <li><a href="#"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
</ul>

<div class="text-center">
    <?php $i = 1; ?>
@foreach ($timetable as $time)
    <button type="button" class="btn btn-default btn-as-time">{{ $time }}</button>
    @if ($i++ % 4 === 0)
        <br>
    @endif
@endforeach
</div>
