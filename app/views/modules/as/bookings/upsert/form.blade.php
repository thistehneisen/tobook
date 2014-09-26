@extends ('modules.as.layout')

@section ('styles')
    @parent
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2-bootstrap.min.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js') }}
    @if(App::getLocale() !== 'en')
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2_locale_'.App::getLocale().'.min.js') }}
    @endif
    @include ('modules.as.bookings.formScript')
@stop

@section ('content')

    @include ('modules.as.bookings.form')
<input type="hidden" id="get_services_url" value=" {{ route('as.bookings.employee.services') }}">
<input type="hidden" id="get_service_times_url" value=" {{ route('as.bookings.service.times') }}">
@stop
