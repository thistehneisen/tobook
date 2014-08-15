<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
	?>
	
	<div id="formstyle">
	<form id="frmAddbooking" class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionStyle&amp;as_pf=<?php echo $as_pf; ?>">
	<fieldset class="fieldset white">
	<input type="hidden" name="form_style" value="1">
	<legend>Tyyli</legend>
					<?php if (isset($tpl['arr'][0]) && count($tpl['arr'][0]) > 0 ) { ?>
							<input type="hidden" name="id" value="<?php echo $tpl['arr'][0]['id']; ?>">
					<?php } ?>
					<p><label class="title">Logo (url)</label><input type="text" name="logo" id="f_logo" class="pj-form-field w250" value="<?php echo isset($tpl['arr'][0]['logo']) ? $tpl['arr'][0]['logo'] : ''; ?>" /></p>
					<p><label class="title"><?php __('banner')?></label><input type="text" name="banner" id="banner" class="pj-form-field w250" value="<?php echo isset($tpl['arr'][0]['banner']) ? $tpl['arr'][0]['banner'] : ''; ?>" /></p>
					<p><label class="title">Väri</label><input type="text" name="color" id="color" class="pj-form-field w250" value="<?php echo isset($tpl['arr'][0]['color']) ? $tpl['arr'][0]['color'] : ''; ?>" /></p>

