<div class="row">
    <div class="col-sm-6">
        <h3><a title="{{{ $business->name }}}" href="{{ route('business.index', ['id' => $user->id, 'slug' => $business->slug]) }}" target="_blank">{{{ $business->name }}}</a></h3>
        <p>{{{ $business->full_address }}}</p>
        <p class="hidden-xs"><img src="{{ Util::thumbnail($business->image, 410, 205) }}" alt="" class="img-responsive img-rounded"></p>

        <!-- About -->
        @if (!empty($business->description))
        <h4>{{ trans('home.search.about') }} {{{ $business->name }}}</h4>
        <div>{{ $business->description_html }}</div>
        @endif
    </div>

    <div class="col-sm-6">
        @if ($business->isUsingAs)
        <div class="box">
            <input type="hidden" id="business_id" value="{{ $business->id }}">
            <input type="hidden" id="business_hash" value="{{ $business->user->hash }}">
            @include('modules.as.embed.layout-3.main', ['inhouse' => false, 'hash' => $business->user->hash, 'allInput' => ['l' => 3, 'hash' => $business->user->hash]])
        </div>
        @endif

        <div class="box hidden-xs">
            <h4>{{ trans('home.search.locations_hours') }}</h4>
            <div class="text-center" style="min-height: 150px;" id="js-map-{{ $business->user->id }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
                <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
            </div>

            <h5>{{ trans('home.search.business_hours') }}</h5>
            <table class="table">
                <tbody>
                @foreach ($business->working_hours_array as $day => $value)
                    <tr>
                        <td>{{ trans('common.'.$day) }}</td>
                        <td>{{ with(new Carbon\Carbon($value['start']))->format('H:i') }} &ndash; {{ with(new Carbon\Carbon($value['end']))->format('H:i') }}</td>
                        <td>
                            @if (!empty($value['extra'])) {{{ $value['extra'] }}}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
