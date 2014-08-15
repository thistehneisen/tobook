<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+

$curTab = 'contents';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";
include_once "../includes/function.php";

$page = $_REQUEST['mode'];
$cmsData = getCmsData($page); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<script type="text/javascript" src="<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo fetchBasicLocation(1); ?>/ckeditor/js/jquery.js"></script>
        <script type="text/javascript">
            
            $(document).ready(function(){

	    CKEDITOR.replace( 'editor1',
            {

            /*toolbar :
            [
                ['newplugin','footnote','Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList','-','Outdent','Indent'],
                ['Link','Unlink','Anchor','Image', '-', 'Format','Font','FontSize', 'TextColor']
            ],*/

            filebrowserBrowseUrl : '<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/filemanager/index.php',
            filebrowserImageBrowseUrl : '<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/filemanager/index.php?Type=Images',
            filebrowserUploadUrl : '<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/filemanager/core/connector/php/filemanager.php?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl : '<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/filemanager/core/connector/php/filemanager.php?command=QuickUpload&type=Images',
            filebrowserImageManagerUrl : '<?php echo fetchBasicLocation(1); ?>/ckeditor/ckeditor/filemanager/index.php?Type=Images&CKEditor=editor1&CKEditorFuncNum=4&langCode=en&filemode=server'
            });

     CKEDITOR.config.width = 500;
     CKEDITOR.config.height = 300;
     CKEDITOR.config.fullPage = true;
     CKEDITOR.on( 'dialogDefinition', function( ev ){

                // Take the dialog name and its definition from the event data.
		var dialogName = ev.data.name;
		var dialogDefinition = ev.data.definition;
		// Check if the definition is from the dialog we're
		// interested on (the Link dialog).
		if ( dialogName == 'link' ){
			// FCKConfig.LinkDlgHideAdvanced = true
                        dialogDefinition.removeContents('advanced');

                        /*
                        var definition = ev.data.definition;
                        var content = definition.getContents( 'target' );
                        content.remove( 'linkTargetType' );
                        */
                        var info = dialogDefinition.getContents( 'info' );
                        info.remove('protocol');


                        info.add( {
					type : 'text',
					label : 'Protocol',
					id : 'protocol',
					'default' : 'http://',
					validate : function()
					{
						if ( /\d/.test( this.getValue() ) )
							return 'Protocol is must';
					}
				});

			// FCKConfig.LinkDlgHideTarget = true
			//dialogDefinition.removeContents( 'default' );
                         /*Enable this part only if you don't remove the 'target' tab in the previous block.*/
			// FCKConfig.DefaultLinkTarget = '_blank'
			// Get a reference to the "Target" tab.
			var targetTab = dialogDefinition.getContents( 'target' );

                        /*
                        var cdrt  =   targetTab.get('linkTargetType');
                        cdrt.remove('_blank');
                        */

                        //targetTab.remove('PopupWindowFeatures');
                        //targetTab.remove('linkTargetType');

                        //$("#cke_81_select option[value='_blank']").remove();




                        targetTab.add( {
					type : 'select',
                                        id : 'cke_81_select',
                                        label : 'Target',
                                        items : [ [ 'New Window', '_blank' ], [ 'Same Window', '_self' ] ],
                                        'default' : 'Football',
                                        onChange : function( api ) {
                                        //this = CKEDITOR.ui.dialog.select
                                        alert( 'Current value: ' + this.getValue() );
                                        }
                                       });
                      /*
                        targetTab.add( {
					type : 'radio',
					label : 'Link Type',
					id : 'cke_81_select',
					items   :   [ [ 'New Window', '_blank' ], [ 'Same Window', '_self' ] ]
				});
                       */
                            targetTab.add( {
					type : 'file',
                                        id : 'upload',
                                        label : 'Select file from your computer',
                                        size : 38

				});
                        targetTab.add( {
					type : 'text',
					label : 'My Custom Field',
					id : 'customField',
					'default' : 'added new!',
					validate : function()
					{
						if ( /\d/.test( this.getValue() ) )
							return 'My Custom Field must not contain digits';
					}
				});


			// Set the default value for the URL field.
			//var targetField = targetTab.get( 'linkTargetType' );

			//targetField[ 'default' ] = '_blank';
		}
		if ( dialogName == 'image' ){

			dialogDefinition.removeContents( 'advanced' );
                        var info        =   dialogDefinition.getContents( 'info' );

                        var brwse       =   info.get('browse');
                        brwse.label     =   'Browse';

                        var alttxt      =   info.get('txtAlt');
                        alttxt.label    =   'Image Title';

                        var wdth        =   info.get('txtWidth');
                        wdth.label      =   'Image Width';
                        wdth.width      =   60;

                        var hght = info.get('txtHeight');
                        hght.label  =   'Image Height';
                        hght.width  =   60;

                        var valg = info.get('cmbAlign');
                        valg.items = [['left','left'],['right','right']];
                        valg['default'] =   'left';

                        var Link        =   dialogDefinition.getContents( 'Link' );
                        var Url         =   Link.get('txtUrl');
                        Url.label       =   'Make Image Clickable Link';
                        Url['default']  =   'http://';
                        Link.remove('browse');

                        var target       =   Link.get( 'cmbTarget' );
                        target.label     =   '<br />Target Page';
                        target.items     =   [['New Window','_blank'],['Same Window','_self']];

                        var Upload       =   dialogDefinition.getContents( 'Upload' );
                        var upload       =   Upload.get('uploadButton');
                        upload.label     =   'Upload Image';


		}
		/*if ( dialogName == 'flash' )
		{
			// FCKConfig.FlashDlgHideAdvanced = true
			dialogDefinition.removeContents( 'advanced' );
		}*/
	});
        //CKEDITOR.instances.editor1.setData(initialContent);
        

	});

        function goback(){

         document.frmPayment.action="cmslisting.php";
         document.frmPayment.submit();

}

