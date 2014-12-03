{{ $business->name }}
@if (!empty($business->note))
<a tabindex="0" class="business-note" href="javascript:void(0);" data-toggle="popover" data-trigger="focus" title="{{ trans('user.business.note') }}" data-content="{{{ $business->note }}}">
    <i class="fa fa-file-text"></i>
</a>
@endif
