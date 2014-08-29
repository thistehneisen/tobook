@extends ('layouts.intro')

@section ('title')
    @parent :: {{ trans('dashboard.timeslot') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('dashboard.timeslot') }}</h1>
@stop

@section ('subheader')
    <h6 class="text-subheader">{{ trans('intro.timeslot_subheader') }}</h6>
@stop

@section ('intro_content')
<div class="guide-body">
    <div class="timeslot-introduction">{{ trans('intro.timeslot_guide_1') }} 24/7</div>
    <div class="timeslot-introduction color">{{ trans('intro.timeslot_guide_2') }}</div>

    <div class="loyalty-general-info">
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_subheader') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_guide_3') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_guide_4') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_guide_5') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_guide_6') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.loyalty_guide9') }}</span>
            </div>
        </div>
        <div class="loyalty-general-info-column">
            <div class="loyalty-general-info-row">
                <img src="/assets/img/iconArrow.png"/ >&nbsp;
                <span>{{ trans('intro.timeslot_guide_8') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.timeslot_guide_9') }}</span>
            </div>
            <div class="loyalty-general-info-row margin">
                <img src="/assets/img/iconArrow.png" />&nbsp;
                <span>{{ trans('intro.loyalty_guide11') }}</span>
            </div>
        </div>
        <div class="clear-both"></div>
    </div>
    <div class="kantis-info-content"><img src="/assets/img/appPCs.png"/></div>
    <div class="timeslot-general-info">
        <p>{{ trans('intro.timeslot_guide_10') }}</p>
        <p>{{ trans('intro.timeslot_guide_11') }}</p>
        <p>{{ trans('intro.timeslot_guide_12') }}</p>
        <p>{{ trans('intro.timeslot_guide_13') }}</p>
        <p>{{ trans('intro.timeslot_guide_14') }}</p>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-image"><img src="/assets/img/appThumb01.png"></div>
        <div class="timeslot-demo-content margin">
            <p class="timeslot-demo-content-paragraph padding">{{ trans('intro.timeslot_guide_15') }}</p>
            <p class="timeslot-demo-content-paragraph-color">{{ trans('intro.timeslot_guide_16') }}</p>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-content">
            <p class="timeslot-demo-content-paragraph margin">{{ trans('intro.timeslot_guide_17') }}</p>
            <p class="timeslot-demo-content-paragraph-color padding">{{ trans('intro.timeslot_guide_18') }}</p>
        </div>
        <div class="timeslot-demo-image margin"><img src="/assets/img/appThumb02.png"></div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-image"><img src="/assets/img/appThumb03.png"></div>
        <div class="timeslot-demo-content margin">
            <p class="timeslot-demo-content-paragraph padding">{{ trans('intro.timeslot_guide_19') }}</p>
            <p class="timeslot-demo-content-paragraph-color">{{ trans('intro.timeslot_guide_20') }}</p>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-content margin-top">
            <p class="timeslot-demo-content-paragraph">{{ trans('intro.timeslot_guide_21') }}</p>
            <p class="timeslot-demo-content-paragraph-color padding">{{ trans('intro.timeslot_guide_22') }}</p>
        </div>
        <div class="timeslot-demo-image margin"><img src="/assets/img/appThumb04.png"></div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-image"><img src="/assets/img/appThumb05.png"></div>
        <div class="timeslot-demo-content margin">
            <p class="timeslot-demo-content-paragraph padding">{{ trans('intro.timeslot_guide_23') }}</p>
            <p class="timeslot-demo-content-paragraph-color">{{ trans('intro.timeslot_guide_24') }}</p>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-content">
            <p class="timeslot-demo-content-paragraph margin">{{ trans('intro.timeslot_guide_3') }}</p>
            <p class="timeslot-demo-content-paragraph-color padding">{{ trans('intro.timeslot_guide_25') }}</p>
        </div>
        <div class="timeslot-demo-image margin"><img src="/assets/img/appThumb02.png"></div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-image"><img src="/assets/img/appThumb06.png"></div>
        <div class="timeslot-demo-content margin">
            <p class="timeslot-demo-content-paragraph padding">{{ trans('intro.loyalty_guide9') }}</p>
            <p class="timeslot-demo-content-paragraph-color">{{ trans('intro.timeslot_guide_26') }}</p>
        </div>
        <div class="clear-both"></div>
    </div>

    <div class="timeslot-demo">
        <div class="timeslot-demo-content margin-top">
            <p class="timeslot-demo-content-paragraph">{{ trans('intro.timeslot_guide_27') }}</p>
            <p class="timeslot-demo-content-paragraph-color padding">{{ trans('intro.timeslot_guide_26') }}</p>
        </div>
        <div class="timeslot-demo-image margin"><img src="/assets/img/appThumb07.png"></div>
        <div class="clear-both"></div>
    </div>
    <div class="timeslot-footer">&nbsp;</div>
</div>
@stop
