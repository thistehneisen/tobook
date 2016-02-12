<div id="book-form" class="@if(isset($upsert)) as-edit-booking @else as-calendar-book @endif">
@if(empty($booking))
<h2>{{ trans('as.bookings.add') }} <span id="loading" style="display:none"><img src="{{ asset_path('core/img/busy.gif') }}"/></span></h2>
@else
<h2>{{ trans('as.bookings.edit') }} <span id="loading" style="display:none"><img src="{{ asset_path('core/img/busy.gif') }}"/></span></h2>
@endif
<form id="booking_form" method="POST" action="{{ route('as.bookings.add') }}">
<div class="bs-example">
    <div class="panel-group" id="accordion">
        <!--- Start first tab -->
        @include ('modules.as.bookings.el.first')
        <!--- End first tab -->

        <!--- Start first tab -->
        @include ('modules.as.bookings.el.second')
        <!--- End first tab -->

        <!--- Start Confirmation and reminder settings tab -->
        @include ('modules.as.bookings.el.third')
        <!--- End confirmation and reminder settings tab -->

        @if(!empty($bookingExtraServices) && !$bookingExtraServices->isEmpty())
        @include ('modules.as.bookings.el.fourth')
        @endif
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group row">
                <div class="col-sm-12">
                    @if(empty($booking))
                    <input type="submit" id="btn-save-booking" class="btn btn-lg btn-success pull-right" value="{{ trans('common.save') }}" />
                    @else
                    <input type="submit" id="btn-save-booking" class="btn btn-lg btn-primary pull-right" value="{{ trans('common.edit') }}" />
                    @endif
                </div>
            </div>
        </div>
    </div>

@include ('modules.as.bookings.el.hidden')

<!-- History Modal Dialog -->
<div class="modal fade" id="js-historyModal" role="dialog" aria-labelledby="js-historyModalLabel" aria-hidden="true" style="z-index: 99999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('common.history') }}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="js-consumer-info-modal" role="dialog" aria-labelledby="js-consumer-info-modal-label" aria-hidden="true" style="z-index: 99999">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{ trans('common.info') }}</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('OK') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function(){
        $('.selectpicker').selectpicker();
    })();
</script>
@if(!isset($upsert))
    @include ('modules.as.bookings.formScript')
@endif
