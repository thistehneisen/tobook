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
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$RB_LANG['errors'][$_GET['err']]);
	}
	?>
	
	<?php pjUtil::printNotice($RB_LANG['info']['options_index']); ?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=update" method="post" class="form" id="frmOptions">
		<input type="hidden" name="options_update" value="1" />
		<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php echo $RB_LANG['option_general']; ?></a></li>
				<li><a href="#tabs-2"><?php echo $RB_LANG['option_bookings']; ?></a></li>
				<li><a href="#tabs-3"><?php echo $RB_LANG['option_confirmation']; ?></a></li>
				<li><a href="#tabs-4"><?php echo $RB_LANG['option_booking_form']; ?></a></li>
				<li><a href="#tabs-5"><?php echo $RB_LANG['option_terms']; ?></a></li>
				<li><a href="#tabs-6"><?php echo $RB_LANG['option_reminder']; ?></a></li>
				<li><a href="#tabs-7"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			</ul>
			<div id="tabs-1">
			<?php
			$tab_id = 1;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs- -->
			<div id="tabs-2">
			<?php
			$tab_id = 2;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-2 -->
			<div id="tabs-3">
			<?php
			$tab_id = 3;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-3 -->
			<div id="tabs-4">
			<?php
			$tab_id = 4;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-4 -->
			<div id="tabs-5">
			<?php
			$tab_id = 5;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-5 -->
			<div id="tabs-6">
			<?php
			$tab_id = 6;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-6 -->
			<div id="tabs-7">
			<?php
			$tab_id = 7;
			include VIEWS_PATH . 'pjAdminOptions/elements/tab.php'
			?>
			</div> <!-- tabs-7 -->
		</div>
	</form>
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{
		$tab_id = explode("-", $_GET['tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		//$tab_id = (int) $_GET['tab_id'] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "selected", <?php echo $tab_id; ?>);
			});
		})(jQuery);
		</script>
		<?php
	}
}
?>