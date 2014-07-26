<?php  
include_once "includes/function.php";
include "includes/applicationheader.php";

$currency = getSettingsValue('currency');
?>

<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.easing.js"></script>
<script language="javascript" type="text/javascript" src="js/script.js"></script>
 
<style>

    ul.lof-main-wapper li {
        position:relative;	
    }
</style>

</head>
<body>

    <div class="wrps_main" >
        <!-- top hrd wrap start -->
        <div class="top_wrps">

            <div class="tpwrp_inner">
                <?php if(file_exists($logo)) { ?>
                <div class="logo_tpsections">
                    <a href="index.php"><img src ="<?php echo BASE_URL.$logo; ?>" border=0></a>
                    <div class="clear"></div>
                </div>
                <?php } ?>
                <div class="top_rightsection">
                    <!-- ///start of header links.........................................    -->
                                <?php
                                include "toplinks.php";
                                ?>
                    <!-- ///end of headerlinks....................................................    -->

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- top hrd wrap start -->

        <!-- bnr wrap start -->
        <div class="banner_wrps">
            <div class="bnr_inwrap">
                <div class="banner_lftxtsection">
                    <?php 
                    $cmsData = getCmsData('main'); 
                    ?>
                    <div class="banner_text">
                        <div class="head">
                            <?php echo $cmsData['section_title']; ?>
                        </div>
                        <div>
                            <?php echo $cmsData['section_content']; ?>
                        </div>
                    </div>
                   
                    <?php
                    
                     
                    $paySupport = getSettingsValue('paymentsupport'); 
                    
                    if($paySupport == 'yes') {
					$siteprice = getSettingsValue('site_price'); 
					 
                    ?>
                    <div class="price_dvs">
                        <div class="price_amnt">
                            <h5><?php echo HOME_PRICE_STARTS. $currencyArray[$currency]['symbol'].$siteprice; ?></h5></div>
                        <div class="clear"></div>
                    </div>
                    <?php } ?>
                    <div class="clear"></div>
                    <div class="strtbtn">
                        <a href="signup.php?type=startnow" class="btn"><?php echo HOME_START; ?></a>
                    </div>
                </div>
                <div class="banner_slide_section">
                    <div class="slide_computwrp">
                        <div class="slider_container">
                            <!-- Slider area -->
                            <div id="lofslidecontent45" class="lof-slidecontent">
                                <div class="lof-main-outer">
                                    <ul class="lof-main-wapper">
                                        <li><img src="images/slider_images/slide1.jpg"  height="200" width="360"></li>
                                        <li><img src="images/slider_images/slide2.jpg"  height="200" width="360"></li>
                                        <li><img src="images/slider_images/slide3.jpg"  height="200" width="360"></li>
                                        <li><img src="images/slider_images/slide4.jpg"  height="200" width="360"></li>
                                    </ul>
                                </div>
                                <!-- Slider area ends -->
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- bnr wrap end -->

        <!-- pnchline ad wrap start -->

        <div class="promo_dv_ads">
            <div class="prom_addmid">
                <div class="bx-mid01 lft">
                        <h6><?php echo HOME_EASY_CREATING; ?></h6>
                </div>
                <div class="bx-mid02 lft">
                        <h6><?php echo HOME_EASY_PROMOTING; ?></h6>
                </div>
                <div class="bx-mid03 lft">
                        <h6><?php echo HOME_EASY_MANAGING; ?></h6>
                </div>
                <div class="bx-mid04 lft">
                        <h6><?php echo HOME_EASY_PUBLISHING; ?></h6>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>

        <!-- pnchline ad wrap end -->

        <!-- Content area home start -->


        <div class="cntarea_dvs" style=" padding-bottom:100px !important">

            <div class="cnt_innerdvs">
