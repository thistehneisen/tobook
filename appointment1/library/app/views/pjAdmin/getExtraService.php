<?php 
if ( count($tpl['extra_arr']) > 0 ) { ?>
	<form action="" method="post" id="frmExtraService" class="form pj-form">
		<input type="hidden" name="booking_id" value="<?php echo isset($_GET['booking_id']) ? $_GET['booking_id'] : null; ?>">
		<p><strong>Do you want extra service?</strong></p>
		<p>
			<select name="extra_id[]" class="pj-form-field" multiple>
				<?php
				foreach ($tpl['extra_arr'] as $extra)
				{
					?><option value="<?php echo $extra['id']; ?>"<?php echo in_array($extra['id'], $tpl['bes_arr']) ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($extra['name'] . ' (' . $extra['length'] . 'min)'); ?></option><?php
				}
				?>
			</select>
		</p>
	</form>
<?php } ?>
