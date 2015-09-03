<div class="list-group">
@foreach ($categories as $category)
    @if (!$category->services->isEmpty())
    <div class="list-group-item">
        <h4 class="list-group-item-heading" id="btn-category-{{ $category->id }}">{{{ $category->name }}}</h4>
        <div class="list-group-item-text">
            <p class="text-muted">{{{ $category->description }}}</p>

            <div class="services" id="category-services-{{ $category->id }}">
            @foreach ($category->services as $service)
                <div class="single">
                    <a data-toggle="collapse" data-parent="#category-services-{{ $category->id }}" href="#service-{{ $category->id.'-'.$service->id }}">
                        <h5 class="heading inline" id="btn-service-{{ $service->id }}">{{{ $service->name }}}</h5>
                    </a>
                    <div id="service-{{ $category->id.'-'.$service->id }}" class="collapse">
                        <div class="row">
                            <div class="col-sm-3" id="btn-service-{{ $service->id }}-time-default">
                                @if ((bool)$user->asOptions['hide_prices'] === false)
                                <a data-service-id="{{ $service->id }}" data-service-time="default" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-tag"></i> {{{ $service->price }}}{{ Settings::get('currency') }}</a>
                                @endif
                                <a data-service-id="{{ $service->id }}" data-service-time="default" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-time"></i> {{{ $service->during }}}  {{ trans('common.minutes')}}</a>
                            </div>
                            <div class="text-muted col-sm-9">{{{ nl2br($service->description) }}}</div>
                        </div>

                        @foreach ($service->serviceTimes as $serviceTime)
                        <div class="row">
                            <div class="col-sm-3" id="btn-service-{{ $service->id }}-time-{{ $serviceTime->id }}">
                                @if ((bool)$user->asOptions['hide_prices'] === false)
                                <a data-service-id="{{ $service->id }}" data-service-time="{{ $serviceTime->id }}" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-tag"></i> {{ $serviceTime->price }}{{ Settings::get('currency') }}</a>
                                @endif
                                <a data-service-id="{{ $service->id }}" data-service-time="{{ $serviceTime->id }}" class="btn btn-default btn-select-service-time"><i class="glyphicon glyphicon-time"></i> {{ $serviceTime->during }} {{ trans('common.minutes')}}</a>
                            </div>
                            <div class="text-muted col-sm-9">{{{ $serviceTime->description }}}</div>
                        </div>
                        @endforeach

                        @if($service->extraServices()->where('is_hidden', '=', false)->count())
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
    @endif
@endforeach
</div>
