<ul class="list-unstyle">
@foreach ($dates as $date)
    <li><code>{{ $date->expire->format(trans('common.format.date_time')) }}</code></li>
@endforeach
</ul>
