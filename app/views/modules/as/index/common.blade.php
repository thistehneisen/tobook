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
<div id="select-modify-action" class="as-modal-form as-calendar-action">
<h2>Kalenteri</h2>
<table class="table table-condensed">
    <tbody>
        <tr>
            <td><input type="radio" id="change_total" value="change_total" name="modify_type"></td>
            <td><label for="change_total">Muokkaa kestoa</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="change_status" value="change_status" name="modify_type"></td>
            <td><label for="change_status">Muokkaa tilaa</label></td>
        </tr>
        <tr>
            <td><input type="radio" id="add_extra_service" value="add_extra_service" name="modify_type"></td>
            <td><label for="add_extra_service">Lisää lisäpalvelu</label></td>
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
<input type="hidden" id="add_service_url" value=" {{ route('as.bookings.service.add') }}">
<input type="hidden" id="add_booking_url" value=" {{ route('as.bookings.add') }}">
<input type="hidden" id="add_freetime_url" value=" {{ route('as.employees.freetime.add') }}">
<input type="hidden" id="delete_freetime_url" value="{{ route('as.employees.freetime.delete') }}"/>
<input type="hidden" id="add_extra_service_url" value="{{ route('as.bookings.extra-service-form') }}"/>
<input type="hidden" id="change_status_form_url" value="{{ route('as.bookings.change-status-form') }}"/>
