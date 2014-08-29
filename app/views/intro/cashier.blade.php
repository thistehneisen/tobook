@extends('layouts.intro')

@section('title')
@parent :: {{ trans('dashboard.cashier') }}
@stop

@section('header')
<h1 class="text-header">{{ trans('dashboard.cashier') }}</h1>
@stop

@section('intro_content')
<div class="loyalty-introduction">{{ trans('intro.cashier_guide_1') }}</div>
<div class="loyalty-introduction color">{{ trans('intro.cashier_guide_2') }}</div>
<a href="{{ URL::route('auth.register') }}" class="btn btn-success btn-lg text-uppercase">{{ trans('home.start_now') }} <i class="fa fa-arrow-circle-right"></i></a>

<div class="loyalty-general-info">
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_3') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_4') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_5') }}</span>
        </div>
    </div>
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_6') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.loyalty_guide8') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_7') }}</span>
        </div>
    </div>
    <div class="loyalty-general-info-column">
        <div class="loyalty-general-info-row">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_8') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_9') }}</span>
        </div>
        <div class="loyalty-general-info-row margin">
            <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
            <span>{{ trans('intro.cashier_guide_10') }}</span>
        </div>
    </div>
    <div class="clear-both"></div>
</div>
<div class="kantis-introduction">
    <span>{{ trans('intro.cashier_guide_11') }}</span>
    <p>
        {{ trans('intro.cashier_guide_12') }}
    </p>
</div>
<div class="cashier-background01">
    <div class="cashier-background01-circle">
        <div class="cashier-background01-circle-header">{{ trans('intro.cashier_guide_10') }}</div>
        <div class="cashier-background01-circle-content">
            {{ trans('intro.cashier_guide_13') }}
        </div>
    </div>
    <div class="clear-both"></div>
</div>
<div class="cashier-background02">
    <span class="cashier-background02-header">{{ trans('intro.cashier_guide14') }}</span>
    <div class="cashier-background02-content">
        <div class="cashier-background02-content-image">
            <img src="/assets/img/cashierBack02.png">
        </div>
        <div class="cashier-background02-content-circle">
            <div class="cashier-background02-content-circle-header">{{ trans('intro.cashier_guide_6') }}</div>
            <div class="cashier-background02-content-circle-text">{{ trans('intro.cashier_guide15') }}</div>
        </div>
        <div class="clear-both"></div>
    </div>
</div>
<div class="cashier-background03">
    <div class="cashier-background03-header">
        <span>{{ trans('intro.cashier_guide16') }}</span>
    </div>
    <div class="cashier-background03-circle">
        <div class="cashier-background03-circle-header">{{ trans('intro.cashier_guide_6') }}</div>
        <div class="cashier-background03-circle-content">{{ trans('intro.cashier_guide17') }}</div>
    </div>
</div>
@stop
