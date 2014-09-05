<div class="list-group">
@foreach ($categories as $category)
    <div class="list-group-item">
        <h4 class="list-group-item-heading">{{ $category->name }}</h4>
        <div class="list-group-item-text">
            <p class="text-muted">{{ $category->description }}</p>

            <div class="services" id="category-services-{{ $category->id }}">
            @if ($category->services->isEmpty())
                <p class="text-muted"><em>There are no services for this category</em></p>
            @endif
            @foreach ($category->services as $service)
                <div class="single">
                    <a data-toggle="collapse" data-parent="#category-services-{{ $category->id }}" href="#service-{{ $category->id.'-'.$service->id }}"><h5 class="heading">{{ $service->name }}</h5></a>
                    <div id="service-{{ $category->id.'-'.$service->id }}" class="collapse">
                        <p>
                            <button disabled class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> &euro;{{ number_format($service->price) }}</button>
                            <button disabled class="btn btn-default"><i class="glyphicon glyphicon-time"></i> {{ $service->length }} minutes</button>
                            <span class="text-muted">{{ $service->description }}</span>
                        </p>
                        @if($service->extraServices()->count())
                       <a href="#" data-hash="{{ $hash }}" data-service-id="{{ $service->id }}" class="btn btn-success btn-add-extra-service">Availability</a>
                        @else
                        <a href="{{ route('as.embed.embed', ['hash' => $hash,'serviceId' => $service->id, 'date' => $date->toDateString() ])}}" class="btn btn-success btn-add-service">Availability</a>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>
