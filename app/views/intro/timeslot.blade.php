@extends ('layouts.intro')

@section ('title')
    @parent :: {{ trans('dashboard.timeslot') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('dashboard.timeslot') }}</h1>
@stop

@section ('subheader')
    <h6 class="text-subheader">{{ trans('intro.timeslot_subheader') }}</h4>
@stop

@section ('content')
<div class="guideBody">
    <div style="font-weight:bold; font-size:20px; padding-top: 30px;">{{ trans('intro.timeslot_guide_1') }} 24/7</div>
    <div style="color:#999; font-size:20px; padding-top: 10px;">{{ trans('intro.timeslot_guide_2') }}</div>
    
    <div style="width:80%;margin-left: 10%;text-align: left; margin-top: 30px; margin-bottom: 20px;" >
        <div style="width:33%;margin-top:30px;" class="floatleft">
            <div>
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">{{ trans('intro.timeslot_subheader') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">{{ trans('intro.timeslot_guide_3') }}</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">{{ trans('intro.timeslot_guide_4') }}</span>
            </div>              
        </div>
        <div style="width:33%;margin-top:30px;" class="floatleft">
            <div>
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Käytettävissä kaikkialla</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Tekstiviesti-ilmotukset</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Asiakasrekisteri</span>
            </div>              
        </div>
        <div style="width:33%;margin-top:30px;" class="floatleft">
            <div>
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Erittäin helppokäyttöinen</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Tekijäkohtaiset kestot</span>
            </div>
            <div style="margin-top:15px;">
                <img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Markinointityäkalut</span>
            </div>              
        </div>
        <div class="clearboth"></div>       
    </div>
    <div style="margin-top:30px;"><img src="/asset/image/appPCs.png"/></div>
    <div style="color:#999; font-size:16px; padding-top: 10px;margin-bottom:30px;">
        <p style="margin-bottom:2px;margin-top:5px;">Elämä on nykypäivänä hektisempää kuin koskaan aiemmin. Ihmiset ovat kiireisempiä ja kaiken tulisi olla helppoa ja nopeaa.</p>
        <p style="margin-bottom:2px;margin-top:5px;">Sähköinen ajanvaraus palvelee asiakkaitasi ympäri vuorokauden vaikka et itse olisi töissä.</p>
        <p style="margin-bottom:2px;margin-top:5px;">Sähköisen kalenterin kautta kuluttaja voi helposti etsiä kalenteristasi hänelle parhaiten sopivan ajan.</p>
        <p style="margin-bottom:2px;margin-top:5px;">Yrittäjänä pääset sähköiseen kalenteriin käsiksi työpaikalta, kotoa tai vaikka lounasravintolasta.</p>
        <p style="margin-bottom:2px;margin-top:5px;">Verkkopohjainen kalenteri vaatii ainoastaan pääsyn internettiin.</p>
    </div>  
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb01.png" style="width:100%;"></div>
        <div class="floatleft" style="margin-left:5%;width:60%;">
            <p style="font-size:25px;font-weight:bold;padding-left:30px;">Aloita varausten vastaanottaminen muutamassa minuutissa!</p>
            <p style="color:#999;">Ajanvarauksen käyttönottaminen on helppoa ja yksinkertaistaista! Kokeile!</p>
        </div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:60%;">
            <p style="font-size:25px;font-weight:bold;">Vastaanota varauksia kotisivuiltasi!</p>
            <p style="color:#999;padding-left:15px;">Ajanvarausjärjestelmän avulla helpotat sinun ja asiakkaidesi välistä kommunikointia.</p>
        </div>
        <div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb02.png" style="width:100%;"></div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb03.png" style="width:100%;"></div>
        <div class="floatleft" style="margin-left:5%;width:60%;">
            <p style="font-size:25px;font-weight:bold;padding-left:30px;">Varaukset myös facebookista!</p>
            <p style="color:#999;">Sosiaalisen median vaikutus jatkaa kasvamista joten on tärkeää hyödyntää kaikkimahdollisuudet jonka vuoksi suosittelemme lisäämään varauskalenterin myös omille facebook sivuillesi helposti!</p>
        </div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:60%;">
            <p style="font-size:25px;font-weight:bold;">Brändisi mukainen ulkoasu!</p>
            <p style="color:#999;padding-left:15px;">Ajanvarauksen ulkoasu on mahdollista suunnitella toiveidesi mukaan!</p>
        </div>
        <div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb04.png" style="width:100%;"></div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb05.png" style="width:100%;"></div>
        <div class="floatleft" style="margin-left:5%;width:60%;">
            <p style="font-size:25px;font-weight:bold;padding-left:30px;">Joustavat ominaisuudet!</p>
            <p style="color:#999;">Voit räätälöidä työajat, palveluidenkestot, resurssit yms. tarpeidesi mukaan!</p>
        </div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:60%;">
            <p style="font-size:25px;font-weight:bold;">Monipuoliset raportointityökalut</p>
            <p style="color:#999;padding-left:15px;">Monipuolisten raportointityökalujen ansioista sinun on mahdollista saada ensimmäistä kertaa liiketoimintasi tunnuslukuja.</p>
        </div>
        <div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb02.png" style="width:100%;"></div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb06.png" style="width:100%;"></div>
        <div class="floatleft" style="margin-left:5%;width:60%;">
            <p style="font-size:25px;font-weight:bold;padding-left:30px;">Asiakasrekisteri</p>
            <p style="color:#999;">Asiakasrekisterin avulla asiakkaasi ovat kätevästi yhdessä paikassa.</p>
        </div>
        <div class="clearboth"></div>
    </div>
    
    <div style="width:76%;margin-left: 12%;">
        <div class="floatleft" style="width:60%;">
            <p style="font-size:25px;font-weight:bold;">Mahtavat markkinointityökalut</p>
            <p style="color:#999;padding-left:15px;">Asiakasrekisterin avulla asiakkaasi ovat kätevästi yhdessä paikassa.</p>
        </div>
        <div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb07.png" style="width:100%;"></div>
        <div class="clearboth"></div>
    </div>              
    <div style="margin-top:50px;">&nbsp;</div>  
</div>
@stop
