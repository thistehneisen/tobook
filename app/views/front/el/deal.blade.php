<div class="offer">
    <p class="image">
        <img class="img-responsive" src="{{ asset_path('core/img/new/offer.jpg') }}" alt="">
        <span class="badge-wrapper">
            <em class="badge">&ndash;{{ $deal->flashDeal->discount_percent }}%</em>
        </span>
    </p>
    <p>
        <span class="normal-price">{{ $deal->flashDeal->normal_price }} EUR</span>
        <span class="offered-price"><em>{{ $deal->flashDeal->discounted_price }}</em> EUR</span>
    </p>
    <h4 class="title"><a href="{{ $deal->business->business_url }}" title="">{{{ $deal->business->name }}}</a></h4>
    <p class="desc">{{{ $deal->flashDeal->service->name }}} <br> {{{ $deal->flashDeal->service->desc }}}</p>
</div>
