<div class="row">
    {{-- left sidebar --}}
    <div class="col-sm-8 col-md-8">
        <h1>{{{ $business->name }}}</h1>
        <address>{{{ $business->full_address }}}</address>
        {{-- Slider  --}}
        @if ($business->images->isEmpty() === false)
            <!-- Slider main container -->
            <div class="slideshow swiper-container" id="js-swiper-{{ $business->user_id }}">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                @foreach ($business->images as $image)
                    <div class="swiper-slide text-center">
                        <img style="max-width: 100%;" src="{{ $image->getPublicUrl() }}" alt="">
                    </div>
                @endforeach
                </div>
            </div>
        @else
            <div class="slideshow">
                <p class="text-center"><img src="{{ $business->image }}" alt="{{{ $business->name }}}"></p>
            </div>
        @endif
        {{-- End slider  --}}
        <div class="description" id="business-description">
            {{ $business->description_html }}
            <a href="#business-description" style="display:none" class="readmore"><i class="fa fa-caret-down"></i></a>
        </div>
    @if (!$business->is_booking_disabled)
        <h3 class="sub-heading">@lang('as.embed.cp.heading')</h3>
        <div id="js-cp-booking-form">@lang('as.embed.loading')</div>
    @endif
    </div>

    {{-- right sidebar --}}
    <div class="col-sm-4 col-md-4">
        <!--End slider-->
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

                {{-- <p><strong>{{ trans('home.business.phone') }}</strong></p>
                <p>{{{ $business->phone }}}</p> --}}

                <p><strong>{{ trans('home.business.email') }}</strong></p>
                <p>{{{ $business->user->email }}}</p>

                <p><strong>{{ trans('home.business.address') }}</strong></p>
                <p>{{{ $business->full_address }}}</p>

                @if (!empty($business->paymentMethods))
                <p><strong>{{ trans('home.business.payment_methods') }}</strong></p>
                <p>
                    <ul>
                    @foreach($business->paymentMethods as $method)
                        <li>{{  trans('user.business.' . $method) }}</li>
                    @endforeach
                    </ul>
                </p>
                @endif
            </div>
        </div>
    </div>
</div>
@if (!empty($ajax) && $ajax)
@include ('front.el.layout-cp.truncateScript')
@endif