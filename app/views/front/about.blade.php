@extends ('layouts.default')

@section('title')
    {{ trans('common.about') }}
@stop

@section('styles')
{{ HTML::style(asset_path('core/styles/about.css')) }}
@stop

@section('main-classes') nova-container @stop

@section('content')
    <div class="nova-header">
        <div class="header-content">
            <h1><span class="varaa">varaa</span><span class="com">.com</span> ajanvarausjärjestelmä</h1>
            <p>Helppokäyttöinen ja tulospohjainen järjestelmämme tulee mullistamaan liiketoimintasi!</p>
        </div>
    </div>

    <div class="jan">
        <div class="jan-content">
            <h2>Keitä me olemme?</h2>
            <p>Me tiedämme, että menestys rakentuu useista pienistä päivittäisistä tehdyistä asioista!</p>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="jan-cell">
                        <h2>Missio</h2>
                      <p>Yleensä yritykset syyttävät
                    taloustilannetta tai muita tekijöitä
                    ongelmistaa. Isoin yksittäinen haaste
                    jota ei mainita ääneen on
                    digitalisoituminen.</p>

                    <p>Me haluamme auttaa kauneusalan
                    yrityksiä digitalisoitumisen murroksessa.
                    Me tarjoamme sinulle kaikki
                    välineet sähköiseen liiketoimintaan!</p>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="jan-cell">
                        <h2>Keitä me olemme</h2>
                        <p>Varaa.comin takana on joukko nuoria
                        yrittäjiä jotka ymmärtävät
                        pienyrittäjiä ja osaavat rakentaa
                        heidän tarpeisiin soveltuvia ratkaisuja.</p>

                        <p>Varaa.com on saanut tukea suomalaisilta
                        bisnesenkeleiltä jotka auttavat aktiivisesti
                        liiketoiminnan kehittämisessä.</p>
                    </div>
               </div>
                <div class="col-lg-4 col-md-4">
                    <div class="jan-cell">
                        <h2>Yhteystiedot</h2>
                        <p>Varaa.com Digital Oy<br/>
                        Kirkonkyläntie 3A<br/>
                        00700 Helsinki</p>

                        <p>045 146 3755<br/>
                        yritys@varaa.com</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