</script>
<?php
$linkArray = array( CONTENTS       =>'admin/cmslisting.php',
                    EDIT_CONTENTS  =>'admin/contentmanagement.php?mode='.$page);
?>
<link rel="stylesheet" href="../style/common.css" />
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray); ?>
    <h2><?php echo EDIT_CONTENTS;?></h2>

<form name="frmPayment" method="post" action="cmsmanagement.php">
    <div class="form-pnl">
            <?php if($_SESSION['successMsg']){ ?>
            <div class="successmessage"><?php  echo $_SESSION['successMsg']; unset($_SESSION['successMsg']);?></div>
            <?php } ?>
            <ul>
                <li>
                    <label><?php echo SECTION_NAME;?></label>
                    <input name="section_name" type="text" readonly value="<?php echo $cmsData['section_name'];?>">
                </li>
                <li>
                    <label><?php echo SECTION_TITLE;?></label>
                    <input size="80" name="section_title" type="text" value="<?php echo $cmsData['section_title']; ?>">
                </li>
                <?php 
                /*
                if($page == 'main') {?>
                <li>
                    <label>Start Price</label>
                    <input size="20" name="section_price" type="text" value="<?php echo $cmsData['section_price']; ?>"> $
                 <div style="margin-left:220px;">
                 	<span class="helptext">This will be the price of the site display in homepage .</span>
                </div> 
                </li>
                <?php } 
                */
                ?>
                <li>
                    <label><?php echo SECTION_CONTENT;?></label>
                    <div style="display: inline-block;">
                    <IFRAME id=foo style="display:none; "></IFRAME>
                    <textarea id="editor1" name="section_content"><?php echo $cmsData['section_content']; ?></textarea>
                    </div>
                    <div class="clear"></div>
                </li>
                <!-- Commenting this section since it is not using any where -->
                <!--li>
                    <label>Section Help</label>
                    <input name="section_help" type="checkbox" value="1" <?php //echo ($cmsData['section_help']==1)?'checked':''; ?>>
                </li-->
                
                <li>
                    <label>&nbsp;</label>
                    <span class="btn-container">
                        <input  class=btn02 type=button value="<?php echo BTN_BACK;?>"  onClick="goback();"  >
                        <input class="btn01" name="cmsSubmit" value="<?php echo BTN_UPDATE;?>" type="submit">
                    </span>
                </li>
            </ul>
        </div>

</form>
</div>


<?php
include "includes/adminfooter.php";
?>