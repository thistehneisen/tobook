@foreach ($categories as $category)
    <div id="as-category-{{ $category->id }}" class="as-category">
        <label class="as-service-category"><input type="radio" name="category_id" value="{{ $category->id }}"> {{ $category->name }}</label>
    </div>
    <div id="as-category-{{ $category->id }}-services" class="as-service">
        <p class="as-back"><i class="glyphicon glyphicon-chevron-left"></i> {{ $category->name }}</p>
    @foreach ($category->services as $service)
        <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                {{ $service->name }}
            </div>
            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                <p>
                    <label class="col-md-4 col-lg-4">
                        <i class="glyphicon glyphicon-info-sign"></i>
                        <input type="radio" name="service_id" value="{{ $service->id }}" data-service="{{ $service->name }}">
                        {{ number_format($service->price) }}&euro;
                    </label>
                    <span><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes')}}</span>
                </p>

            @foreach ($service->serviceTimes as $item)
                <p>
                    <label class="col-md-4 col-lg-4">
                        <i class="glyphicon glyphicon-info-sign"></i>
                        <input type="radio" name="service_id" value="{{ $item->id }}" data-service="{{ $service->name }}">
                        {{ number_format($item->price) }}&euro;
                    </label>
                    <span><i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes')}}</span>
                </p>
            @endforeach

            </div>
        </div>

    @endforeach
    </div>
@endforeach
