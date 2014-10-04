<div class="col-sm-3">
    <h5>{{ trans('as.embed.layout_2.select_service_type') }}</h5>
    <div class="as-categories well">
@foreach ($categories as $category)
    <p><a class="as-category" rel="as-category-{{ $category->id }}-services" href="#{{ $category->id }}">{{ $category->name }}</a></p>
@endforeach
    </div>
</div>

@foreach ($categories as $category)
<div class="as-services col-sm-3" id="as-category-{{ $category->id }}-services">
    <h5>{{ trans('as.embed.layout_2.services') }}</h5>
    <div class="well">
    @foreach ($category->services as $service)
        <p>{{ $service->name }}</p>

        <div class="as-service-time">
            <div class="btn-group">
                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> {{ $service->formatted_price }}</button>
                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes') }}</button>
            </div>
        @foreach ($service->serviceTimes as $item)
            <div class="btn-group">
                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-tag"></i> {{ $item->formatted_price }}</button>
                <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes') }}</button>
            </div>
        @endforeach
        </div>
    @endforeach

    </div>
</div>
@endforeach
