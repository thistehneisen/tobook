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
<div id="getPosts">	
	<table class="table" style="width: 100%;">
		<thead>
			<tr>
				<th class="sub"></th>
				<th class="sub">Title</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$i = 0;
		foreach ($tpl['posts_arr'] as $post) {
			$i++;
			if ( $i%2 == 0 ) {
				$tr_class = " odd ";
			} else {
				$tr_class = " even ";
			}
		?>
			<tr class="<?php echo $tr_class; ?>">
				<td class="meta"><input type="radio" class="radio_postID" name="post_id[]" value="<?php echo $post['ID']; ?>"></td>
				<td class="meta"><span><?php echo $post['post_title']; ?></span></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
	<?php 
}