<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+

$currency = getSettingsValue('currency');
$curSymbol = $currencyArray[$currency]['symbol'];
                            
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="maintext">

    <tr>
        <td  height="27" align="right" >&nbsp;&nbsp;
            <b>Amount (<?php echo $curSymbol;?> ) : </b></td>
        <td  width="30%" align="left" >&nbsp;<b><?php echo(number_format(round($totalamt,2),2)); ?></b></td>
    </tr>
    <tr>
        <td colspan=2 align=center class=maintext>
					The amount charged for publishing this site in FTP/ZIP format is <?php echo(number_format(round($totalamt,2),2)); ?>.Please use the below form to make the payment

        </td>
    </tr>

    <tr>
        <td height="20" colspan="2" align="left"><div align="center"><font color="#FF0000"><?php echo($cc_err);?></font></div></td>
    </tr>

    <?php
    include_once("./includes/payfields.php");
    ?>


    <!-- End OF Payment Form -->
    <tr>
        <td colspan=2 align="center" valign="middle"><br>
            <input type="button" name="btPay" id="btPay" class="button"  value="Pay Now" onClick="javascript:clickPay();" style=" width:120px;">
            &nbsp; &nbsp;

        </td>
    </tr>
</table>