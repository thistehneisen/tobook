<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
		case 3:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	?>
	<!-- 
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=install&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['option_install']; ?></a></li>
		</ul>
	</div>
	-->
	<?php pjUtil::printNotice($RB_LANG['info']['options_install']); ?>
	<ul class="menu" style="display: inline-block; margin-bottom: 10px;"><li><a id="installcontent" href="#" style="margin: 0px; padding: 0px 10px;">Insert Content</a></li></ul>
	<div class="form">
	<textarea id="code" class="textarea textarea-install w700 h100 overflow"
		><iframe src="<?php echo INSTALL_URL; ?>preview.php?rbpf=<?php echo PREFIX; ?>" width="100%" height="800px"></iframe>
	</textarea>
	<!-- 
		<textarea id="code" class="textarea textarea-install w700 h100 overflow">
&lt;link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&amp;action=loadCss" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=pjFront&amp;action=load"&gt;&lt;/script&gt;</textarea>-->
	</div>
	<div style="display: none;" id="dialogInstallContent" title="Insert Content">
		<div class="pj-form">
			<p>
				<input type="text" name="search_posts" id="search_posts" class="text w300" placeholder="Search Posts"/>
				<button id="buttonSearchContent" class="button" >Search</button>
			</p>
			<p style="margin-bottom: 20px"></p>
			<div class="content"></div>
		</div>
	</div>
	<?php
}
?>