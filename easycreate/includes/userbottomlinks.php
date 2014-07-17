<?php
if($_SESSION["session_rootserver"])
    $currentloc=$_SESSION["session_rootserver"]."/";
else
    $currentloc=BASE_URL;
  
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
    <tr>
        <td>
            <p>
                <a href="<?php echo $currentloc; ?>sitemanager.php"> <?php echo FOOTER_SITE_MANAGER; ?></a> |
                <a href="<?php echo $currentloc; ?>pagemanager.php"> <?php echo FOOTER_PAGE_MANAGER; ?></a> |
                <!--<td >|</td>
                <td ><a href=<?php //echo $currentloc ?>imagemanager.php class=toplinks style="font-weight:normal;">
                Image Manager
                </a> </td>
                <td >|</td>
                <td ><a href=<?php //echo$currentloc ?>gallerymanager.php class=toplinks style="font-weight:normal;">
                Gallery Manager
                </a></td>-->
                <a href="<?php echo $currentloc; ?>gallerymanager.php"> <?php echo FOOTER_GALLERY_MANAGER; ?></a> |
                <a href="<?php echo $currentloc; ?>profilemanager.php"> <?php echo FOOTER_PROFILE_MANAGER; ?></a> |
                <a href="<?php echo $currentloc; ?>promotionmanager.php"> <?php echo FOOTER_PROMOTION_MANAGER; ?></a> |
                <a href="<?php echo $currentloc; ?>integrationmanager.php"> <?php echo FOOTER_INTEGRATION_MANAGER; ?></a>
            </p>
        </td>
    </tr>
</table>
				        
  
    
      