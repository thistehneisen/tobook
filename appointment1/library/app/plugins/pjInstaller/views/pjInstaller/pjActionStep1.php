<?php
	global $as_pf;
	// header("location: index.php?as_pf=$as_pf");
	echo "<script>window.location.href='index.php?as_pf=$as_pf&firstYN=N';</script>";
	exit();
?>
<?php
include dirname(__FILE__) . '/elements/progress.php';
?>

<div class="i-wrap">
	
	<div class="i-status i-status-success">
		<div class="i-status-icon"><abbr></abbr></div>
		<div class="i-status-txt">
			<h2>Installation successful!</h2>
		</div>
	</div>
	
</div>