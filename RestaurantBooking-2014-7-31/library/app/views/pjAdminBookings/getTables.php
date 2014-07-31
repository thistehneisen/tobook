<?php 
if ( isset($_GET['tablesgroup']) && $_GET['tablesgroup'] == 1 ) { ?>
	<tr>
		<td>
			<select name="tables_group_id[]" class="select">
				<option value="">---</option>
				<?php
				$tables_group = array();
					
				foreach ( $tpl['tg_arr'] as $key => $table_group ) {
				
					$check = true;
					$tables_id = explode(',', $table_group['tables_id']);
				
					foreach ($tpl['bt_not_arr'] as $table_id ) {
							
						if ( in_array($table_id, $tables_id)) {
				
							$check = false;
							continue;
						}
					}
				
					if ($check == true){
							
						$tables_group[] = $table_group;
					}
				}
				
				foreach ($tables_group as $table_group) {
					
					?><option value="<?php echo $table_group['id']; ?>|<?php echo $table_group['tables_id']; ?>"><?php echo stripslashes($table_group['name']); ?></option><?php
				}
				?>
			</select>
		</td>
		<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?rbpf=<?php echo PREFIX; ?>" class="btnRemoveTable"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>icon-delete.png" alt="" /></a></td>
	</tr>
	
<?php } else {
?>
<tr>
	<td>
		<select name="table_id[]" class="select">
			<option value="">---</option>
			<?php
			foreach ($tpl['table_arr'] as $table)
			{
				?><option value="<?php echo $table['id']; ?>|<?php echo $table['minimum']; ?>|<?php echo $table['seats']; ?>"><?php echo stripslashes($table['name']); ?></option><?php
			}
			?>
		</select>
	</td>
	<td>-</td>
	<td>-</td>
	<td><a href="<?php echo $_SERVER['PHP_SELF']; ?>?rbpf=<?php echo PREFIX; ?>" class="btnRemoveTable"><img src="<?php echo INSTALL_URL . IMG_PATH; ?>icon-delete.png" alt="" /></a></td>
</tr>
<?php } ?>