<div class="offer">
    <p class="image">
        <img class="img-responsive" src="{{ $deal->user->business->image }}" alt="">
        <span class="badge-wrapper">
            <em class="badge">&ndash;{{ $deal->discount_percent }}%</em>
        </span>
    </p>
    <p>
        <span class="normal-price">{{ $deal->normal_price }} EUR</span>
        <span class="offered-price"><em>{{ $deal->discounted_price }}</em> EUR</span>
    </p>
    <h4 class="title"><a href="{{ $deal->user->business->business_url }}" title="">{{{ $deal->user->business->name }}}</a></h4>
    <p class="desc">{{{ $deal->service->name }}} <br> {{{ $deal->service->desc }}}</p>
</div>
