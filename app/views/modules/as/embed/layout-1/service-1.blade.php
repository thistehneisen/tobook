<div class="list-group">
@foreach ($categories as $category)
    <div class="list-group-item">
        <h4 class="list-group-item-heading">{{ $category->name }}</h4>
        <div class="list-group-item-text">
            <p class="text-muted">{{ $category->description }}</p>

            <div class="services" id="category-services-{{ $category->id }}">
            @if ($category->services->isEmpty())
                <p class="text-muted"><em>{{ trans('as.services.categories.no_services') }}</em></p>
            @endif
            @foreach ($category->services as $service)
                <div class="single">
                    <a data-toggle="collapse" data-parent="#category-services-{{ $category->id }}" href="#service-{{ $category->id.'-'.$service->id }}"><h5 class="heading">{{ $service->name }}</h5></a>
                    <div id="service-{{ $category->id.'-'.$service->id }}" class="collapse">
                        <p>
                            <a data-service-id="{{ $service->id }}" data-service-time="default" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-tag"></i> &euro;{{ number_format($service->price) }}</a>
                            <a data-service-id="{{ $service->id }}" data-service-time="default" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes')}}</a>
                            <span class="text-muted">{{ $service->description }}</a>
                        </p>
                        @foreach ($service->serviceTimes as $serviceTime)
                        <p>
                            <a data-service-id="{{ $service->id }}" data-service-time="{{ $serviceTime->id }}" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-tag"></i> &euro;{{ number_format($serviceTime->price) }}</a>
                            <a data-service-id="{{ $service->id }}" data-service-time="{{ $serviceTime->id }}" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-time"></i> {{ $serviceTime->length }} {{ trans('common.minutes')}}</a>
                            <span class="text-muted">{{ $serviceTime->description }}</span>
                        </p>
                        @endforeach
                        @if($service->extraServices()->count())
                       <a href="#" data-hash="{{ $hash }}" data-service-id="{{ $service->id }}" class="btn btn-success btn-add-extra-service">{{ trans('as.services.categories.availability') }}</a>
                        @else
                        <a id="btn-add-service-{{ $service->id }}" href="{{ route('as.embed.embed', ['hash' => $hash,'service_id' => $service->id,'service_time' => 'default' ,'date' => $date->toDateString() ])}}" class="btn btn-success btn-add-service">{{ trans('as.services.categories.availability') }}</a>
                        @endif
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
@endforeach
</div>
