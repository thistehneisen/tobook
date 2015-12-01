@if(!empty($booking))
<input type="hidden" value="{{ $booking->id }}" name="booking_id">
<input type="hidden" value="{{ $booking->employee->id }}" name="employee_id" id="employee_id">
@endif
<input type="hidden" value="{{ route('as.bookings.add') }}" name="add_booking_url" id="add_booking_url">
<input type="hidden" id="add_service_url" value="{{ route('as.bookings.service.add') }}">
<input type="hidden" value="{{ route('as.bookings.service.delete') }}" name="delete_booking_service_url" id="delete_booking_service_url">
</form>
</div>
<input type="hidden" value="" id="consumer_data"/>