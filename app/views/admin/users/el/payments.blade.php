@if (!empty($user->business->total_commission))
<code>
    <span class="js-show-tooltip" data-toggle="tooltip" data-placement="top" title="{{ trans('admin.paid') }}">{{ $user->business->paid_commission }}&euro;</span> /
    <span class="js-show-tooltip" data-toggle="tooltip" data-placement="top" title="{{ trans('admin.total') }}">{{ $user->business->total_commission }}&euro;</span>
</code>

<a class="js-commission-action" href="{{ route('admin.users.commissions.show', ['action' => 'add', 'id' => $user->id]) }}"><i class="fa fa-plus-circle"></i></a>
<a class="js-commission-action" href="{{ route('admin.users.commissions.show', ['action' => 'subtract', 'id' => $user->id]) }}"><i class="fa fa-minus-circle"></i></a>
<a class="js-commission-history" href="{{ route('admin.users.commissions', ['id' => $user->id]) }}"><i class="fa fa-history"></i></a>
@endif
