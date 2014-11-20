<ul>
    @foreach ($consumers as $consumer)
    <li><a href="{{ route('consumer-hub.upsert', $consumer->id)  }}" target="_blank">{{ $consumer->name }}</a></li>
    @endforeach
</ul>
