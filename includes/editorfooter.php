<!--------------------------------------------editor footersection_new starts------------------------------------------------- -->

<?php $siteId = $_SESSION['siteDetails']['siteId']; ?>

<div class="editor_footernew">

	<div class="left">

            <input name="" type="button" value="<?php echo TEMPLATE_SAVE;?>" class="grey-btnstyle" onclick="window.location='getsitedetails.php?siteid=<?php echo $siteId; ?>&action=<?php echo $action;?>'">

	</div>

	

	 

        <div class="right">

		

		<div id="editactionloader" class="editactionloader" style="display:none;"><img src="images/img_loading_bar.gif" /></div>

            <a title="Click to publish the site" href="#" class="orange_btnstyle jQPublishSite right"><?php echo TEMP_PUBLISH;?></a>

            <a title="Click to save the site" href="#" class="orange_btnstyle jQSaveSiteDetails right"><?php echo TEMP_SAVE;?></a>

            <a title="Click to view the site" href="editor_sitepreview.php" class="grey-btnstyle2 sitepreview right" ><?php echo TEMP_PREVIEW;?></a>

	</div>

	<div class="clear"></div>

</div>

<!--------------------------------------------editor footersection_new ends------------------------------------------------- -->