<?php
if (!headers_sent())
{
	session_name('MarketingTool');
	@session_start();
} 
?>
<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
<head>
    <?php require_once("common/config.php"); ?>
    <?php require_once("common/DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>    
	<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all'/>
	<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all'/>    
    
	<script src="js/footable.js" type="text/javascript"></script>
	<script src="js/footable.sort.js" type="text/javascript"></script>
    
	<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all'/>
	<script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/templateForm.js"></script>

    <link href="wysiwyg/bootstrap/bootstrap_extend.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
    
	<script src="wysiwyg/scripts/innovaeditor.js" type="text/javascript"></script>
	<script src="wysiwyg/scripts/innovamanager.js" type="text/javascript"></script>
	
	<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" type="text/javascript"></script>
	<script src="wysiwyg/scripts/common/webfont.js" type="text/javascript"></script>
	
	<script src="wysiwyg/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>	    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
		if( !in_array( $prefix, $emailCreators)){
			echo "You can't access this page.";
			exit();
		}
		if( isset($_GET['id']) ){
			$templateId = base64_decode($_GET['id']);
			$sql = "select * from tbl_email_template where email_template = $templateId";
			$dataTemplate = $db->queryArray( $sql );
			$dataTemplate = $dataTemplate[0];
			$pageType = "EDIT";
		}else{
			$pageType = "ADD";
		}
    ?>
</head>
<body>
	<input type="hidden" value="<?php echo $templateId;?>" id="templateId"/>
	<div class="container">
			<div style="width: 800px;margin:20px auto;">
				<h3 style="color:#2980b9;"><?php echo $MT_LANG['saveTemplate']?></h3>
			</div>
            <div style="text-align:center; width: 800px;margin:20px auto;">
	            <div class="floatright">
	            	<button class="btn-u btn-u-blue" onclick="onSaveTemplate()"><i class="icon-edit"></i>&nbsp;<?php echo $MT_LANG['saveTemplate']?></button>
	            	<button class="btn-u btn-u-orange" onclick="window.location.href='templateList.php'"><i class="icon-list-ul"></i>&nbsp;<?php echo $MT_LANG['templateList']?></button>
	            </div>
	            <div class="clearboth"></div>
	            <br/>
				<form id="imageForm" method="post" enctype="multipart/form-data" action='async-uploadImage.php' style="margin-bottom:0px;">
					<input type="file" name="imageUpload" id="imageUpload" class="form-control" style="width: 85%;float:left;"/>						
					<input type="hidden" name="uploadType" value="emailTemplate">
					<input type="hidden" id="imagePrevDiv" value="previewImage">
					<div id="previewImage" class="previewImage floatleft">
						<?php if( $pageType == "ADD"){?>
							<img src="<?php echo DEFAULT_TEMPLATE_EMAIL;?>" style="width:100%;height: 100%;"/>
						<?php }else{ ?>
							<img src="<?php echo $dataTemplate['thumbnail'];?>" style="width:100%;height: 100%;"/>
						<?php }?>
					</div>
					<div class="clearboth"></div>
				</form>
	            <br/>
	            <input type="text" id="txtName" value="<?php echo $dataTemplate['subject'];?>" class="form-control" placeholder="<?php echo $MT_LANG['enterTemplateName']?>..."/>
	            <br/>
				<textarea id="txtEmail">
					<?php echo $dataTemplate['content'];?>
				</textarea>
			</div>
	</div>
	<script>
	    $('#txtEmail').liveEdit({
	    	fileBrowser: '/marketing/wysiwyg/assetmanager/asset.php?type=template',
	        height: 550,
	        groups: [
	                ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "RemoveFormat"]],
	                ["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
	                ["group3", "", ["Paragraph", "FontSize", "FontDialog", "TextDialog"]],
	                ["group4", "", ["LinkDialog", "ImageDialog", "TableDialog", "SourceDialog"]],
	                ]
	    });
	    $('#txtEmail').data('liveEdit').startedit();	
	</script>
</body>
</html>