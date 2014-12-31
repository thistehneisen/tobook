@if (!empty($user->business->total_commission))
<code>
    <span class="js-show-tooltip" data-toggle="tooltip" data-placement="top" title="{{ trans('admin.paid') }}">{{ $user->business->paid_commission }}&euro;</span> /
    <span class="js-show-tooltip" data-toggle="tooltip" data-placement="top" title="{{ trans('admin.total') }}">{{ $user->business->total_commission }}&euro;</span>
</code>

<a href=""><i class="fa fa-plus-circle"></i></a>
<a href=""><i class="fa fa-minus-circle"></i></a>
<a href=""><i class="fa fa-arrow-circle-down"></i></a>
@endif
