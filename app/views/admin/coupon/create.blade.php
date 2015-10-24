@extends ('layouts.admin')

@section('content')
    <h3>{{ trans('admin.coupon.title')}}</h3>
    @include ('admin.coupon.tabs')
@stop
