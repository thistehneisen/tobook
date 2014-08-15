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

@section ('content')
<div class="guideBody">
    <div style="font-weight: bold; font-size: 20px; padding-top: 30px;">{{ trans('intro.loyalty_guide1') }}</div>
    <div style="color: #999; font-size: 20px; padding-top: 10px;">{{ trans('intro.loyalty_guide2') }}</div>
    <div style="font-weight: bold; font-size: 20px; padding-top: 10px;">{{ trans('intro.loyalty_guide3') }}</div>
    <div style="margin-top: 20px;"><a href="#"><img src="/assets/img/loyalityBtnAloita.png" style="width: 300px;"/></a></div>

    <div style="width: 80%; margin-left: 10%; text-align: left; margin-top: 30px; margin-bottom: 20px;">
        <div style="width: 33%; margin-top: 30px; float: left" class="floatleft">
            <div>
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral" style="font-size: 16px;">{{ trans('intro.loyalty_guide4') }}</span>
            </div>
            <div style="margin-top: 15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide5') }}</span>
            </div>
            <div style="margin-top: 15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide6') }}</span>
            </div>              
        </div>
        <div style="width: 33%; margin-top: 30px; float: left" class="floatleft">
            <div>
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide7') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide8') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide9') }}</span>
            </div>              
        </div>
        <div style="width:33%;margin-top:30px;float:left" class="floatleft">
            <div>
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide10') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide11') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span class="loyaltyGeneral">{{ trans('intro.loyalty_guide12') }}</span>
            </div>              
        </div>
        <div style="clear:both"></div>       
    </div>

    <div style="background:#f6841e;color:#FFF;padding-top:30px; padding-bottom:40px;">
        <span style="font-size:40px;">{{ trans('intro.loyalty_kantis') }}</span>
        <p style="line-height:30px;">
            {{ trans('intro.loyalty_kantis_1') }}<br/>
            {{ trans('intro.loyalty_kantis_2') }}<br/>
            {{ trans('intro.loyalty_kantis_3') }}<br/>
            {{ trans('intro.loyalty_kantis_4') }}
        </p>
    </div>

    <div class="loyalityBackground01">
        <div class="floatleft" style="width:42%;float:left">
            <div style="width:300px;height:300px; border-radius: 150px;background:#eb8b2e;margin-top:200px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
                <div style="padding-top:90px;line-height:30px;">{{ trans('intro.loyalty_kantis_5') }}</div>
            </div>
        </div>
        <div class="floatleft" style="margin-left:3%;margin-top:80px;text-align:left;width:55%;float:left">
            <div>
                <span style="font-size:30px;font-weight: normal;">{{ trans('intro.loyalty_kantis_6') }}:</span>
            </div>
            <div style="margin-top:40px;">
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_7') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_8') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_9') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconArrow.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_10') }}</span>
                </div>              
            </div>

            <div style="margin-top:40px;">
                <span style="font-size:30px;font-weight: normal;">{{ trans('intro.loyalty_kantis_11') }}:</span>
            </div>
            <div style="margin-top:40px;">
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_12') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_13') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_14') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_15') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_16') }}</span>
                </div>
                <div style="margin-top:15px;">
                    <img src="/assets/img/iconPlus.png" class="loyaltyArrow"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_17') }}</span>
                </div>                                                              

            </div>          
        </div>
        <div style="clear:both"></div>
    </div>

    <div style="margin-top:50px;border-bottom:3px solid #CCC;">
        <span style="color:#000;font-size:36px;font-weight:normal;">{{ trans('intro.loyalty_kantis_18') }}!</span>
        <div style="margin-top:70px;width:90%;margin-left:5%;">
            <div class="floatleft" style="width:50%;float:left;">
                <img src="/assets/img/loyalityBody02.png" style="width:100%;">
            </div>
            <div class="floatleft" style="margin-left:50px;margin-top:20px;text-align:left;font-size:20px;width:45%;float:left;">
                <span style="color:#f6841e;">{{ trans('intro.loyalty_kantis_19') }}!</span>
                <br/><br/>
                <span style="color:#000;">
                    {{ trans('intro.loyalty_kantis_20') }}
                </span>
                <div style="margin-top:20px;">
                    <img src="/assets/img/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_21') }}</span>
                </div>
                <div style="margin-top:20px;">
                    <img src="/assets/img/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_22') }}</span>
                </div>
                <div style="margin-top:20px;margin-bottom:30px;">
                    <img src="/assets/img/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">{{ trans('intro.loyalty_kantis_23') }}</span>
                </div>                                                  
            </div>
            <div style="clear:both"></div>           
        </div>
    </div>

    <div style="margin-top:40px;">
        <div style="font-size:56px;">
            <span style="color:#f6841e;">Kantiskortti</span><span class="fontBlack">.com</span>
        </div>
        <img src="/assets/img/loyalityBody03.png" style="width:100%;margin-top:100px;">    
    </div>
</div>
@stop
