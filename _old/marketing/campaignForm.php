<?php
if (!headers_sent()) {
    session_name('MarketingTool');
    @session_start();
}
?>
<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<?php
require_once("common/config.php");
require_once("../DB_Connection.php");
require_once("common/header.php");
require_once("common/asset.php");
require_once("common/functions.php");
?>
<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all' />
<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all' />
<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all' />
<link href="wysiwyg/bootstrap/bootstrap_extend.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>

<?php
$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];

MT_MkDir("img/$ownerId");

$sql = "select *
          from tbl_email_campaign
         where owner = $ownerId
         order by email_campaign desc";
$campaignList = $db->queryArray($sql);

$replyEmail = "";
$replyName = "";

if (isset($_GET['id'])) {
    $campaignId = base64_decode($_GET['id']);
    $sql = "select *
              from tbl_email_campaign
             where email_campaign = $campaignId";
    $dataCampaign = $db->queryArray($sql);
    $dataCampaign = $dataCampaign[0];
    $pageType = "EDIT";
    $replyEmail = $dataCampaign["reply_email"];
    $replyName = $dataCampaign["reply_name"];
} else {
    if (count($campaignList) > 0) {
        $replyEmail = $campaignList[0]['reply_email'];
        $replyName = $campaignList[0]['reply_name'];
    }else{
		$replyEmail = "";
		$replyName = "";
	}
    $pageType = "ADD";
}
?>
</head>
<body>
	<input type="hidden" value="<?php echo $ownerId;?>" id="ownerId" />
	<input type="hidden" value="<?php echo $campaignId;?>" id="campaignId" />
	<div class="container">
		<div style="width: 800px; margin: 20px auto;">
			<h3 style="color: #e67e22;">
				<?php echo $MT_LANG['editCampaign'];?>
			</h3>
		</div>
		<div style="text-align: center; width: 800px; margin: 20px auto;">
			<div class="floatleft">
				<button class="btn-u btn-u-blue"
					onclick="onShowEmailTemplatePopup()">
					<i class="icon-book"></i>&nbsp;
					<?php echo $MT_LANG['useTemplate'];?>
				</button>
			</div>
			<div class="floatright">
				<button class="btn-u btn-u-orange" onclick="onSaveCampaign()">
					<i class="icon-edit"></i>&nbsp;
					<?php echo $MT_LANG['saveCampaign'];?>
				</button>
				<button class="btn-u btn-u-blue"
					onclick="window.location.href='campaignList.php'">
					<i class="icon-list-ul"></i>&nbsp;
					<?php echo $MT_LANG['campaignList'];?>
				</button>
			</div>
			<div class="clearboth"></div>
			<br />
			<p>
				<input type="text" id="replyName" class="form-control"
					placeholder="<?php echo $MT_LANG['enterEmailTitle'];?>..."
					value="<?php echo $replyName?>" />
			</p>
			<p>
				<input type="text" id="replyEmail" class="form-control"
					placeholder="<?php echo $MT_LANG['enterReplyEmailAddress'];?>..."
					value="<?php echo $replyEmail?>" />
			</p>
			<p>
				<input type="text" id="txtSubject" class="form-control"
					placeholder="<?php echo $MT_LANG['enterEmailSubjectHere'];?>..."
					value="<?php echo $dataCampaign["subject"]?>" />
			</p>
			<p>
				<textarea id="txtEmail">
					<?php echo $dataCampaign["content"]?>
				</textarea>
			</p>

		</div>
	</div>
	<?php
	$sql = "select * from tbl_email_template";
	$emailTemplateList = $db->queryArray($sql);
	?>
	<div id="divBlackBackground" class="unshow"></div>
	<div id="emailTemplatePopup" class="unshow">
		<div
			style="height: 45px; background: #3498db; color: #FFF; line-height: 45px; font-size: 20px; padding-left: 20px;">
			<?php echo $MT_LANG['templateList'];?>
		</div>
		<div id="emailTemplateList"
			style="margin-top: 20px; margin-left: 20px; margin-right: 20px;">
			<?php for( $i = 0; $i < count( $emailTemplateList ); $i++ ){?>
			<div id="emailTemplateItem" class="floatleft"
				style="width: 140px; text-align: center; cursor: pointer; padding-top: 10px; padding-bottom: 10px; border-radius: 10px !important;"
				onclick="onSelectEmailTemplate(this)">
				<input type="hidden"
					value="<?php echo $emailTemplateList[$i]['email_template']?>"
					id="emailId">
				<p>
					<img src="<?php echo $emailTemplateList[$i]['thumbnail']?>"
						style="width: 100px; height: 103px;" />
				</p>
				<p>
					<b><?php echo $emailTemplateList[$i]['subject']; ?> </b>
				</p>
			</div>
			<?php } ?>
			<div class="clearboth"></div>
		</div>
		<hr />
		<div style="text-align: center;">
			<button class="btn-u btn-u-blue" style="text-align: center;"
				onclick="onChooseEmailTemplate()">
				<?php echo $MT_LANG['useThisTemplate'];?>
			</button>
			<button class="btn-u btn-u-orange" style="text-align: center;"
				onclick="onCloseEmailTemplatePopup()">
				<?php echo $MT_LANG['cancel'];?>
			</button>
		</div>
	</div>
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/campaignForm.js"></script>
<script src="wysiwyg/scripts/innovaeditor.js" type="text/javascript"></script>
<script src="wysiwyg/scripts/innovamanager.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" type="text/javascript"></script>
<script src="wysiwyg/scripts/common/webfont.js" type="text/javascript"></script>
<script src="wysiwyg/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script>
    $('#txtEmail').liveEdit({
    	fileBrowser: '/marketing/wysiwyg/assetmanager/asset.php?type=campaign&ownerId=<?php echo $ownerId?>',
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
