@extends ('layouts.intro')

@section ('title')
    @parent :: {{ trans('dashboard.loyalty') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('dashboard.loyalty') }}</h1>
@stop

@section ('subheader')
    <h6 class="text-subheader">{{ trans('intro.loyalty_sub_header') }}</h4>
@stop

@section ('intro_content')
<div class="guide-body">
    <div class="loyalty-introduction">{{ trans('intro.loyalty_guide1') }}</div>
    <div class="loyalty-introduction color">{{ trans('intro.loyalty_guide2') }}</div>
    <div class="loyalty-introduction">{{ trans('intro.loyalty_guide3') }}</div>
    <a href="#"><img src="/assets/img/loyalityBtnAloita.png" class="loyalty-introduction-button" /></a>

    <div class="loyalty-general-info">
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide4') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide5') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide6') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span >{{ trans('intro.loyalty_guide7') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide8') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide9') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide10') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                <span>{{ trans('intro.loyalty_guide11') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
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
                    <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_7') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_8') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_9') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconArrow.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_10') }}</span>
                </div>
            </div>

            <div class="kantis-info-header margin">{{ trans('intro.loyalty_kantis_11') }}:</div>
            <div class="kantis-info-content">
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_12') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_13') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_14') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_15') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_16') }}</span>
                </div>
                <div class="kantis-info-content margin">
                    <img src="/assets/img/iconPlus.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_17') }}</span>
                </div>

            </div>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="loyalty-controlling">
        <div class="loyalty-controlling-header">{{ trans('intro.loyalty_kantis_18') }}!</div>
        <div class="loyalty-controlling-description">
            <div class="loyalty-controlling-description-image"><img src="/assets/img/loyalityBody02.png"></div>
            <div class="loyalty-controlling-description-text">
                <span class="color-orange">{{ trans('intro.loyalty_kantis_19') }}!</span>
                <br/><br/>
                <span class="color-black">{{ trans('intro.loyalty_kantis_20') }}</span>
                <div class="loyalty-controlling-description-text-row">
                    <img src="/assets/img/iconCheck.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_21') }}</span>
                </div>
                <div class="loyalty-controlling-description-text-row">
                    <img src="/assets/img/iconCheck.png" class="loyalty-bullet" />&nbsp;
                    <span>{{ trans('intro.loyalty_kantis_22') }}</span>
                </div>
                <div class="loyalty-controlling-description-text-row margin-bottom">
                    <img src="/assets/img/iconCheck.png" class="loyalty-bullet" />&nbsp;
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
        <img src="/assets/img/loyalityBody03.png" class="kantis-text-image">
    </div>
</div>
@stop
