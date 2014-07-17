<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	global $as_pf;
	
	if ( count($tpl['bs_arr']) > 0 ) {
	?>
	<form action="" method="post" id="frmEditTime" class="form pj-form">
		<input type="hidden" name="booking_id" value="<?php echo $tpl['bs_arr']['id']; ?>">
		<p>
		<?php 
		$time = array();	
		
		for ( $i = 0; $i <= 60; $i += $tpl['option_arr']['o_step']) {
			$time[] = $i;
			if ( $tpl['bs_arr']['total'] > $i && $i > 0 ) $time[] = -$i;
		}
	
		sort($time);
		?>
		<select id="edittime" name="edittime" class="pj-form-field w150">
			<?php foreach ( $time as $t ) { ?>
				<option  <?php echo $t == 0 ? 'selected="selected"' : null; ?> value="<?php echo $t; ?>"><?php echo $t; ?> min</option>
			<?php } ?>
		</select>
		</p>
	</form>
	<?php 	
	}
}
?>