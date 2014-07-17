<?php

defined('_JEXEC') or die;

$caption         = $this->params->get ('caption');
$menu            = $this->params->get ('menu');
$headHeigh	     = $this->params->get('headHeigh');
$sliders_thumb1 	= $this->params->get('sliders_thumb1', '' );
$sliders_thumb2 	= $this->params->get('sliders_thumb2', '' );
$sliders_thumb3 	= $this->params->get('sliders_thumb3', '' );
$sliders_thumb4 	= $this->params->get('sliders_thumb4', '' );
$sliders_thumb5 	= $this->params->get('sliders_thumb5', '' );
$sliders_thumb6 	= $this->params->get('sliders_thumb6', '' );
$sliders_texts1 	= $this->params->get('sliders_texts1', '' );
$sliders_texts2 	= $this->params->get('sliders_texts2', '' );
$sliders_texts3 	= $this->params->get('sliders_texts3', '' );
$sliders_texts4 	= $this->params->get('sliders_texts4', '' );
$sliders_texts5 	= $this->params->get('sliders_texts5', '' );
$sliders_texts6 	= $this->params->get('sliders_texts6', '' );
$sliders_text1 	= $this->params->get('sliders_text1', '' );
$sliders_text2 	= $this->params->get('sliders_text2', '' );
$sliders_text3 	= $this->params->get('sliders_text3', '' );
$sliders_text4 	= $this->params->get('sliders_text4', '' );
$sliders_text5 	= $this->params->get('sliders_text5', '' );
$sliders_text6 	= $this->params->get('sliders_text6', '' );

if ($sliders_thumb1 || $sliders_thumb2 || $sliders_thumb3 || $sliders_thumb4 || $sliders_thumb5) {
// use images from template manager
} else {
// use default images
$sliders_thumb1 = $this->baseurl . '/templates/' . $this->template . '/header/header1.jpg';
$sliders_thumb2 = $this->baseurl . '/templates/' . $this->template . '/header/header2.jpg';
}

?>

<?php if (($this->countModules('header') && $slides == 2) || ($slides == 1)): ?>


<section id="slider" class="container">
<div class="slider-content">	
<div class="container">

<div id="wowslider-container" class="">

<div class="ws_images">
<ul>
<?php if ($sliders_thumb1): ?>
<li >
<img src="<?php echo $sliders_thumb1; ?>"  alt="" id="wows1_0" />
</li>
<?php endif;?>
<?php if ($sliders_thumb2): ?>
<li >
<img src="<?php echo $sliders_thumb2; ?>"  alt="" id="wows1_1" />
</li>
<?php endif;?>
<?php if ($sliders_thumb3): ?>
<li >
<img src="<?php echo $sliders_thumb3; ?>"  alt="" id="wows1_2" />
</li>
<?php endif;?>
<?php if ($sliders_thumb4): ?>
<li >
<img src="<?php echo $sliders_thumb4; ?>"  alt="" id="wows1_3" />
</li>
<?php endif;?>
<?php if ($sliders_thumb5): ?>
<li >
<img src="<?php echo $sliders_thumb5; ?>" alt="" id="wows1_4" />
</li>
<?php endif;?>
</ul>
</div>

</div>
<script type = "text/javascript" src = "<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/header/js/wowslider.js"></script>
<script type = "text/javascript" src = "<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/header/js/blinds.js"></script>	

<script type="text/javascript">
(function($) {
$(window).load(function(){
$("#wowslider-container").wowSlider({
effect:"blinds",
duration:900,
delay:4000,
width:960,
height:400,
cols:6,
autoPlay:true,
stopOnHover:true,
loop:false,
bullets:false,
caption:true,
controls:true,
captionEffect:"slide",
logo:"/templates/tc_theme1/header/images/loading_light.gif",
images:0
});
});
})(jQuery);
</script>
</div>
</div>     
</section>

<?php endif; ?>	
<!-- No Slides -->
<?php if ($this->countModules('header') && $slides == 0): ?>
<section id="slider" class="container">
<div class="slider-content">	
<jdoc:include type="modules" name="header" style="none" />
</div>     
</section>
<?php endif; ?>		
<div class="clear"></div>       

