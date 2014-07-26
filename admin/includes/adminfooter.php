</div>
<div class="clear"></div>
</div>

<div class="clear"></div>
</div>


 
<!-- Content area home end -->


<!-- Footer Area Start -->
	<div class="admin-ftr-pnl">
		<div class="admin-ftr-nw">
			<!--div class="footer_comm_div">
			   <!-- ///start of footer links....................................................    -->
							<?php
							//include("userbottomlinks.php");
							?>
							<!-- ///end of footer links....................................................    -->
			</div-->
			<div class="ftr-lft">
				<p><?php echo MSG_POWERED_BY;?> <a href="http://www.iscripts.com/" target="_blank"><?php echo MSG_ISCRIPT;?></a></p>
			</div>
                        <?php
                         if($_SESSION['session_username']=='admin'){?>
                            <div class="ftr-ryt">
                                    <p><a href=dashboard.php><?php echo DASHBOARD;?></a> | <a href=settings.php ><?php echo SETTINGS;?></a> | <a href=templatemanager.php ><?php echo TEMP_MANAGER;?></a> | <a href=usermanager.php ><?php echo USERS;?></a> | <a href=sitemanager.php ><?php echo SITES;?></a> | <a href=payment.php ><?php echo PAYMENTS;?></a> | <a href="#"  onClick="javascript:openWindow('adminhelp/index.html');" title="Open Admin Help Window"><?php echo HELP;?></a></p>
                            </div>
                       <?php
                         }?>
		<!--p align="center">Powered by <a href="http://www.iscripts.com/easycreate" class="copyright" target="_blank" >iScripts EasyCreate</a> . A premium product from <a href="http://www.iscripts.com" class="copyright" target="_blank" >iScripts.com</a></p-->
		</div>
		<div class="clear"></div>
	</div>
<!-- Footer Area End -->
<div class="clear"></div>
</div>
</body>

</html>



