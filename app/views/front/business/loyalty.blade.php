@extends ('layouts.business')

@section('title')
    @parent :: {{ trans('dashboard.loyalty') }}
@stop

@section('page-header')
    <h1 class="text-header">{{ trans('dashboard.loyalty') }}</h1>
@stop

@section('subheader')
    <h6 class="text-subheader">{{ trans('intro.loyalty_sub_header') }}</h6>
@stop

@section('intro_content')
<div class="loyalty-introduction">{{ trans('intro.loyalty_guide1') }}</div>
<div class="loyalty-introduction color">{{ trans('intro.loyalty_guide2') }}</div>
<div class="loyalty-introduction">{{ trans('intro.loyalty_guide3') }}</div>
<a href="{{ URL::route('auth.register') }}" class="btn btn-success btn-lg text-uppercase">{{ trans('home.start_now') }} <i class="fa fa-arrow-circle-right"></i></a>

<div class="loyalty-general-info">
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide4') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide5') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide6') }}</span>
        </div>
    </div>
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span >{{ trans('intro.loyalty_guide7') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide8') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide9') }}</span>
        </div>
    </div>
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide10') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide11') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide12') }}</span>
        </div>
    </div>
    <div class="clear-both"></div>
</div>

<div class="kantis-introduction">
    <span>{{ trans('intro.loyalty_kantis') }}</span>
    <p>
        {{ trans('intro.loyalty_kantis_1') }}<br/>
        {{ trans('intro.loyalty_kantis_2') }}<br/>
        {{ trans('intro.loyalty_kantis_3') }}<br/>
        {{ trans('intro.loyalty_kantis_4') }}
    </p>
</div>

<div class="loyalty-background01">
    <div class="kantis-circle">
        <div class="kantis-circle content">{{ trans('intro.loyalty_kantis_5') }}</div>
    </div>
    <div class="kantis-info">
        <div class="kantis-info-header">{{ trans('intro.loyalty_kantis_6') }}:</div>
        <div class="kantis-info-content">
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_7') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_8') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_9') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconArrow.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_10') }}</span>
            </div>
        </div>

        <div class="kantis-info-header margin">{{ trans('intro.loyalty_kantis_11') }}:</div>
        <div class="kantis-info-content">
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_12') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_13') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_14') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_15') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_16') }}</span>
            </div>
            <div class="kantis-info-content margin">
                <img src="{{ asset_path('core/img/iconPlus.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_17') }}</span>
            </div>

        </div>
    </div>
    <div class="clear-both"></div>
</div>

<div class="loyalty-controlling">
    <div class="loyalty-controlling-header">{{ trans('intro.loyalty_kantis_18') }}!</div>
    <div class="loyalty-controlling-description">
        <div class="loyalty-controlling-description-image"><img src="{{ asset_path('core/img/loyalityBody02.png') }}"></div>
        <div class="loyalty-controlling-description-text">
            <span class="color-orange">{{ trans('intro.loyalty_kantis_19') }}!</span>
            <br/><br/>
            <span class="color-black">{{ trans('intro.loyalty_kantis_20') }}</span>
            <div class="loyalty-controlling-description-text-row">
                <img src="{{ asset_path('core/img/iconCheck.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_21') }}</span>
            </div>
            <div class="loyalty-controlling-description-text-row">
                <img src="{{ asset_path('core/img/iconCheck.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_22') }}</span>
            </div>
            <div class="loyalty-controlling-description-text-row margin-bottom">
                <img src="{{ asset_path('core/img/iconCheck.png') }}" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_kantis_23') }}</span>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
</div>

<div class="kantis-text">
        <span class="color-orange">Kantiskortti</span>
        <span class="color-black">.com</span>
    </div>
    <img src="{{ asset_path('core/img/loyalityBody03.png') }}" class="kantis-text-image">
</div>
@stop
