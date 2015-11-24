<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">3. {{ trans('as.bookings.extra_service') }}</a>
        </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <table id="extra_services" class="table table-bordered">
                     <thead>
                            <tr>
                                <th>{{ trans('as.services.extras.name') }}</th>
                                <th>{{ trans('as.services.extras.length') }}</th>
                                <th>{{ trans('as.services.extras.price') }}</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookingExtraServices as $bookingExtraService)
                            <tr id="extra-service-{{ $bookingExtraService->extraService->id }}">
                                <td>
                                    {{ $bookingExtraService->extraService->name }} {{ $bookingExtraService->extraService->description }}
                                </td>
                                <td>{{ $bookingExtraService->extraService->length }}</td>
                                <td class="align_right"> {{ $bookingExtraService->extraService->price }}</td>
                                <td>
                                   <a href="#" class="btn btn-default btn-remove-extra-service" data-remove-url="{{ route('as.bookings.remove-extra-service') }}" data-extra-service-id="{{ $bookingExtraService->extraService->id }}" data-booking-id="{{ $booking->id }}"><i class="glyphicon glyphicon-remove"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>  