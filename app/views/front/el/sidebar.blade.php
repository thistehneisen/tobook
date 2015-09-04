<div class="businesses">
@foreach ($businesses as $business)
    <div class="business js-business" data-title="{{{ $business->title }}}" data-id="{{ $business->user_id }}" data-hash="{{ $business->hash }}" data-url="{{ $business->business_url }}" data-lat="{{ $business->lat }}" data-lng="{{ $business->lng }}">
        {{-- <p><img src="{{ $business->image }}" alt="" class="img-responsive"></p> --}}
        <h4><a href="{{ $business->business_url }}" title="">{{{ $business->name }}}</a>
    @if ($business->isUsingAS && (bool) $business->is_booking_disabled === false)
        <small><span class="label label-success"><i class="fa fa-ticket"></i> {{ trans('home.business.online_booking') }}</span></small>
    @endif
        {{-- <small>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
            <i class="fa fa-star"></i>
        </small> --}}
        </h4>
        <address>{{{ $business->full_address }}}</address>
    </div>
@endforeach
</div>

@if (!empty($nextPageUrl))
<nav class="text-center show-more">
    <a id="js-show-more" href="{{ $nextPageUrl }}" class="btn btn-default btn-block">
    <span>{{ trans('home.show_more') }}</span>
    <i class="fa fa-2x fa-spinner fa-spin" style="display: none;"></i>
    </a>
</nav>
@endif
