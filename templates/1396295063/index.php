<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//define path
$base_url = $this->baseurl;
$tpl_name = $this->template;
$css_url = ''.$base_url.'/templates/'.$tpl_name.'/css/';
$scripts_url = ''.$base_url.'/templates/'.$tpl_name.'/scripts/';
$framework = 'templates/'.$tpl_name.'/framework/';

require_once ($framework.'blocks/header_include.php');
?>

<body>
<div id="page-container">  	

<!-- start page top container -->
<div id="page-container-top">
<?php require_once ($framework.'blocks/header.php');?>                 
<?php 				
require_once ($framework.'blocks/slider.php');			
require_once ($framework.'blocks/feature.php'); 
?>                   
</div>
<!-- //end page top container -->        

<!-- start page middle container -->
<div id="page-container-middle">
<?php 
require_once ($framework.'blocks/top.php');
require_once ($framework.'blocks/main.php');
require_once ($framework.'blocks/info.php'); 
?>
</div>
<!-- //end page middle container -->        

<!-- start page bottom container -->
<div id="page-container-bottom">
<?php 
require_once ($framework.'blocks/bottom.php'); 
require_once ($framework.'blocks/footer.php');
?>
</div>
<!-- //end page bottom container -->         

</div>   


</body>
</html>