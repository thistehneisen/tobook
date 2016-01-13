@extends ('layouts.default')

@section('title')
    {{ trans('common.business') }}
@stop

@section('styles')
{{ HTML::style(asset_path('core/styles/business.css')) }}
@stop


@section('main-classes') nova-container @stop

@section('content')
 <div class="nova-header">
    <div class="header-content">
        <h1><span class="varaa">varaa</span><span class="com">.com</span> ajanvarausjärjestelmä</h1>
        <p>Meidän ilmainen ja helppokäyttöinen järjestelmä tulee mullistamaan liiketoimintasi</p>
    </div>
    <p><a class="btn btn-lg btn-orange" href="{{ route('auth.register') }}" role="button">Rekisteröidy</a></p>
</div>

<div class="jan">
    <div class="jan-content">
        <h2>Tee elämästäsi helpompaa kuin koskaan!</h2>
        <p>Oli yrityksesi kauneushoitola, kampaamo tai muu hyvinvointialan yritys meidän palvelumme sopii sinulle!</p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Varaa aikoja</h2>
                    <p>
                        Varaa.com ajanvarausjärjestelmä
                        on helppokäyttöinen, mutta erittäin
                        monipuolinen ajanvarausjärjestelmä.
                    </p>
                    <p>
                        Varausjärjestelmä on rakennettu
                        yhteistyössä Suomen johtavien kauneus
                        ja kampaamoalan yritysten kanssa.
                    </p>
                    <p>
                        Järjestelmä sopii erinomaisesti niin yhden
                        hengen yritykselle kuin isolle ketjulle!
                    </p>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Hallinnoi asiakkaita</h2>
                    <p>
                        Sähköinen asiakasrekisteri pitää
                        asiakastietosi helposti ajantasalla.
                    </p>
                    <p>
                        Järjestelmän kautta seuraat asiakkaiden
                        edellisiä käyntejä sekä voit tehdä
                        käynneistä muistiinpanoja.
                    </p>
                    <p>
                    Järjestelmän kautta voit lähettää
                    asiakkaillesi markkinointiviestejä
                    sähköpostiin tai suoraan puhelimeen
                    </p>
                </div>
           </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Kasvata myyntiä</h2>
                    <p>
                        Ajanvarauksen avulla asiakkaasi
                        voivat varata aikoja myös kotisuviltasi
                        <strong>24/7</strong>!
                    </p>
                    <p>
                        Sähköisen ajanvarauksen kautta pystyt
                        myös helposti mittamaan kuinka moni
                        asiakas on reagoinut eri kampanjoihisi.
                    </p>
                    <p>
                        Varaa.com tekee kaikkensa tuodakseen
                        sinulla uusia asiakkaita portaalinsa
                        kautta!
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
                <img class="img-responsive" src="{{ asset_path('core/img/front/varaa/figure0.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Räätälöitävä ulkoasu</h3>
                <p class="text">
                    Voimme helposti luoda ajanvarauksesta yrityksesi näköisen!
                    Me tiedämme kuinka paljon arvostat yrityksesi brändi, jonka vuoksi
                    olemme tehneet ulkoasusta helposti täysin räätälöitävän!</p>

                <p class="text">Meillä on 3 eri asettelua jos voit valita omasta mielestäsi sinun yrityksellesi
                    parhaan vaihtoehdon!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Monipuoliset raportoinnit</h3>
                <p class="text">
                    Monipuolisten raportointityökalujen ansioista sinun on mahdollista
                    saada ensimmäistä kertaa liiketoimintasi tunnuslukuja. </p>
                <p class="text">
                    Niiden avullanäet budjetoidun liikevaihtosisekä varaustilanteesi
                    joiden avulla voit suunnitella markkinointiasi.
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/varaa/figure1.jpg') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/varaa/figure2.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Huone, resurssit ja tekijäkohtaiset kestot</h3>
                <p class="text">
                Onko yritykselläsi eri mittaiset kestot eri tekijöillä tai onko hoitolassasi vain
                2 huonetta, mutta 3 tekijää? Ei huolta.</p>

                <p class="text">Me olemme saaneet palautetta yli 200 asiakkaalta kuinka kalenterin tulisi
                toimia ja olemme rakentaneet sen asiakkaidemme toivoiden ja tarpeiden
                pohjalta!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Työaikasuunnittelu</h3>
                <p class="text">
                    Järjestelmään on helppo määrittää oletustyöaikoja
                    joita voit helposti muokata tilanteen mukaan.</p>
                <p class="text">
                    Sinulla on mahdollisuus räätälöidä
                    epäsäännöllisiä työaikoja kolmen kuukauden päähän.
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/varaa/figure3.jpg') }}">
            </div>
        </div>
    </div>
</div>
<div class="mar">
    <div class="mar-content">
        <h2>missä vaan, milloin vaan</h2>
        <p>Varaa.comiin sinun ei tarvitse osata asentaa ohjelmia käyttääksesi
        kalenteria. Sinun tarvitsee vain omistaa laite jolla pääset internettiin</p>
    </div>
</div>
<div class="apr">
    <div class="apr-content">
        <h2>Näin pääset mukaan!</h2>
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
                    <h3 class="text">Rekisteröidy ja esittele yrityksesi</h3>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <h3 class="text">Luo palvelut, tekijät ja lisää varauksesi</h3>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <h3 class="text">Ilmoita meille, kunolet valmis!</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="may">
    <div class="may-content">
        <h1>Liity satojen edelläkävijöiden joukkoon!</h1>
    </div>
</div>

<div class="jun">
    <div class="jun-content">
        <h1>Ole edelläkävijä ja elä unelmaasi!</h1>
        <p>
            Olla edelläkävijä on itsensä haastamista, uuden oppimista ja tietoisten, mutta kannattavien riskien ottamista. Varaa.comin koko henkilökunta on sitoutunut olemaan tukenasi.
        </p>
        <a class="btn btn-lg btn-orange" role="button">Rekisteröidy</a>
    </div>
</div>
@stop
