<div class="panel panel-default">
    <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.selected') }}</strong></div>
    <div class="panel-body">
        @include ('modules.as.embed.layout-2.cart', ['cart' => $cart, 'hash' => $hash])
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading to-upper"><strong>{{ trans('as.embed.layout_2.form') }}</strong></div>
    <div class="panel-body" id="as-frm-confirm" style="position: relative;">
        @include ('modules.as.embed.layout-2.form')
    </div>
</div>

