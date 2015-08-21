<div id="select-action" class="as-modal-form as-calendar-action">
<h2>{{ trans('as.index.calendar') }}</h2>
<div class="btn-group-vertical" style="width: 100%" role="group" aria-label="...">
    <button type="button" data-action="freetime" class="btn btn-default btn-continute-action">{{ trans('as.employees.add_free_time') }}</button>
    <button type="button" data-action="book" class="btn btn-default btn-continute-action">{{ trans('as.bookings.add') }}</button>
    <button type="button" id="row_paste_booking" data-action="paste_booking" class="btn btn-default btn-continute-action" style="@if(empty($cutId)) display: none @endif">{{ trans('as.bookings.confirm_reschedule') }}</button>
    <button type="button" id="row_discard_cut_booking" data-action="discard_cut_booking" class="btn btn-default btn-continute-action" style="@if(empty($cutId)) display: none @endif">{{ trans('as.bookings.cancel_reschedule') }}</button>
</div>
</div>
<div id="select-modify-action" class="as-modal-form as-calendar-action">
<h2>{{ trans('as.index.calendar') }}</h2>
<table class="table table-condensed">
    <tbody>
        {{--<tr>
            <td><input type="radio" id="change_total" value="change_total" name="modify_type"></td>
            <td><label for="change_total">{{ trans('as.bookings.modify_duration') }}</label></td>
        </tr>--}}
        <tr>
            <td><input type="radio" id="change_status" value="change_status" name="modify_type"></td>
            <td><label for="change_status">{{ trans('as.bookings.change_status') }}</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="add_extra_service" value="add_extra_service" name="modify_type"></td>
            <td><label for="add_extra_service">{{ trans('as.services.extras.add') }}</label></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="as-submit-row" colspan="2">
            <a href="#" id="btn-continue-modify" class="btn btn-primary">{{ trans('common.continue') }}</a>
            <a onclick="$.fancybox.close();" id="btn-cancel-action" class="btn btn-danger">{{ trans('common.cancel') }}</a>
        </td>
    </tr>
    </tfoot>
</table>
</div>
<input type="hidden" name="get_booking_form_url" id="get_booking_form_url" value="{{ route('as.bookings.form') }}">
<input type="hidden" name="get_freetime_form_url" id="get_freetime_form_url" value="{{ route('as.employees.freetime.form') }}">
<input type="hidden" name="employee_id" id="employee_id" value="">
<input type="hidden" name="booking_id" id="booking_id" value="">
<input type="hidden" name="date" id="date" value="">
<input type="hidden" name="start_time" id="start_time" value="">
<input type="hidden" id="get_services_url" value=" {{ route('as.bookings.employee.services') }}">
<input type="hidden" id="get_service_times_url" value=" {{ route('as.bookings.service.times') }}">
<input type="hidden" id="add_service_url" value="{{ route('as.bookings.service.add') }}">
<input type="hidden" id="add_booking_url" value="{{ route('as.bookings.add') }}">
<input type="hidden" id="add_freetime_url" value=" {{ route('as.employees.freetime.add') }}">
<input type="hidden" id="edit_freetime_url" value=" {{ route('as.employees.freetime.edit') }}">
<input type="hidden" id="delete_freetime_url" value="{{ route('as.employees.freetime.delete') }}"/>
<input type="hidden" id="modify_booking_form_url" value="{{ route('as.bookings.modify-form') }}"/>
<input type="hidden" id="get_paste_booking_url" value="{{ route('as.bookings.paste') }}"/>
<input type="hidden" id="get_discard_cut_booking_url" value="{{ route('as.bookings.discard-cut') }}"/>
<input type="hidden" id="update_workshift_url" value="{{ route('as.employees.employeeCustomTime.updateWorkshift') }}"/>
