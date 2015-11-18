@if((boolean)$user->asOptions['announcement_enable'])
<div class="container alert alert-info announcement hidden-print hidden-xs hidden-sm">
    <p><strong>{{ trans('as.index.heading') }}</strong></p>
    <p>{{ $user->asOptions['announcement_content'] }}</p>
</div>
@endif

<div class="container-fluid">
    @if(isset($allInput['src']) && $allInput['src'] === 'inhouse')
    <div class="row">
        <div class="col-sm-12">
            <h3 class="sub-heading">{{ trans('as.embed.layout_3.heading_line')}}</h3>
        </div>
    </div>
    @endif
    <div class="panel-group" id="varaa-as-bookings" data-inhouse="{{ isset($inhouse) ? (int) $inhouse : 0 }}">
        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-1" href="#as-step-1">
                <h4 class="panel-title">
                    1. <span>{{ trans('as.embed.layout_3.select_service') }}</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-1" data-parent="#varaa-as-bookings" class="panel-collapse collapse in">
                <div class="panel-body">
                    @include ('modules.as.embed.layout-3.services')
                </div>
            </div>
        </div>

        @if((bool)$user->asOptions['auto_select_employee'] === false)
        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-2" href="#as-step-2">
                <h4 class="panel-title">
                    2. <span>{{ trans('as.embed.layout_3.select_employee') }}</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-2" data-parent="#varaa-as-bookings" class="panel-collapse collapse" data-url="{{ route('as.embed.employees', $allInput) }}">
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-3" href="#as-step-3">
                <h4 class="panel-title">
                    @if((bool)$user->asOptions['auto_select_employee']) 2 @else 3 @endif. <span>{{ trans('as.embed.layout_3.select_datetime') }}</span>
                    <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                    <i id="as-datepicker" class="glyphicon glyphicon-calendar pull-right"></i>
                </h4>
            </div>
            <div id="as-step-3" data-parent="#varaa-as-bookings" class="panel-collapse collapse" data-url="{{ route('as.embed.timetable', $allInput) }}">
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" id="as-title-4" href="#as-step-4">
                <h4 class="panel-title">
                    @if((bool)$user->asOptions['auto_select_employee']) 3 @else 4 @endif. <span>{{ trans('as.embed.layout_3.contact') }}</span> <i class="glyphicon glyphicon-ok text-success pull-right hide"></i>
                </h4>
            </div>
            <div id="as-step-4" data-parent="#varaa-as-bookings" class="panel-collapse collapse" data-url="{{ route('as.embed.checkout', $allInput) }}">
                <div id="as-overlay-message" class="as-overlay-message"></div>
                <div class="panel-body">
                    <p class="loading text-center">
                        <i class="glyphicon glyphicon-refresh text-info"></i> {{ trans('as.embed.loading') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="add-service-url" id="add-service-url" value="{{ route('as.bookings.service.front.add', $allInput) }}">
<input type="hidden" name="auto-select-employee" id="auto-select-employee" value="<?php echo ($user->asOptions['auto_select_employee']) ? 'true' : 'false';?>">
