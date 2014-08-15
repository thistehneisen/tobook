
</td></tr></table>

</td></tr></table>

</td></tr></table>

<div class="clear"></div>
</div>


<div class="clear"></div>
</div>



<!-- Content area home end -->


<!-- Footer Area Start -->
<div class="ftr_divsmani">
	<div class="ftr_mains">
		<div class="footer_lfet ryt">
			<!-- ///start of footer links....................................................    -->
			<?php
			include("userbottomlinks.php");
			?>
			<!-- ///end of footer links....................................................    -->
			<div class="share-pnl ryt"></div>
			<div class="clear"></div>
		</div>
		<div class="footer_rgt lft">
			<p><?php echo FOOTER_POWERED; ?> <a href="http://www.iscripts.com/easycreate" class="copyright" target="_blank" ><?php echo FOOTER_PRODUCT; ?></a> . <?php echo FOOTER_PREMIUM; ?>  <a href="http://www.iscripts.com" class="copyright" target="_blank" ><?php echo FOOTER_SITE; ?></a></p>
		</div>
	<div class="clear"></div>
</div>
<!-- Footer Area End -->



<div class="clear"></div>
</div>

<?php
if(LookupDisplay('adsense_flag')=="YES"){
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

