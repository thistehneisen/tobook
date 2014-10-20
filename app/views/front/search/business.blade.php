<div class="row">
    <div class="col-sm-6">
        <h3><a title="{{{ $business->name }}}" href="{{ route('business.index', ['id' => $user->id, 'slug' => $business->slug]) }}" target="_blank">{{{ $business->name }}}</a></h3>
        <p>{{{ $business->full_address }}}</p>
        <p><img src="{{ Util::thumbnail($business->image, 410, 205) }}" alt="" class="img-responsive img-rounded"></p>

        <!-- About -->
        @if (!empty($business->description))
        <h4>{{ trans('home.search.about') }} {{ $business->name }}</h4>
        <p>{{{ $business->description }}}</p>
        @endif

        <!-- Flash deals -->
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
                <td><a href="#" title="">{{ $item->flashDeal->service->name }}</a></td>
                <td><span class="text-danger countdown" data-date="{{ $item->expire->toISO8601String() }}"></span></td>
                <td>{{ $item->flashDeal->discounted_price }}&euro; ({{ $item->flashDeal->service->price }}&euro;)</td>
                <td>
                    <p class="text-danger"><strong>-{{ $item->flashDeal->discount_percent }}%</strong></p>
                </td>
                <td>
                    <a href="#" class="btn btn-success btn-sm">{{ trans('home.search.book') }}</a>
                </td>
            </tr>
        @endforeach
            </tbody>
        </table>
        @endif

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
                    <td><span class="text-danger countdown" data-date="{{ $item->valid_date->toISO8601String() }}"></span></td>
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

    <div class="col-sm-6">
        <div class="box">
            {{-- `$inhouse = true` means that we'll show login/register secion in step 4 --}}
            <input type="hidden" id="business_id" value="{{ $business->id }}">
            <input type="hidden" id="business_hash" value="{{ $business->hash }}">
            @include('modules.as.embed.layout-3.main', ['inhouse' => true, 'hash' => $business->user->hash])
        </div>

        <div class="box">
            <h4>{{ trans('home.search.locations_hours') }}</h4>
            <div class="text-center" style="min-height: 150px;" id="js-map-{{ $user->id }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <h5>{{ trans('home.search.business_hours') }}</h5>
            <table class="table">
                <tbody>
                @foreach ($user->as_options->get('working_time') as $day => $value)
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
