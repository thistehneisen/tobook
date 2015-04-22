@extends ('layouts.front')

@section('title')
    {{ trans('common.intro') }}
@stop

@section('css')
{{ HTML::style(asset_path('core/styles/business2.css')) }}
@stop

@section('content')
<div class="header">
    <div class="header-content">
        <h1>Tarvitsetko uusia asiakkaita?</h1>
        <p>Lisää yrityksesi Suomen johtavaan ajanvarausportaaliin!</p>
    </div>
    <p><a class="btn btn-lg btn-orange" href="{{ route('auth.register') }}" role="button">Rekisteröidy</a></p>
</div>

<div class="jan">
    <div class="jan-content">
        <h2>Hei! Me olemme varaa.com!</h2>
        <p>Missionamme on auttaa kauneus ja hyvinvointialaa menestymään digitalisoitumisen murroksessa!</p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Me haluamme auttaa</h2>
                    <p>
                        Me olemme  Suomen johtava
                        ajanvarausportaali jonka avulla
                        pyrimme auttamaan sinua:
                    </p>
                    <p>
                        Täyttäämään tyhjiä aikoja
                        Vastaanottamaan varauksia 24/7
                    </p>
                    <p>Minimoimaan no showt
                        Vähentämään hallintoa
                        Kasvattamaan liikevaihtoa
                    </p>
              </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Miten me autamme?</h2>
                    <p>
                        Me luomme yrityksellesi esittelyn varaa.comiin.
                    </p>
                    <p>
                        Me markkinoimme yritystäsi
                        varaa.comia lukuisissa eri
                        medioissa  onka avulla tuomme
                         sinulle uusia asiakkaita.
                    </p>
                    <p>
                        Saat meiltä myös
                        ajanvarausjärjestelmän omille
                        kotisivuillesi
                    </p>
                </div>
           </div>
            <div class="col-lg-4 col-md-4">
                <div class="jan-cell">
                    <h2>Kasvata myyntiä</h2>
                    <p>
                        Sinä ostat lomamatkasi verkosta,
                        varaat pöytäsi verkossa
                        kuten tuhannet muutkin kuluttajat.
                    </p>
                    <p>
                        Nyt kuluttajat varaavat myös
                        kauneuspalveluita verkosta.
                    </p>
                    <p>
                        Varmista, että sinun yritykseesi
                        varauksen tekeminen onnistuu!
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
                <img class="img-responsive" src="{{ asset_path('core/img/front/figure4.jpg') }}">
            </div>
            <div class="col-md-6">
                <h3>Varaa.com profiili</h3>
                <p class="text">
                    Varaa.com profiili parantaa yrityksesi verkkonäkyvyyttä parantaen
                     löydettävyyttäsi verkossa!
                    </p>

                <p class="text">Voit luoda Varaa.comin sisään  tarjouksia haluamiisi palveluihin haluamanasi
                    ajankohtana houkutellaksesi asiakkaita kokeilemaan palveluitasi!
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h3>Ajanvarauskalenterit</h3>
                <p class="text">
                    Sähköisen kalenterin kautta kuluttaja voi helposti etsiä
                    kalenteristasi hänelle parhaiten sopivan ajan!</p>
                <p class="text">
                Pääset käsiin kalenteriisi mistä vain kunhan sinulla on verkkoyhtey laitteesta riippumatta.
                </p>
            </div>
            <div class="col-md-6 image-holder">
                <img class="img-responsive" src="{{ asset_path('core/img/front/figure3.jpg') }}">
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
