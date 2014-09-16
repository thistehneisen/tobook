<?php 
if ( count($tpl['extra_arr']) > 0 ) { ?>
	<form action="" method="post" id="frmExtraService" class="form pj-form">
		<input type="hidden" name="service_id" value="<?php echo isset($_GET['service_id']) ? $_GET['service_id'] : null; ?>">
		<span>Haluaisitko myös varata?</span>
		<p>
			<?php foreach ($tpl['extra_arr'] as $extra) { ?>
			<label for="extra_id_<?php echo $extra['id']; ?>"><input type="checkbox" id="extra_id_<?php echo $extra['id']; ?>" name="extra_id[]" value="<?php echo $extra['id']; ?>" /> <?php echo $extra['name'] . ' (' . $extra['length'] . 'min)'; ?></label><br>
			<?php } ?>
		</p>
	</form>
<?php } ?>
