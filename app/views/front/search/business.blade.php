<h3>{{{ $business->business_name }}}</h3>
<p>{{{ $business->full_address }}}</p>

<div class="row">
    <div class="col-sm-8">
        <p><img src="{{ asset('assets/img/slides/3.jpg') }}" alt="" class="img-responsive img-rounded"></p>

        <!-- About -->
        @if (!empty($business->description))
        <h4>{{ trans('home.search.about') }} {{ $business->business_name }}</h4>
        <p>{{{ $business->description }}}</p>
        @endif

        <!-- Flash deals -->
        {{--
        @if (!$flashDeals->isEmpty())
        <hr>
        <table class="table table-stripped table-hovered">
            <thead>
                <tr>
                    <th>{{ trans('fd.services.name') }}</th>
                    <th>{{ trans('fd.coupons.valid_date') }}</th>
                    <th>{{ trans('common.price') }}</th>
                    <th>{{ trans('common.discount') }}</th>
                    <th>{{ trans('home.search.buy') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($flashDeals as $item)
                <tr>
                    <td><a href="#" title="">{{ $item->service->name }}</a></td>
                    <td>{{ $item->valid_date }}</td>
                    <td>{{ $item->discounted_price }}&euro; ({{ $item->service->price }}&euro;)</td>
                    <td>
                        <p class="text-danger"><strong>-{{ $item->discount_percent }}%</strong></p>
                    </td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">{{ trans('home.search.book') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
        --}}

        <!-- Coupons -->
        @if (!$coupons->isEmpty())
        <hr>
        <table class="table table-stripped table-hovered">
            <thead>
                <tr>
                    <th>{{ trans('fd.services.name') }}</th>
                    <th>{{ trans('fd.coupons.valid_date') }}</th>
                    <th>{{ trans('common.price') }}</th>
                    <th>{{ trans('common.discount') }}</th>
                    <th>{{ trans('home.search.buy') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($coupons as $item)
                <tr>
                    <td><a href="#" title="">{{ $item->service->name }}</a></td>
                    <td>{{ $item->valid_date }}</td>
                    <td>{{ $item->discounted_price }}&euro; ({{ $item->service->price }}&euro;)</td>
                    <td>
                        <p class="text-danger"><strong>-{{ $item->discount_percent }}%</strong></p>
                    </td>
                    <td>
                        <a href="#" class="btn btn-success btn-sm">{{ trans('home.search.book') }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="col-sm-4">
        <div class="box text-center">
            <a href="#" class="btn btn-success btn-lg">{{ trans('common.book') }}</a>
        </div>

        <div class="box">
            <h4>{{ trans('home.search.locations_hours') }}</h4>
            <div class="text-center" style="min-height: 150px;" id="js-map-{{ $business->id }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <h5>{{ trans('home.search.business_hours') }}</h5>
            <table class="table">
                <tbody>
                @foreach ($business->as_options->get('working_time') as $day => $value)
                    <tr>
                        <td>{{ trans('common.'.$day) }}</td>
                        <td>{{ with(new Carbon\Carbon($value['start']))->format('H:i') }} &ndash; {{ with(new Carbon\Carbon($value['end']))->format('H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
