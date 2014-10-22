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
    {{ HTML::script(asset('assets/js/home.js')) }}
@stop

@section('main-classes') container-fluid home @stop

@section('content')
<div class="container text-center">

    <!-- Next available time slot -->
    <div class="row" id="next-available-slots">
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
            <?php
                $slots = $business->user->getASNextTimeSlots($now, $now->hour);
                $count = 0;
            ?>
            <div class="available-slot col-sm-3 col-xs-6">
                <a href="{{ $business->business_url }}">
                    <img src="{{ Util::thumbnail($business->image, 270, 135, true, true) }}" alt="" class="img-responsive" />
                </a>
                <div class="info text-left">
                    <a href="{{ $business->business_url }}"><h4>{{ $business->name }}</h4></a>
                    <p>{{ $business->full_address }}</p>
                    @foreach ($slots as $slot)
                        <?php if($count === 3) break;?>
                        <a href="{{ route('business.index', [
                            'id'          => $business->user->id,
                            'slug'        => $business->slug,
                            'service_id'  => $slot['service'],
                            'employee_id' => $slot['employee'],
                            'date'        => $slot['date'],
                            'hour'        => $slot['hour'],
                            'minute'      => $slot['minute']
                        ])}}" class="btn btn-sm btn-default">{{ $slot['time'] }}</a>
                        <?php $count++;?>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>


    <!-- Flash deals -->
    @include ('modules.fd.front', ['deals' => $deals])
</div>
@stop
