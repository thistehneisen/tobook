<?php

// Download the file
if (count($tpl['arr']) > 0) {
	$output = "";
	$title = array();
	
	if ( (isset($tpl['option_arr']['cm_include_fname']) && $tpl['option_arr']['cm_include_fname'] == 2) || (isset($tpl['option_arr']['cm_include_lname']) && $tpl['option_arr']['cm_include_lname'] == 2) ) {
		$title[] = $RB_LANG['booking_name'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_phone']) && $tpl['option_arr']['cm_include_phone'] == 2 ) {
		$title[] = $RB_LANG['booking_phone'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_email']) && $tpl['option_arr']['cm_include_email'] == 2 ) {
		$title[] = $RB_LANG['booking_email'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_company']) && $tpl['option_arr']['cm_include_company'] == 2 ) {
		$title[] = $RB_LANG['booking_company'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_address']) && $tpl['option_arr']['cm_include_address'] == 2 ) {
		$title[] = $RB_LANG['booking_address'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_city']) && $tpl['option_arr']['cm_include_city'] == 2 ) {
		$title[] = $RB_LANG['booking_city'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_state']) && $tpl['option_arr']['cm_include_state'] == 2 ) {
		$title[] = $RB_LANG['booking_state'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_zip']) && $tpl['option_arr']['cm_include_zip'] == 2 ) {
		$title[] = $RB_LANG['booking_zip'];
	}
	
	if ( isset($tpl['option_arr']['cm_include_count']) && $tpl['option_arr']['cm_include_count'] == 2 ) {
		$title[] = $RB_LANG['booking_count'];
	}
	
	$output .= join(", ", $title);
	$output .= "\n";
	
	for ($i = 0; $i < count($tpl['arr']); $i++) {
		$value = array();
		
		if ( (isset($tpl['option_arr']['cm_include_fname']) && $tpl['option_arr']['cm_include_fname'] == 2) || (isset($tpl['option_arr']['cm_include_lname']) && $tpl['option_arr']['cm_include_lname'] == 2) ) {
			$c_title = isset($tpl['arr'][$i]['c_title']) ? $RB_LANG['_titles'][$tpl['arr'][$i]['c_title']] . ' ' : '';
			$value[] = $c_title . stripslashes($tpl['arr'][$i]['c_fname'] . " " . $tpl['arr'][$i]['c_lname']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_phone']) && $tpl['option_arr']['cm_include_phone'] == 2 ) {
			$value[] = $tpl['arr'][$i]['c_phone'];
		}
	
		if ( isset($tpl['option_arr']['cm_include_email']) && $tpl['option_arr']['cm_include_email'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_email']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_company']) && $tpl['option_arr']['cm_include_company'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_company']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_address']) && $tpl['option_arr']['cm_include_address'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_address']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_city']) && $tpl['option_arr']['cm_include_city'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_city']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_state']) && $tpl['option_arr']['cm_include_state'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_state']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_zip']) && $tpl['option_arr']['cm_include_zip'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['c_zip']);
		}
	
		if ( isset($tpl['option_arr']['cm_include_count']) && $tpl['option_arr']['cm_include_count'] == 2 ) {
			$value[] = stripslashes($tpl['arr'][$i]['count']);
		}
	
		$output .= join(", ", $value);
		$output .= "\n";
	}
	
	$output = str_replace("  ", " ", $output);
	$output = trim($output);
	
	$filename = "customer_" . date("Y-m-d") . ".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	
	echo $output;
	
	exit();
}
