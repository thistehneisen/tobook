@extends ('layouts.default')

@section('title')
    @parent :: {{ trans('common.home') }}
@stop

@section('styles')
    {{ HTML::style('assets/css/home.css') }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script(asset('packages/jquery.countdown/jquery.plugin.min.js')) }}
    {{ HTML::script(asset('packages/jquery.countdown/jquery.countdown.min.js')) }}
    <script>
$(function() {
    var applyCountdown = function(elems) {
        elems.each(function() {
            var $this = $(this);

            $this.countdown({
                until: new Date($this.data('date')),
                compact: true,
                layout: '{hnn}{sep}{mnn}{sep}{snn}',
            });
        });
    };

    // Init
    applyCountdown($('a.countdown'));

    $('#business_category').change( function (e) {
         window.location.href = $(this).val();
    });
});
    </script>
@stop

@section('main-classes') container-fluid home @stop

@section('content')
<div class="container text-center">

    <!-- Next available time slot -->
    <div class="row">
        <h3 class="comfortaa">{{ trans('Next available time slot') }}</h3>
        <div class="form-group col-md-4 col-md-offset-4">
        <select class="form-control input-sm" name="business_category" id="business_category">
            @foreach ($categories as $key => $value)
            <option @if($categoryId==$key) selected="selected" @endif value="{{ route('home', ['category_id' => $key ]) }}">{{ $value }}</option>
            @endforeach
        </select>
        </div>
        <div class="clearfix"></div>
        @foreach ($businesses as $business)
            <div class="available-slot col-md-3 col-sm-3">
                <img src="{{ asset('assets/img/slides/1.jpg') }}" alt="" class="img-responsive" />
                <div class="info text-left">
                    <h4>{{ $business->name }}</h4>
                    <p>{{ $business->address }}</p>
                    @foreach ($business->getASNextTimeSlots($now->toDateString(), $now->hour) as $slot)
                        <a href="#" class="btn btn-sm btn-default">{{ $slot['time'] }}</a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>


    <!-- Flash deals -->
    @include ('modules.fd.front', ['deals' => $deals])
</div>
@stop
