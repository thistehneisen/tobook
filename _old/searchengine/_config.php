<?php

define("DEBUG", 0);
define("ONLINE", 1); // dont edit these two

$engine_file		 = "searchengine/engines.dat";	// engine data file 
$enable_form_validate= FALSE;			// must validate form fields [javascript]
$num_of_cols		 = 2;				// number of cols in the engine display form table

$title_suffix		 = " [SubmitForce powered]";
$style_sheet_href	= "submitforce.css";

// Operating mode
$SF_mode = DEBUG;		






/*** exec time ****/
function microtime_diff($a,$b) {
    list($a_micro, $a_int)=explode(' ',$a);
     list($b_micro, $b_int)=explode(' ',$b);
     if ($a_int>$b_int) {
        return ($a_int-$b_int)+($a_micro-$b_micro);
     } elseif ($a_int==$b_int) {
        if ($a_micro>$b_micro) {
          return ($a_int-$b_int)+($a_micro-$b_micro);
        } elseif ($a_micro<$b_micro) {
           return ($b_int-$a_int)+($b_micro-$a_micro);
        } else {
          return 0;
        }
     } else { // $a_int<$b_int
        return ($b_int-$a_int)+($b_micro-$a_micro);
     }
  }

$start_time = microtime();



?>