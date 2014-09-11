<div id="select-action" class="as-modal-form as-calendar-action">
<h2>Kalenteri</h2>
<table class="table table-condensed">
    <tbody>
        <tr>
            <td><input type="radio" id="freetime" value="freetime" name="action_type"></td>
            <td><label for="freetime">Lisää vapaa</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="book" value="book" name="action_type"></td>
            <td><label for="book">Tee varaus</label></td>
        </tr>
    </tbody>
    <tfoot>
    <tr>
        <td class="as-submit-row" colspan="2">
            <a href="#" id="btn-continute-action" class="btn btn-primary">{{ trans('common.continue') }}</a>
            <a onclick="$.fancybox.close();" id="btn-cancel-action" class="btn btn-danger">{{ trans('common.cancel') }}</a>
        </td>
    </tr>
    </tfoot>
</table>
</div>
<input type="hidden" name="get_booking_form_url" id="get_booking_form_url" value="{{ route('as.bookings.form') }}">
<input type="hidden" name="get_freetime_form_url" id="get_freetime_form_url" value="{{ route('as.employees.freetime.form') }}">
<input type="hidden" name="employee_id" id="employee_id" value="">
<input type="hidden" name="date" id="date" value="">
<input type="hidden" name="start_time" id="start_time" value="">
<input type="hidden" id="get_services_url" value=" {{ route('as.bookings.employee.services') }}">
<input type="hidden" id="get_service_times_url" value=" {{ route('as.bookings.service.times') }}">
<input type="hidden" id="add_service_url" value=" {{ route('as.bookings.service.add') }}">
<input type="hidden" id="add_booking_url" value=" {{ route('as.bookings.add') }}">
<input type="hidden" id="add_freetime_url" value=" {{ route('as.employees.freetime.add') }}">
<input type="hidden" id="delete_freetime_url" value="{{ route('as.employees.freetime.delete') }}"/>
