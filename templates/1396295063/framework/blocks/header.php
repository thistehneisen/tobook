<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//define basic parameters
$base_url = $this->baseurl;

//load parameters from template admin panel
$search = $this->countModules('search');
$logo_type = $this->params->get('logo_type');
$logo_text = $this->params->get('logo_text');
$site_slogan = $this->params->get('site_slogan');
$logo_image = $this->params->get('logo_image');
$logo_image_width = $this->params->get('logo_image_width');
$logo_image_height = $this->params->get('logo_image_height');
$logo_image_margin = $this->params->get('logo_image_margin');
$main_menu = '<jdoc:include type="modules" name="mainmenu" style="" />';

//change id of logo heading
$logo_id='logo-image';

if($logo_type =='text') {
$logo_id = 'logo-text';
}
?>

<header id="header" class="container">

<div class="logo">                      	
<h1 id="<?php echo $logo_id; ?>"><a href="<?php echo $base_url; ?>"><?php echo htmlspecialchars($logo_text); ?></a></h1>
<?php if($site_slogan !=''){ ?><span id="site-slogan"><?php echo htmlspecialchars($site_slogan); ?></span> <?php } ?>                        
</div>
<?php if($search){?>
<div class="search-form">
<jdoc:include type="modules" name="search"  />
</div>
<div class="clear"></div>
<?php } ?>
<div class="header-content">	
<nav id="navigation">
<?php echo $main_menu; ?>
<div class="clear"></div>
</nav>
</div>
<div class="clear"></div>

</header>