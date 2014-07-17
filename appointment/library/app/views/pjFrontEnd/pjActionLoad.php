<?php
$cid = (int) $controller->getForeignId();
$front_app = __('front_app', true);
?>
<div class="asContainer">
	<div id="asContainer_<?php echo $cid; ?>" class="asContainerInner"></div>
</div>
<script type="text/javascript">
var pjQ = pjQ || {},
	AppScheduler_<?php echo $cid; ?>;
(function () {
	"use strict";
	var isSafari = /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor),

	loadCssHack = function(url, callback){
		var link = document.createElement('link');
		link.type = 'text/css';
		link.rel = 'stylesheet';
		link.href = url;

		document.getElementsByTagName('head')[0].appendChild(link);

		var img = document.createElement('img');
		img.onerror = function(){
			if (callback && typeof callback === "function") {
				callback();
			}
		};
		img.src = url;
	},
	loadRemote = function(url, type, callback) {
		if (type === "css" && isSafari) {
			loadCssHack.call(null, url, callback);
			return;
		}
		var _element, _type, _attr, scr, s, element;
		
		switch (type) {
		case 'css':
			_element = "link";
			_type = "text/css";
			_attr = "href";
			break;
		case 'js':
			_element = "script";
			_type = "text/javascript";
			_attr = "src";
			break;
		}
		
		scr = document.getElementsByTagName(_element);
		s = scr[scr.length - 1];
		element = document.createElement(_element);
		element.type = _type;
		if (type == "css") {
			element.rel = "stylesheet";
		}
		if (element.readyState) {
			element.onreadystatechange = function () {
				if (element.readyState == "loaded" || element.readyState == "complete") {
					element.onreadystatechange = null;
					if (callback && typeof callback === "function") {
						callback();
					}
				}
			};
		} else {
			element.onload = function () {
				if (callback && typeof callback === "function") {
					callback();
				}
			};
		}
		element[_attr] = url;
		s.parentNode.insertBefore(element, s.nextSibling);
	},
	loadScript = function (url, callback) {
		loadRemote.call(null, url, "js", callback);
	},
	loadCss = function (url, callback) {
		loadRemote.call(null, url, "css", callback);
	},
	options = {
		server: "<?php echo PJ_INSTALL_URL; ?>",
		folder: "<?php echo PJ_INSTALL_FOLDER; ?>",
		cid: <?php echo $cid; ?>,
		seoUrl: <?php echo (int) $tpl['option_arr']['o_seo_url']; ?>,
		locale: <?php echo isset($_GET['locale']) && (int) $_GET['locale'] > 0 ? (int) $_GET['locale'] : 'null'; ?>,
		layout: <?php echo isset($_GET['layout']) && in_array($_GET['layout'], $controller->getLayoutRange()) ? (int) $_GET['layout'] : (int) $tpl['option_arr']['o_layout']; ?>,
		fields: <?php echo pjAppController::jsonEncode(__('front_app', true)); ?>,
		firstDate: "<?php echo $tpl['first_working_date']; ?>"
	};
	loadScript("<?php echo PJ_INSTALL_URL . PJ_LIBS_PATH; ?>pjQ/pjQuery.min.js", function () {
		loadScript("<?php echo PJ_INSTALL_URL . PJ_LIBS_PATH; ?>pjQ/pjQuery.validate.min.js", function () {
			loadScript("<?php echo PJ_INSTALL_URL . PJ_LIBS_PATH; ?>pjQ/pjQuery-ui-1.9.2.custom.min.js", function () {
				loadScript("<?php echo PJ_INSTALL_URL . PJ_LIBS_PATH; ?>pjQ/pjQuery.ba-hashchange.min.js", function () {
					loadScript("<?php echo PJ_INSTALL_URL . PJ_JS_PATH; ?>pjAppScheduler.js", function () {
						AppScheduler_<?php echo $cid; ?> = new AppScheduler(options);
						loadCss("<?php echo PJ_INSTALL_URL . PJ_LIBS_PATH; ?>pjQ/css/jquery-ui-1.9.2.custom.min.css");
					});
				});
			});
		});
	});
})();
</script>