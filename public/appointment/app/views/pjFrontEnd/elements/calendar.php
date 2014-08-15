<div class="asBox asCalendarOuter">
	<div class="asCalendarInner">
		<div class="asHeading"><?php __('front_select_date'); ?></div>
		<div class="asSelectorCalendar">
		<?php /*<div style="position: relative; height: 0; width: 100%; padding-bottom: 100%">
		<div style="position: absolute; top: 0; width: 100%; height: 100%; background: cyan"> */?>
		<?php
		list($year, $month,) = explode("-", $_GET['date']);
		echo $tpl['calendar']->getMonthHTML((int) $month, $year);
		?>
		<?php /*</div>
		</div>*/?>
		</div>
	</div>
</div>
