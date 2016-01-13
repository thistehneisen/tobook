@extends ('layouts.default')

@section('title')
    {{ trans('common.business') }}
@stop

@section('styles')
{{ HTML::style(asset_path('core/styles/business.lv.css')) }}
@stop


@section('main-classes') nova-container @stop

@section('content')
 <div class="nova-header">
    <div class="header-content">
        <h1>Vai vēlaties piesaistīt jaunus klientus?</h1>
        <p>Pievienojieties Latvijā pirmajai skaistumkopšanas pakalpojumu rezervēšanas platformai Tobook.lv </p>
    </div>
    <p><a class="btn btn-lg btn-orange" href="{{ route('auth.register') }}" role="button">Reģistrējiet savu uzņēmumu bez maksas</a></p>
</div>

<div class="jan">
    <div class="jan-content">
        <h2>Tobook.lv - jaunas iespējas jūsu ērtībām</h2>
        <p>Mēs esam radījuši platformu, kura ir paredzēta, lai uzlabotu jūsu salona darbu un padarītu ērtāku jūsu klientu ikdienu</p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Rezervāciju sistēma</h2>
                    <p>
                        Tobook.lv ir viegli lietojama un ļoti daudzpusīga sistēma, kas ir radīta sadarbībā ar skaistumkopšanas saloniem, lai tā pilnībā atbilstu visām šī biznesa prasībām, tādējādi atvieglojot un attīstot jūsu biznesu.
                    </p>
                    <p>
                       Tobook.lv sistēma ir piemērota gan maziem, gan lieliem uzņēmumam, turklāt tā pieejama bez maksas.
                    </p>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Klientu administrēšana</h2>
                    <p>
                        Labs klientu serviss ir viens no pakalpojumu biznesa svarīgākajiem elementiem. Izmantojot Tobook.lv sistēmu, varat būt droši, ka jūsu klienti jutīs patiesas rūpes, kuras atmaksāsies ar lojalitāti jūsu uzņēmumam. 
                    </p>
                </div>
           </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Efektivitātes palielināšana</h2>
                    <p>
                        zmantojot Tobook.lv reģistrācijas sistēmu, klienti var pierakstīties jūsu pakalpojumiem jebkurā laikāun vietā <storng>24/7</storng>, tādējādi brīvie rezervāciju laiki aizpildīsies ātrāk un efektīvāk.  
                    </p>
                    <p>
                        Tobook.lv komanda regulāri strādā pie portāla atpazīstamības veicināšanas, tādēļ esam lieliska platforma jaunu klientu piesaistīšanai.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="feb">
    <div class="container">
        <div class="row">
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure1.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Pievienojiet savus pakalpojumus</h3>
                <p class="text">
                    zveidojiet klientam ērti pārskatāmu informāciju par savu uzņēmumu - pievienojiet jūsu sniegtos pakalpojumus, to aprakstus, cenas, bildes un pat īpašus piedāvājumus, ērti un ātri. Visas izmaiņas jūsu uzņēmuma konta lapā automātiski būs redzamas ikvienam, kurš to apmeklē. 
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Plānojiet darbu</h3>
                <p class="text">
                    Tobook.lv sistēma ir speciāli radīta skaistumkopšanas saloniem un to darba specifikai, lai viegli un pārskatāmi varētu saplānot darba laiku, pierakstus, atvaļinājumus un brīvdienas tieši tā, kā jums nepieciešams, Turklāt sistēma piedāvāarī atskaites par konkrētiem laika periodiem un saņemt mēneša pārskatus par salona darbu.
                </p>

                <p class="text">
                    Ja vēlaties, mēs varam integrēt rezervācijas formu arī Jūsu mājas lapā, lai esošie klienti pakalpojumiem var pierakstīties vēl ātrāk un ērtāk.
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure2.jpg') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure3.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Rūpējieties par klientiem</h3>
                <p class="text">
                    Tobook.lv sistēma sniedz iespēju sakārtot un viegli pārvaldīt jūsu klientu datu bāzi, nodrošinot visas informācijas uzglabāšanu vienuviet. Pavisam vienkārši varat sekot līdzi klientu rezervāciju vēsturei un piezīmēm, lai atvieglotu savu darbu un uzlabotu jūsu klientu servisu.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Atvieglojiet savu un klientu ikdienu</h3>
                <p class="text">
                    Pievienojot savu uzņēmumu Tobook.lv sistēmai, jūsu pakalpojumu rezervācija ir pieejama jebkurā laikāun vietā. Klientiem vairs nav jums jāzvana vai jāraksta, lai noskaidrotu brīvos rezervāciju laikus, jo tie uzreiz ir redzami jūsu Tobook.lv profilā. Tas palīdzēs piepildīt brīvās rezervācijas ātrāk un efektīvāk.
                </p>
                <p class="text">
                    Par klienta veikto online rezervāciju jūs saņemsiet sms no sistēmas un redzēsiet veikto rezervāciju savāToook.lv profilā.
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure4.jpg') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure5.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Izmantojiet mārketinga iespējas</h3>
                <p class="text">
                    Tobook.lv ik mēnesi apmeklēvairāk kā 12 tūkstoši apmeklētāju, kuri interesējas par skaistumkopšanas pakalpojumiem, tādēļ tā ir lieliska vieta, kur izvietot sava uzņēmuma pakalpjumus. Turklāt Tobook.lv sistēma piedāvā sms un epasta rīkus, kas ļauj ērti un vienkārši izsūtīt Jūsu uzņēmuma īpašos piedāvājumus klientiem.
                </p>
                <p class="text">
                    Jebkuras aktivitātes efektivitāti varat apskatīt atskaites sadaļā, redzot tās atdevi, tādējādi uzzinot, kas un cik labi ir strādājis.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Maksājiet tikai par rezultātiem</h3>
                <p class="text">
                    Mēs strādājam, lai palīdzētu attīstīt jūsu biznesu, tādēļ, izmantojot Tobook.lv, ir jāmaksā tikai par rezultātiem! Visi Tobook.lv platformas rīki tiek nodrošināti bez maksas un jūs maksājat vienreizēju komisijas maksu tikai tajābrīdī, kad mēs jums piesaistām jaunu klientu. 
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/tobook/lv/figure6.jpg') }}">
            </div>
        </div>
    </div>
