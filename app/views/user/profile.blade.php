@extends ('layouts.dashboard')

@section('title')
    @parent :: {{ trans('user.profile.index') }}
@stop

@section ('scripts')
    @parent
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
                $this.parent().fadeOut(1000, function() {
                    $(this).remove();
                });
            });
        }
    });
});
    </script>
@stop

@section ('content')
<div class="row">
    <div class="col-xs-12">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li {{ $activeTab === 'general' ? 'class="active"' : '' }}><a href="#tab-general" role="tab" data-toggle="tab">{{ trans('user.profile.general') }}</a></li>
        @if (empty($consumer))
            <li {{ $activeTab === 'business' ? 'class="active"' : '' }}><a href="#tab-business" role="tab" data-toggle="tab">{{ trans('user.profile.business') }}</a></li>
            <li {{ $activeTab === 'images' ? 'class="active"' : '' }}><a href="#tab-images" role="tab" data-toggle="tab">{{ trans('user.profile.images') }}</a></li>
        @endif
            <li {{ $activeTab === 'password' ? 'class="active"' : '' }}><a href="#tab-password" role="tab" data-toggle="tab">{{ trans('user.change_password') }}</a></li>
        </ul>
        <br>
        @include ('el.messages')

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
                @include ('user.el.general')
            </div> <!-- General information -->

        @if (empty($consumer))
            <div class="tab-pane {{ $activeTab === 'business' ? 'active' : '' }}" id="tab-business">
                @include ('user.el.business')
            </div> <!-- Images -->

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