<p><label class="title">Headings color:</label><input type="text" name="color" id="color" class="pj-form-field w250" value="<?php echo isset($tpl['arr'][0]['headings']) ? $tpl['arr'][0]['headings'] : ''; ?>" /></p>
					<p><label class="title">Tausta</label><input type="text" name="background" id="background" class="pj-form-field w250" value="<?php echo isset($tpl['arr'][0]['background']) ? $tpl['arr'][0]['background'] : ''; ?>" /></p>
					<?php 
					$font_name = array(
						"Aclonica" => "Aclonica",
						"Allan" => "Allan",
						"Annie+Use+Your+Telescope" => "Annie Use Your Telescope",
						"Anonymous+Pro" => "Anonymous Pro",
						"Allerta+Stencil" => "Allerta Stencil",
						"Allerta" => "Allerta",
						"Amaranth" => "Amaranth",
						"Anton" => "Anton",
						"Architects+Daughter" => "Architects Daughter",
						"Arimo" => "Arimo",
						"Artifika" => "Artifika",
						"Arvo" => "Arvo",
						"Asset" => "Asset",
						"Astloch" => "Astloch",
						"Bangers" => "Bangers",
						"Bentham" => "Bentham",
						"Bevan" => "Bevan",
						"Bigshot+One" => "Bigshot One",
						"Bowlby+One" => "Bowlby One",
						"Bowlby+One+SC" => "Bowlby One SC",
						"Brawler" => "Brawler ",
						"Buda" => "Buda",
						"Cabin" => "Cabin",
						"Calligraffitti" => "Calligraffitti",
						"Candal" => "Candal",
						"Cantarell" => "Cantarell",
						"Cardo" => "Cardo",
						"Carter One" => "Carter One",
						"Caudex" => "Caudex",
						"Cedarville+Cursive" => "Cedarville Cursive",
						"Cherry+Cream+Soda" => "Cherry Cream Soda",
						"Chewy" => "Chewy",
						"Coda" => "Coda",
						"Coming+Soon" => "Coming Soon",
						"Copse" => "Copse",
						"Corben" => "Corben",
						"Cousine" => "Cousine",
						"Covered+By+Your+Grace" => "Covered By Your Grace",
						"Crafty+Girls" => "Crafty Girls",
						"Crimson+Text" => "Crimson Text",
						"Crushed" => "Crushed",
						"Cuprum" => "Cuprum",
						"Damion" => "Damion",
						"Dancing+Script" => "Dancing Script",
						"Dawning+of+a+New+Day" => "Dawning of a New Day",
						"Didact+Gothic" => "Didact Gothic",
						"Droid+Sans" => "Droid Sans",
						"Droid+Sans+Mono" => "Droid Sans Mono",
						"Droid+Serif" => "Droid Serif",
						"EB+Garamond" => "EB Garamond",
						"Expletus+Sans" => "Expletus Sans",
						"Fontdiner+Swanky" => "Fontdiner Swanky",
						"Forum" => "Forum",
						"Francois+One" => "Francois One",
						"Geo" => "Geo",
						"Give+You+Glory" => "Give You Glory",
						"Goblin+One" => "Goblin One",
						"Goudy+Bookletter+1911" => "Goudy Bookletter 1911",
						"Gravitas+One" => "Gravitas One",
						"Gruppo" => "Gruppo",
						"Hammersmith+One" => "Hammersmith One",
						"Holtwood+One+SC" => "Holtwood One SC",
						"Homemade+Apple" => "Homemade Apple",
						"Inconsolata" => "Inconsolata",
						"Indie+Flower" => "Indie Flower",
						"IM+Fell+DW+Pica" => "IM Fell DW Pica",
						"IM+Fell+DW+Pica+SC" => "IM Fell DW Pica SC",
						"IM+Fell+Double+Pica" => "IM Fell Double Pica",
						"IM+Fell+Double+Pica+SC" => "IM Fell Double Pica SC",
						"IM+Fell+English" => "IM Fell English",
						"IM+Fell+English+SC" => "IM Fell English SC",
						"IM+Fell+French+Canon" => "IM Fell French Canon",
						"IM+Fell+French+Canon+SC" => "IM Fell French Canon SC",
						"IM+Fell+Great+Primer" => "IM Fell Great Primer",
						"IM+Fell+Great+Primer+SC" => "IM Fell Great Primer SC",
						"Irish+Grover" => "Irish Grover",
						"Irish+Growler" => "Irish Growler",
						"Istok+Web" => "Istok Web",
						"Josefin+Sans" => "Josefin Sans Regular 400",
						"Josefin+Slab" => "Josefin Slab Regular 400",
						"Judson" => "Judson",
						"Jura" => " Jura Regular",
						"Just+Another+Hand" => "Just Another Hand",
						"Just+Me+Again+Down+Here" => "Just Me Again Down Here",
						"Kameron" => "Kameron",
						"Kenia" => "Kenia",
						"Kranky" => "Kranky",
						"Kreon" => "Kreon",
						"Kristi" => "Kristi",
						"La+Belle+Aurore" => "La Belle Aurore",
						"Lato" => "Lato",
						"League+Script" => "League Script",
						"Lekton" => " Lekton ",
						"Limelight" => " Limelight ",
						"Lobster" => "Lobster",
						"Lobster Two" => "Lobster Two",
						"Lora" => "Lora",
						"Love+Ya+Like+A+Sister" => "Love Ya Like A Sister",
						"Loved+by+the+King" => "Loved by the King",
						"Luckiest+Guy" => "Luckiest Guy",
						"Maiden+Orange" => "Maiden Orange",
						"Mako" => "Mako",
						"Maven+Pro" => " Maven Pro",
						"Meddon" => "Meddon",
						"MedievalSharp" => "MedievalSharp",
						"Megrim" => "Megrim",
						"Merriweather" => "Merriweather",
						"Metrophobic" => "Metrophobic",
						"Michroma" => "Michroma",
						"Miltonian Tattoo" => "Miltonian Tattoo",
						"Miltonian" => "Miltonian",
						"Modern Antiqua" => "Modern Antiqua",
						"Monofett" => "Monofett",
						"Molengo" => "Molengo",
						"Mountains of Christmas" => "Mountains of Christmas",
						"Muli" => "Muli Regular",
						"Neucha" => "Neucha",
						"Neuton" => "Neuton",
						"News+Cycle" => "News Cycle",
						"Nixie+One" => "Nixie One",
						"Nobile" => "Nobile",
						"Nova+Cut" => "Nova Cut",
						"Nova+Flat" => "Nova Flat",
						"Nova+Mono" => "Nova Mono",
						"Nova+Oval" => "Nova Oval",
						"Nova+Round" => "Nova Round",
						"Nova+Script" => "Nova Script",
						"Nova+Slim" => "Nova Slim",
						"Nova+Square" => "Nova Square",
						"Nunito" => " Nunito Regular",
						"OFL+Sorts+Mill+Goudy+TT" => "OFL Sorts Mill Goudy TT",
						"Old+Standard+TT" => "Old Standard TT",
						"Open+Sans" => "Open Sans regular",
						"Orbitron" => "Orbitron Regular (400)",
						"Oswald" => "Oswald",
						"Over+the+Rainbow" => "Over the Rainbow",
						"Reenie+Beanie" => "Reenie Beanie",
						"Pacifico" => "Pacifico",
						"Patrick+Hand" => "Patrick Hand",
						"Paytone+One" => "Paytone One",
						"Permanent+Marker" => "Permanent Marker",
						"Philosopher" => "Philosopher",
						"Play" => "Play",
						"Playfair+Display" => " Playfair Display ",
						"Podkova" => " Podkova ",
						"PT+Sans" => "PT Sans",
						"PT+Sans+Narrow" => "PT Sans Narrow",
						"PT+Serif" => "PT Serif",
						"PT+Serif Caption" => "PT Serif Caption",
						"Puritan" => "Puritan",
						"Quattrocento" => "Quattrocento",
						"Quattrocento+Sans" => "Quattrocento Sans",
						"Radley" => "Radley",
						"Redressed" => "Redressed",
						"Rock+Salt" => "Rock Salt",
						"Rokkitt" => "Rokkitt",
						"Ruslan+Display" => "Ruslan Display",
						"Schoolbell" => "Schoolbell",
						"Shadows+Into+Light" => "Shadows Into Light",
						"Shanti" => "Shanti",
						"Sigmar+One" => "Sigmar One",
						"Six+Caps" => "Six Caps",
						"Slackey" => "Slackey",
						"Smythe" => "Smythe",
						"Special+Elite" => "Special Elite",
						"Stardos+Stencil" => "Stardos Stencil",
						"Sue+Ellen+Francisco" => "Sue Ellen Francisco",
						"Sunshiney" => "Sunshiney",
						"Swanky+and+Moo+Moo" => "Swanky and Moo Moo",
						"Syncopate" => "Syncopate",
						"Tangerine" => "Tangerine",
						"Tenor+Sans" => " Tenor Sans ",
						"Terminal+Dosis+Light" => "Terminal Dosis Light",
						"The+Girl+Next+Door" => "The Girl Next Door",
						"Tinos" => "Tinos",
						"Ubuntu" => "Ubuntu",
						"Ultra" => "Ultra",
						"Unkempt" => "Unkempt",
						"UnifrakturMaguntia" => "UnifrakturMaguntia",
						"Varela" => "Varela",
						"Varela Round" => "Varela Round",
						"Vibur" => "Vibur",
						"Vollkorn" => "Vollkorn",
						"VT323" => "VT323",
						"Waiting+for+the+Sunrise" => "Waiting for the Sunrise",
						"Wallpoet" => "Wallpoet",
						"Walter+Turncoat" => "Walter Turncoat",
						"Wire+One" => "Wire One",
						"Yanone+Kaffeesatz" => "Yanone Kaffeesatz",
						"Yeseva+One" => "Yeseva One",
						"Zeyada" => "Zeyada",
						);
					?>
					<p><label class="title">Fontti</label>
						<select name="font" id="font" class="pj-form-field w250">
							<option value="">Select font</option>
							<?php foreach ($font_name as $k => $v ) { ?>
							<option value="<?php echo $k; ?>" <?php echo isset($tpl['arr'][0]['font']) && $tpl['arr'][0]['font'] == $k ? 'selected="selected"' : ''; ?>><?php echo $v; ?></option>
							<?php } ?>
						</select>
					</p>
					<p><label class="title">Custom CSS</label><textarea name="message" id="message" class="pj-form-field w400 h200"><?php echo isset($tpl['arr'][0]['message']) ? $tpl['arr'][0]['message'] : ''; ?></textarea></p>
					<p>
						<label class="title">&nbsp;</label>
						<input class="pj-button" type="submit" value="Tallenna" />
					</p>
				</fieldset>
			</form>
		</div>
		<?php 
}
?>
