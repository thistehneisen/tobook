@extends ('layouts.intro')

@section ('title')
    @parent :: {{ trans('home.customer_websites') }}
@stop

@section ('header')
    <h1 class="text-header">{{ trans('home.customer_websites') }}</h1>
@stop

@section ('subheader')
    <h6 class="text-subheader">{{ trans('home.description') }}</h6>
@stop

@section ('scripts')
    <script>
        $(document).ready(function(){
            $("div.weblist-item-icon img").mouseover(function() {
                $(this).parents("div").eq(0).find(".weblist-item-icon-thumb").fadeIn();
            });
            $("div.weblist-item-icon img").mouseout(function() {
                $(this).parents("#weblistItems").find("div.weblist-item-icon-thumb").fadeOut();
            });
        });
    </script>
@stop

@section ('intro_content')
<div class="guide-body">
    <div class="loyalty-introduction">{{ trans('home.description_1') }}</div>
    <div class="loyalty-introduction color">{{ trans('home.description_2') }}</div>
    <a href="#"><img src="/assets/img/btn-tutustu.png" class="loyalty-introduction-button" /></a>
    <div id="weblistItems" class="list-head">
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb01.png"/>
                <p>Rauman Keskus-Apteekki</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb01.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb02.png" style="width:60%;"/>
                <p>Boutique Cameo</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb02.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb03.png" style="width:60%;"/>
                <p>Ravintola Torni</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb01.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb04.png" style="width:60%;"/>
                <p>West Beverly Ravintola</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb01.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb05.png" style="width:60%;"/>
                <p>Trattoria Il Faro</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb05.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb06.png" style="width:60%;"/>
                <p>BM Day Spa</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb06.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb07.png" style="width:60%;"/>
                <p>Myllypuron Apteekki</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb07.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb08.png" style="width:60%;"/>
                <p>Kauneushuone Rilla</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb01.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb09.png" style="width:60%;"/>
                <p>Pasilan Hiuspiste</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb09.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb10.png" style="width:60%;"/>
                <p>Ravintola Thalassa</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb01.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb11.png" style="width:60%;"/>
                <p>Hiustrendi</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb11.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb12.png" style="width:60%;"/>
                <p>Riitan Salonki</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb12.png" /></div>
            </div>
        </a>

        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb13.png" style="width:60%;"/>
                <p>M.Sport Liikuntakeskus</p>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb13.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb14.png" style="width:60%;"/>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb14.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb15.png" style="width:60%;"/>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb15.png" /></div>
            </div>
        </a>
        <a href="#">
            <div class="weblist-item-icon">
                <img src="/assets/img/homeWeb16.png" style="width:60%;"/>
                <div class="weblist-item-icon-thumb"><img src="/assets/img/thumbWeb16.png" /></div>
            </div>
        </a>
        <div class="clear-both"></div>
    </div>
</div>
@stop
