<div class="col-sm-3">
    <h5>{{ trans('as.embed.layout_2.select_service_type') }}</h5>
    <div class="as-categories better">
@foreach ($categories as $category)
    <p><a class="as-category" data-category-id="{{ $category->id }}" href="#{{ $category->id }}"><small><i class="glyphicon glyphicon-chevron-right"></i></small> {{ $category->name }}</a></p>
@endforeach
    </div>
</div>

@foreach ($categories as $category)
<div class="as-services col-sm-3" id="as-category-{{ $category->id }}-services">
    <h5>{{ trans('as.embed.layout_2.services') }}</h5>
    <div class="better">
    @foreach ($category->services as $service)
        <div class="as-service-row">
            <a data-service-id="{{ $service->id }}" href="#" class="as-service"><small><i class="glyphicon glyphicon-chevron-right"></i></small> {{ $service->name }}</a>
            <div class="as-service-time">
                <div class="btn-group">
                    <button data-service-time-id="default" data-service-id="{{ $service->id }}" type="button" class="btn btn-default btn-sm btn-service-time"><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes') }}</button>
                </div>
            @foreach ($service->serviceTimes as $item)
                <div class="btn-group">
                    <button data-service-id="{{ $service->id }}" data-service-time-id="{{ $item->id }}" type="button" class="btn btn-default btn-sm btn-service-time"><i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes') }}</button> &nbsp; {{ $item->description }}
                </div>
            @endforeach
            </div>
        </div>
    @endforeach
    </div>
</div>
@endforeach

@foreach ($categories as $category)
    @foreach ($category->services as $service)
        @if ($service->extraServices->isEmpty() === false)
<div class="as-extra-services col-sm-3" id="as-service-{{ $service->id }}-extra-services">
    <h5>{{ trans('as.embed.layout_2.extra_services') }}</h5>
    <div class="better">
        <div class="as-extra-service-row">
            @foreach ($service->extraServices as $item)
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="extra_service_id[]" value="{{ $item->id }}"> {{ $item->name }} ({{ $item->length }} {{ trans('common.minutes') }})
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
        @endif
    @endforeach
@endforeach
