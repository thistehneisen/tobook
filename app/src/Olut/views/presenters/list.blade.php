@if (empty($items))
    {{ trans('olut::olut.empty') }}
@else
<ul>
    @foreach ($items as $item)
    <li>{{ $item->name }}</li>
    @endforeach
</ul>
@endif
