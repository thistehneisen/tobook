<div class="row">
    {{-- left sidebar --}}
    <div class="col-sm-8 col-md-8">
        <h1>{{{ $business->name }}}</h1>
        <address>{{{ $business->full_address }}}</address>

        <div class="slideshow">
            <p><img src="{{ $business->image }}" alt=""></p>
        </div>

    @if (!empty($business->description))
        <h3 class="sub-heading">{{ home.business.about }}</h3>
        <div class="description">
            {{ $business->description_html }}
        </div>
    @endif
    </div>

    {{-- right sidebar --}}
    <div class="col-sm-4 col-md-4">
    @if ($business->is_booking_disabled)
        @include ('front.contact.form', ['business' => $business])
    @else
        @if ($business->isUsingAS)
        <div class="box">
            {{-- `$inhouse = true` means that we'll show login/register secion in step 4 --}}
            <input type="hidden" id="business_id" value="{{ $business->id }}">
            <input type="hidden" id="business_hash" value="{{ $business->user->hash }}">
            @include('modules.as.embed.layout-3.main', ['inhouse' => Settings::get('enable_cart'), 'hash' => $business->user->hash])
        </div>
        @endif
    @endif

        <h3 class="sub-heading">{{ trans('home.business.map') }}</h3>
        <div data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}" id="js-map-{{ $business->user_id }}" class="small-map"></div>

        <div class="row">
            <div class="col-sm-6 col-md-6">
                <h3 class="sub-heading">{{ trans('home.business.openning_hours') }}</h3>
                <table class="table table-working-hours">
                    <tbody>
                    @foreach ($business->working_hours_array as $day => $value)
                        <tr>
                            <td>{{ trans('common.short.'.$day) }}</td>
                        @if (isset($value['hidden']) && (bool) $value['hidden'] === true)
                            <td colspan="2">
                                @if (!empty($value['extra'])) {{{ $value['extra'] }}}
                                @endif
                            </td>
                        @else
                            <td><p>{{ $value['formatted'] or '' }}</p>
                                @if (!empty($value['extra'])) <span class="text-info">{{{ $value['extra'] }}}</span>
                                @endif
                            </td>
                        @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 col-md-6">
                <h3 class="sub-heading">{{ trans('home.business.contact.index') }}</h3>

                <p><strong>{{ trans('home.business.phone') }}</strong></p>
                <p>{{{ $business->phone }}}</p>

                <p><strong>{{ trans('home.business.email') }}</strong></p>
                <p>{{{ $business->user->email }}}</p>
            </div>
        </div>
    </div>
</div>
