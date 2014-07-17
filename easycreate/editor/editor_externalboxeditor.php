<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add parameter values to the external boxes								                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";

//echo "<pre>";
//print_r($_GET);

$param = $_GET['params'];
if($param != '') {
    $params = explode('_',$param);
    ?>

<!--link rel="stylesheet" href="../style/jquery-ui.css" /-->
<!--script src="<?php echo BASE_URL;?>js/common.js"></script-->
<script type="text/javascript" >
    BASE_URL = '<?php echo BASE_URL;?>';

    // update the panel parameters
    $('#btn_editor_addparameters').live('click', function()	{

        $.ajax({url: "editor/editor_updateexternalboxparams.php",
            type: "POST",data: $('#extboxdataedit').serialize() ,cache: false,dataType:'html',
            success: function(html) {
                res = html.toString();

                if(res == 1)
                    $('#opendialogbox').dialog('close');

            }
        });


        return false;
    });


</script>


<div id="editor_editexternalbox_section">
    <form name="extboxdataedit" id="extboxdataedit">

            <?php
            $currentPage 	= $_SESSION['siteDetails']['currentpage'];
            $appDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$param];
            //echopre($appDetails);

            $sql 	= "Select P.* from  tbl_editor_apps_params  AS P
 				LEFT JOIN  tbl_editor_apps AS A ON P.app_id = A.app_id  
 				where A.app_class='".$params[1]."' ORDER BY A.app_id ASC";
            $result = mysql_query($sql) or die(mysql_error());
            ?>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
                <?php
                if(mysql_num_rows($result) > 0) {
                    while($row = mysql_fetch_assoc($result)) {

                        //$paramId = 1;
                        $paramClass 	= $row['param_class'];
                        $textBoxName 	= 'txteditoredit_'.$row['param_class'].'_'.$row['param_id'];

                        if(isset($appDetails))
                            $addedValue = $appDetails[$paramClass];


                        //echo $row['param_title'].':<input type="text" name="'.$textBoxName.'" id="'.$textBoxName.'" value="'.$addedValue.'"><br>';
                        ?>

            <tr>
                <td valign="middle" align="left"><?php echo $row['param_title'];?> </td>
                <td valign="middle" align="left">
                    <?php $title = "Please provide the complete '". $row['param_title']."' url (Eg : http://yoursite.com)"; ?>
                    <input class="textbox_style1" type="text" name="<?php echo $textBoxName?>" id="<?php echo $textBoxName;?>" value="<?php echo $addedValue;?>">
                    <a style="cursor: pointer;" class="masterTooltip" title="<?php echo $title;?>">
                    <img src="<?php echo BASE_URL;?>style/images/info_icon.png">
                    </a>
                </td>
            
            </tr>


                        <?php

                    }
                }

                ?>
        </table>
        <!--
 	 Add Your Link  : 
        <input type="text" name="<?php echo $textBoxName?>" id="<?php echo $textBoxName;?>"><br>

 	 -->
        <div class="popupeditpanel_ftr">
            <input type="button" name="btn_editor_addparameters" id="btn_editor_addparameters" value="Update" class="popup_orngbtn right">
            <input type="hidden" name="extboxname" id="extboxname" value="<?php echo $param;?>">
            <div class="clear"></div>
        </div>

    </form>

</div>

    <?php
}
else {
    echo "Error....";
}

?>