@extends ('layouts.default')

@section('title')
    @parent :: {{{ $business->name }}}
@stop

@section('search')
    @include ('el.search.newdefault', ['businessCategories' => \App\Core\Models\BusinessCategory::getAll()])
@stop

@section('scripts')
    <script>
    // Dump inline data
    VARAA.Search = VARAA.Search || {};
    VARAA.Search.businesses = {{ $businessesJson }};
    VARAA.Search.lat = {{ $lat }};
    VARAA.Search.lng = {{ $lng }};
@if(!empty($categoryId) && !empty($serviceId))
    VARAA.Search.categoryId = {{ $categoryId }};
    VARAA.Search.serviceId = {{ $serviceId }};
    VARAA.Search.employeeId = {{ $employeeId }};
    VARAA.Search.time = {{ $time }};
@endif
    </script>

    {{ HTML::script('//maps.googleapis.com/maps/api/js?v=3.exp&language='.App::getLocale()) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.12/gmaps.min.js') }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js') }}
    @if (App::getLocale() !== 'en') {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/locales/bootstrap-datepicker.'.App::getLocale().'.min.js') }}
    @endif
    {{ HTML::script(asset_path('as/scripts/layout-3.js')) }}

    <script>
$(function() {
    new GMaps({
      div: '#map-canvas',
      lat: {{ $lat }},
      lng: {{ $lng }}
    });

    VARAA.initLayout3({
        isAutoSelectEmployee: false
    });
});
    </script>
    {{ HTML::script(asset_path('core/scripts/search.js')) }}
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css">
    <link rel="stylesheet" href="{{ asset_path('as/styles/layout-3.css') }}">
@stop

@section('main-classes') front @stop

@section('content')
<div class="container search-results">
    <a href="/new/search">
        <h4 class="heading">
            <i class="fa fa-chevron-left"></i>
            <span class="keyword">Barber shop</span>,
            <span class="location">Center</span>,
            any date,
            any time:
            <span class="results">38 results</span>
        </h4>
    </a>

    <div class="row">
        {{-- left sidebar --}}
        <div class="col-sm-8 col-md-8">
            <h1>{{{ $business->name }}}</h1>
            <address>{{{ $business->full_address }}}</address>

            <div class="slideshow">
                <p><img src="{{ asset_path('core/img/new/business.jpg') }}" alt=""></p>
            </div>

        @if (!empty($business->description))
            <h3 class="sub-heading">About</h3>
            <div class="description">
                {{{ $business->description_html }}}
            </div>
        @endif
        </div>

        {{-- right sidebar --}}
        <div class="col-sm-4 col-md-4">
            @if ($business->isUsingAs)
            <div class="box">
                {{-- `$inhouse = true` means that we'll show login/register secion in step 4 --}}
                <input type="hidden" id="business_id" value="{{ $business->id }}">
                <input type="hidden" id="business_hash" value="{{ $business->user->hash }}">
                @include('modules.as.embed.layout-3.main', ['inhouse' => Settings::get('enable_cart'), 'hash' => $business->user->hash])
            </div>
            @endif

            <h3 class="sub-heading">Map</h3>
            <div id="map-canvas" class="small-map"></div>

            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <h3 class="sub-heading">Openning hours</h3>
                    <table class="table table-working-hours">
                        <tbody>
                        @foreach ($business->working_hours_array as $day => $value)
                            <tr>
                                <td>{{ trans('common.short.'.$day) }}</td>
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
                <div class="col-sm-6 col-md-6">
                    <h3 class="sub-heading">Contact</h3>

                    <p><strong>Phone:</strong></p>
                    <p>{{{ $business->phone }}}</p>

                    <p><strong>E-mail</strong></p>
                    <p>{{{ $business->user->email }}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
