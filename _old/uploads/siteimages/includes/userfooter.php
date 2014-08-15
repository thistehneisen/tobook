<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<!-- Content area home end -->
<!-- Footer Area Start -->

<?php

function getFormatedUrl($url){

    if($url){
        $urlExplode = explode("http",$url);
        if($urlExplode[0]=='http')
            $urlNew = $url;
        else
            $urlNew = "http://".$url;
        }
    return $urlNew;
}


$enable_home_social_shares = LookupDisplay('enable_home_social_shares'); 
$facebook_url   = LookupDisplay('facebook_url');
$facebookUrlNew = getFormatedUrl($facebook_url);
$twitter_url    = LookupDisplay('twitter_url'); 
$twitterUrlNew  = getFormatedUrl($twitter_url);
$googleplus_url = LookupDisplay('googleplus_url');
$googlePlusUrlNew = getFormatedUrl($googleplus_url);
?>
<div class="ftr_divsmani">
	<div class="ftr_mains">
		<div class="footer_lfet ryt"> 
                    
			<p><a href="index.php"><?php echo TOP_LINKS_HOME; ?></a>  |
                        <?php  if(!$_SESSION['session_userid']>0){?>
                                <a href="signup.php"><?php echo TOP_LINKS_SIGNUP; ?></a>  |
                                <a href="login.php"><?php echo LOGIN_TITLE; ?></a> |
                         <?php 
                        }?>
                        <a href="privacy.php"><?php echo FOOTER_PRIVACY; ?></a>  |
                        <a href="terms.php"><?php echo SIGNUP_TERMS; ?></a></p>
                        <?php if($enable_home_social_shares == 'Y' && ($twitter_url!="" || $facebook_url!="" || $googleplus_url!=""   )){ ?>
                        <div class="share-pnl ryt">
                            <?php if($twitter_url){ ?><a class="twitter_footer" target="_blank" href="<?php echo $twitterUrlNew; ?>"><span>Twitter</span></a><?php } ?>
                            <?php if($facebook_url){ ?><a class="facebook_footer" target="_blank" href="<?php echo $facebookUrlNew; ?>"><span>Facebook</span></a><?php } ?>
                            <?php if($googleplus_url){ ?><a class="googleplus_footer" target="_blank" href="<?php echo $googlePlusUrlNew; ?>"><span>Googleplus</span></a><?php } ?>
                            <!--a class="rss_footer" href="#"><span>Rss</span></a-->
                        </div>
                        <?php } ?>
			<div class="clear"></div>
		</div>
		<div class="footer_rgt lft">
			<p><?php echo FOOTER_POWERED; ?><a href="http://www.iscripts.com/easycreate" class="copyright" target="_blank" ><?php echo FOOTER_PRODUCT; ?></a> . <?php echo FOOTER_PREMIUM; ?> <a href="http://www.iscripts.com" class="copyright" target="_blank" ><?php echo FOOTER_SITE; ?></a></p>
		</div>
	</div>
	<div class="clear"></div>
</div>
<!-- Footer Area End -->
<div class="clear"></div>
</div>
<?php
if(LookupDisplay('adsense_flag')=="YES")
{
    $adsenseCode = LookupDisplay('adsense_code');
    if($adsenseCode!="")
        echo $adsenseCode;

}

$googleAnalytics = LookupDisplay('google_analytics');
    if($googleAnalytics!="")
        echo $googleAnalytics;

?>
</body>
</html>