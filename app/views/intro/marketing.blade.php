@extends('layouts.intro')

@section('title')
    @parent :: {{ trans('dashboard.marketing') }} &amp; {{ trans('home.customer_registration') }}
@stop

@section('header')
    <h1 class="text-header">{{ trans('dashboard.marketing') }} &amp; {{ trans('home.customer_registration') }}</h1>
@stop

@section('intro_content')
<div class="guide-body">
    <div class="loyalty-introduction">{{ trans('intro.marketing_guide_1') }}</div>
    <div class="loyalty-introduction color">{{ trans('intro.marketing_guide_2') }}</div>
    <a href="#"><img src="/assets/img/loyalityBtnAloita.png" class="loyalty-introduction-button" /></a>

    <div class="loyalty-general-info">
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_3') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_4') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_5') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_6') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_7') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_8') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide10') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_9') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.marketing_guide_10') }}</span>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="kantis-introduction">
        <span>{{ trans('dashboard.marketing') }} &amp; {{ trans('home.customer_registration') }}</span>
        <p>
            {{ trans('intro.marketing_guide_11') }}<br/>
            {{ trans('intro.marketing_guide_12') }} <br/>
            {{ trans('intro.marketing_guide_13') }} <br/>
            {{ trans('intro.marketing_guide_14') }}
        </p>
    </div>

    <div class="marketing-background01">
        <div class="marketing-background01-image">
            <img src="/assets/img/marketingBack01.png"/>
        </div>
        <div class="marketing-background01-text">
            <div class="marketing-background01-text-header">
                <span>{{ trans('intro.marketing_guide_15') }}</span>
            </div>
            <div class="marketing-background01-text-content">
                {{ trans('intro.marketing_guide_16') }}
            </div>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="marketing-background02">
        <div class="marketing-background02-circle">
                {{ trans('intro.marketing_guide_17') }}
        </div>
        <div class="marketing-background02-text">
            <span>{{ trans('intro.marketing_guide_6') }}</span>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="marketing-background01">
        <div class="marketing-background01-image">
            <img src="/assets/img/marketingBack03.png" />
        </div>
        <div class="marketing-background01-text">
            <div class="marketing-background01-text-header">
                <span>{{ trans('intro.marketing_guide_18') }}</span>
            </div>
            <div class="marketing-background01-text-content">
                {{ trans('intro.marketing_guide_19') }}
            </div>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="marketing-background04">
        <div class="timeslot-demo-content">
            <div class="marketing-background04-header">
                <span>{{ trans('intro.marketing_guide_18') }}!</span>
            </div>
            <div class="marketing-background02-circle">
                {{ trans('intro.marketing_guide_20') }}
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
</div>
@stop
