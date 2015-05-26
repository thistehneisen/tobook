@extends ('layouts.dashboard')

@section('title')
    {{ trans('user.profile.index') }}
@stop

@section ('styles')
    @parent
    {{ HTML::style(asset('packages/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')) }}
@stop

@section ('scripts')
    @parent
    {{ HTML::script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.2/moment-with-locales.min.js') }}
    {{ HTML::script(asset('packages/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')) }}
    <script>
$(function () {
    $('a.delete-image').on('click', function (e) {
        e.preventDefault();
        if (confirm('{{ trans('common.are_you_sure') }}')) {
            var $this = $(this);
            $.ajax({
                url: $this.attr('href'),
                type: 'GET'
            }).done(function () {
                $this.parent().fadeOut(1000, function () {
                    $(this).remove();
                });
            });
        }
    });

    $('input.time-picker').datetimepicker({
        pickDate: false,
        minuteStepping: 15,
        format: 'HH:mm',
        language: '{{ App::getLocale() }}'
    });

    var $deposit = $('#js-payment-options-deposit'),
    $rate = $('#js-deposit-rate');
    $deposit.prop('checked') && ($rate.show());

    $deposit.on('change', function () {
        if ($(this).prop('checked')) {
            $rate.show();
        } else {
            $rate.hide();
        }
    });
});
    </script>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <!-- Nav tabs -->
        <br>
        <ul class="nav nav-tabs" role="tablist">
            <li {{ $activeTab === 'general' ? 'class="active"' : '' }}><a href="#tab-general" role="tab" data-toggle="tab">{{ trans('user.profile.general') }}</a></li>
        @if ($user->is_business)
            <li {{ $activeTab === 'business' ? 'class="active"' : '' }}><a href="#tab-business" role="tab" data-toggle="tab">{{ trans('user.profile.business') }}</a></li>
            <li {{ $activeTab === 'working-hours' ? 'class="active"' : '' }}><a href="#tab-working-hours" role="tab" data-toggle="tab">{{ trans('user.profile.working_hours') }}</a></li>
            <li {{ $activeTab === 'images' ? 'class="active"' : '' }}><a href="#tab-images" role="tab" data-toggle="tab">{{ trans('user.profile.images') }}</a></li>
        @endif
            <li {{ $activeTab === 'password' ? 'class="active"' : '' }}><a href="#tab-password" role="tab" data-toggle="tab">{{ trans('user.change_password') }}</a></li>
        </ul>
        <br>
        @include ('el.messages')

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane {{ empty($activeTab) || $activeTab === 'general' ? 'active' : '' }}" id="tab-general">
                @include ('user.el.general')
            </div> <!-- General information -->

        @if ($user->is_business)
            <div class="tab-pane {{ $activeTab === 'business' ? 'active' : '' }}" id="tab-business">
                @include ('user.el.business')
            </div> <!-- Business information -->

            <div class="tab-pane {{ $activeTab === 'working-hours' ? 'active' : '' }}" id="tab-working-hours">
                @include ('user.el.working_hours', ['working_hours' => $business->working_hours_array])
            </div> <!-- Working hours -->

            <div class="tab-pane {{ $activeTab === 'images' ? 'active' : '' }}" id="tab-images">
                @include ('user.el.images')
            </div> <!-- Images -->
        @endif

            <div class="tab-pane {{ $activeTab === 'password' ? 'active' : '' }}" id="tab-password">
                @include ('user.el.password')
            </div> <!-- Change password -->
        </div>
    </div>
</div>
@stop