</div>
<div class="mar">
    <div class="mar-content">
        <h2>Vienmēr un visur</h2>
        <p>Lai lietotu Tobook.lv sistēmu, nav nepieciešams instalēt speciālas programmas. Apskatiet un pievienojiet rezervācijas, klientus un darbus jebkurā laikā, vietā un ierīcē ar interneta savienojumu.</p>
    </div>
</div>
<div class="apr">
    <div class="apr-content">
        <h2>Kas jādara, lai pievienotos Tobook.lv? </h2>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-2">
                    <div class="square">
                        1
                    </div>
                </div>
                <div class="col-md-1 bar-parent">
                    <hr class="bar">
                </div>
                <div class="col-md-2">
                    <div class="square">
                        2
                    </div>
                </div>
                <div class="col-md-1 bar-parent">
                    <hr class="bar">
                </div>
                <div class="col-md-2">
                    <div class="square">
                        3
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-2">
                    <h3 class="text">Aizpildiet reģistrācijas formu</h3>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <h3 class="text">Mēs jums izveidosim bezmaksas kontu</h3>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <h3 class="text">Pievienojiet sava uzņēmuma informāciju un sāciet izmantot Tobook.lv priekšrocības</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="may">
    <div class="may-content">
        <h1>Pievienojieties Tobook.lv un palieliniet sava uzņēmuma efektivitāti jau tagad! </h1>
    </div>
</div>

<div class="jun">
    <div class="jun-content">
        <h2>Pievienojieties Tobook.lv un palieliniet sava uzņēmuma efektivitāti jau tagad! </h2>
        <a class="btn btn-lg btn-orange" role="button">Reģistrējiet savu uzņēmumu bez maksas</a>
    </div>
</div>
@stop
