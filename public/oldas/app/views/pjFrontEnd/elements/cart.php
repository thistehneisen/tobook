<?php
$cartClass = NULL;
switch ($_GET['action'])
{
	case 'pjActionService':
	case 'pjActionServices':
		break;
	case 'pjActionCheckout':
	case 'pjActionPreview':
		$cartClass = ' asCartInnerPreview';
		break;
}
?>
<div class="asBox asCartOuter">
	<div class="asCartInner<?php echo $cartClass; ?>">
		<div class="asHeading"><?php __('front_selected_services'); ?></div>
		<div class="asSelectorCartWrap asOverflowHidden">
		<?php include PJ_VIEWS_PATH . 'pjFrontEnd/pjActionGetCart.php'; ?>
		</div>
	</div>
</div>