<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>         		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<script>
    function checkthis(){
        
        frm=document.frmbuildsite;
        
        if (frm.sitename.value.length == 0 ||frm.sitename.value.substr(0,1) == " ") {
            alert('<?php echo SM_MESSAGE_VALIDATION_EMPTY_SITENAME ?>');
            frm.sitename.focus();
            return false;
        } 
        return true;
    }
    
</script>
<div class="border-pnl">
 	<h3><?php echo SM_START_BUILDING; ?></h3>
	<form name=frmbuildsite method=post onsubmit="return checkthis();">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr><td style="padding: 10px 0 10px 0;" colspan=3 class=errormessage><?php echo $message;?></td></tr>
                    <tr>
                        <td width="25%">
                            <label><?php echo SM_PROVIDE_NAME; ?></label>
                        </td>
                        <td align="left" width="52%">
                            <input type="text" name=sitename id=sitename  maxlength=49 size=40 value="<?php //echo htmlentities($_SESSION['session_sitename']);?>">
                        </td>
                        <td >
                            <input type="submit" name=subbuild class="btn03" value="<?php echo strtoupper(SM_Go);?>">
                        </td>
                    </tr>
		</table>
	</form>
	<div class="clear"></div>
</div>

 