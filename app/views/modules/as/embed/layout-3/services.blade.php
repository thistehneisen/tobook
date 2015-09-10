@foreach ($categories as $category)
    <div id="as-category-{{ $category->id }}" class="as-category">
        <label class="as-service-category btn btn-default"><input type="radio" class="hidden" name="category_id" value="{{ $category->id }}"><span>{{ $category->name }}</span></label>
    </div>
    <div id="as-category-{{ $category->id }}-services" class="as-service">
        <p class="as-back"><i class="glyphicon glyphicon-chevron-left"></i> {{ $category->name }}</p>
    @foreach ($category->services as $service)
        <div class="row">
            <div class="col-xs-offset-1 col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-xs-11 col-sm-11 col-md-11 col-lg-11">
                <p class="as-service-name" id="btn-service-{{ $service->id }}"><small><i class="glyphicon glyphicon-chevron-right"></i></small> {{ $service->name }}</p>
                <div class="as-service-time" id="service-times-{{ $service->id }}">
                    <p>
                        <?php $col = ((bool)$user->asOptions['hide_prices'] === false) ? 5 : 12;?>
                        <label class="col-lg-{{$col}} col-sm-6 col-md-6 btn btn-default">
                            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="{{ $service->description }}"></i>
                            <input type="radio" class="hidden" name="service_id" data-service-time-id="default" value="{{ $service->id }}" data-service="{{ $service->name }}" data-hash="{{ $user->hash }}">
                            @if ((bool)$user->asOptions['hide_prices'] === false)
                                {{ $service->price }}{{ Settings::get('currency') }}
                            @else
                                &nbsp;<i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes')}}
                            @endif
                            {{ trans('common.select') }}
                        </label>
                        @if ((bool)$user->asOptions['hide_prices'] === false)
                        <span><i class="glyphicon glyphicon-time"></i> {{ $service->during }} {{ trans('common.minutes')}}</span>
                        @endif
                    </p>

                @foreach ($service->serviceTimes as $item)
                    <p>
                        <label class="col-lg-{{$col}} col-sm-6 col-md-6 btn btn-default">
                            <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="top" title="{{ $item->description }}"></i>
                            <input type="radio" class="hidden" name="service_id" data-service-time-id="{{ $item->id }}" value="{{ $service->id }}" data-service="{{ $service->name }}">
                            @if ((bool)$user->asOptions['hide_prices'] === false)
                                {{ $item->price }}{{ Settings::get('currency') }}
                            @else
                                &nbsp;<i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes')}}
                            @endif
                            {{ trans('common.select') }}
                        </label>
                        @if ((bool)$user->asOptions['hide_prices'] === false)
                        <span><i class="glyphicon glyphicon-time"></i> {{ $item->during }} {{ trans('common.minutes')}}</span>
                        @endif
                    </p>
                @endforeach
                </div>
            </div>
        </div>

    @endforeach
    </div>
@endforeach
