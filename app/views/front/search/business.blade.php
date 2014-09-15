<h3>{{{ $business->full_name }}}</h3>
<p>{{{ $business->full_address }}}</p>

<div class="row">
    <div class="col-sm-8">
        <p><img src="//placehold.it/500x300" alt="" class="img-responsive img-rounded"></p>

    @if (!empty($business->description))
        <h4>{{ trans('home.search.about') }} {{ $business->business_name }}</h4>
        <p>{{{ $business->description }}}</p>
    @endif

        <table class="table table-stripped table-hovered">
            <thead>
                <tr>
                    <th>{{ trans('fd.services.name') }}</th>
                    <th>{{ trans('fd.coupons.valid_date') }}</th>
                    <th>{{ trans('fd.services.price') }}</th>
                    <th></th>
                    <th>{{ trans('home.search.buy') }}</th>
                </tr>
            </thead>
            <tbody>
            @if ($coupons->isEmpty() === true)
                <tr>
                    <td colspan="5">{{ trans('home.search.no_results') }}</td>
                </tr>
            @endif
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
    </div>

    <div class="col-sm-4">
        <div class="box text-center">
            <a href="#" class="btn btn-success btn-lg">Varaa</a>
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
