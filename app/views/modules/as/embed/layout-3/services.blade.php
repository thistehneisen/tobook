@foreach ($categories as $category)
    <div id="as-category-{{ $category->id }}" class="as-category">
        <label class="as-service-category"><input type="radio" name="category_id" value="{{ $category->id }}"> {{ $category->name }}</label>
    </div>
    <div id="as-category-{{ $category->id }}-services" class="as-service">
        <p class="as-back"><i class="glyphicon glyphicon-chevron-left"></i> {{ $category->name }}</p>
    @foreach ($category->services as $service)
        <div class="row">
            <div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-11 col-sm-11 col-md-11 col-lg-11">
                <p class="as-service-name"><small><i class="glyphicon glyphicon-chevron-right"></i></small> {{ $service->name }}</p>
                <div class="as-service-time">
                    <p>
                        <label class="col-lg-4 col-sm-6 col-md-6">
                            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="{{ $service->description }}"></i>
                            <input type="radio" name="service_id" data-service-time-id="default" value="{{ $service->id }}" data-service="{{ $service->name }}">
                            {{ $service->price }}&euro;
                        </label>
                        <span><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes')}}</span>
                    </p>

                @foreach ($service->serviceTimes as $item)
                    <p>
                        <label class="col-lg-4 col-sm-6 col-md-6">
                            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="{{ $item->description }}"></i>
                            <input type="radio" name="service_id" data-service-time-id="{{ $item->id }}" value="{{ $service->id }}" data-service="{{ $service->name }}">
                            {{ $item->price }}&euro;
                        </label>
                        <span><i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes')}}</span>
                    </p>
                @endforeach
                </div>
            </div>
        </div>

    @endforeach
    </div>
@endforeach
