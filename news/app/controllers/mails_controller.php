<?php

function str_replace2($s, $r, $t) {
	$t = str_replace(str_replace(array("[", "]"), array("%5B", "%5D"), $s), $s, $t);
	return str_replace($s, $r, $t);
}

class MailsController extends AppController {

	var $name = 'Mails';
	var $uses = array("Mail", "CategoriesSubscriber", "Subscriber", "Campaign");
	var $cr = false;

	function index() {
		$this->Mail->recursive = 0;
		$this->set('mails', $this->paginate("Mail", array('Mail.campaign_id' => 0)));
	}

	function feed() {
		if (Configure::read("Settings.norss") != "1") {
			$this->autoLayout = false;
			$this->RequestHandler->respondAs('xml');
			$this->Mail->recursive = 0;

			$conds = array(' Mail.send_on< Now()', 'Mail.campaign_id' => 0, 'Mail.status >' => 0, 'Mail.campaign_id' => 0, 'Mail.private !=' => 1);
			if (Configure::read("Settings.stoppednl") != "1") {
				$conds['Mail.status !='] = 3;
			}

			$this->set('mails', $this->Mail->find("all", array("limit" => Configure::read("Settings.rssitems") + 0, "fields" => array("id", "subject", "send_on"), "conditions" => $conds)));
		} else {
			$this->cakeError('error404');
		}
	}

	function campaign($id = 0) {
		if ($id == 0) {
			$this->redirect(array('action' => 'index'));
		}
		$this->Mail->recursive = 0;
		$this->paginate["order"] = "delay asc";
		$this->set('mails', $this->paginate("Mail", array('Mail.campaign_id' => $id)));
		$this->set('campaign', $this->Campaign->read(null, $id));
		$ids = $this->Mail->find("list", array("conditions" => array("campaign_id" => $id)));
		$ids = array_keys($ids);
		$ids[] = -1;
		$tot = $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $ids)));
		$tot2 = $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $ids), "fields" => "count(DISTINCT `Subscriber`.`id`) as count"));

		$unsub = $this->Mail->find("first", array('fields' => 'SUM(Mail.unsubscribed) as unsubscribed', "conditions" => array("Mail.id" => $ids)));
		if (isset($unsub[0]["unsubscribed"])) {
			$this->set('unsub', $unsub[0]["unsubscribed"]);
		} else {
			$this->set('unsub', 0);
		}
		$sendtof = $this->Mail->find("first", array('fields' => 'SUM(Mail.sendtof) as sendtoft', "conditions" => array("Mail.id" => $ids)));
		if (isset($sendtof[0]["sendtoft"])) {
			$this->set('sendtof', $sendtof[0]["sendtoft"]);
		} else {
			$this->set('sendtof', 0);
		}

		$this->set('recipient', array(
			"total" => $tot,
			"total2" => $tot2,
			"sent" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $ids, "sent" => 1, "read" => 0))),
			"read" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $ids, "sent" => 1, "read" => 1))),
			"failed" => $this->Mail->Recipient->find("count", array("conditions" => array(array("mail_id" => $ids, "sent" => 0), array("OR" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL")))))
		));
		$this->set('chartdata', $this->__makeChart($this->Mail->Recipient->query("SELECT count( * ) as c , YEAR( `send_date` ) as y , MONTH( `send_date` ) as m , DAY( `send_date` ) as d FROM `recipients` WHERE sent=1 AND mail_id IN (" . implode(",", $ids) . ") GROUP BY YEAR( `send_date` ) , MONTH( `send_date` ) , DAY( `send_date` ) ORDER BY `send_date`")));

		$this->set('chartdata2', $this->__makeChart($this->Mail->Recipient->query("SELECT count( * ) as c , YEAR( `read_date` ) as y , MONTH( `read_date` ) as m , DAY( `read_date` ) as d
FROM `recipients` WHERE sent=1 AND `read`=1 AND mail_id IN (" . implode(",", $ids) . ")
GROUP BY YEAR( `read_date` ) , MONTH( `read_date` ) , DAY( `read_date` )
ORDER BY `read_date`")));
	}

	function beforeFilter() {
		$this->Auth->allow('track', 'preview', 'sendAll', 'parallelSend', 'feed', 'image');
		parent::beforeFilter();
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid mail', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('mail', $this->Mail->read(null, $id));
	}

	function preview($id = null, $subsci = null, $open = null) {
		$this->set('id', $id);
		if (!$id) {
			$this->Session->setFlash(__('Invalid mail', true));
			$this->redirect(array('action' => 'index'));
		}
		if ($id > 0) {
			$mail = $this->Mail->read(null, $id);
			if (!isset($mail["Mail"]["status"])) {
				$this->cakeError('error404');
				return;
			}

			$mail["read"] = $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 1)));

			if ($mail["Mail"]["status"] == 0) {
				$content = $this->loadcontent($mail);
			} else {
				$content = $mail["Mail"]["final_html"];
			}

			if (count($this->Session->read('Auth.User')) == 0 && $mail["Mail"]["status"] == 0) {
				$this->cakeError('error404');
				return;
			}

			if (substr($this->params['url']['url'], 0, 4) == "show") {
				$subsci = split("-", $subsci);
				$subsc = $subsci[0];
				if ($open == null) {
					$this->Mail->Recipient->openMail($id, $subsc);
				}

				$subscriber = $this->Mail->Recipient->Subscriber->read(null, $subsc);

				if (isset($subsci[1]) && $subscriber["Subscriber"]["unsubscribe_code"] == $subsci[1]) {
					$content = $this->Mail->addInfos($content, $subscriber["Subscriber"]);
				} else {
					if (count($subsci) > 1 && $subsci[0] == 0 && $subsci[1] == "GUEST" && $mail["Mail"]["private"] == "0") {

						$content = $this->Mail->addInfos($content, array("form_id" => 0, "id" => 0, "mail_adresse" => "guest@guest.com", "first_name" => "Guest", "last_name" => "User", "custom1" => "custom1", "custom2" => "custom2", "custom3" => "custom3", "custom4" => "custom4", "unsubscribe_code" => "GUEST"));
					} else {
						if (count($this->Session->read('Auth.User')) == 0) {
							$this->cakeError('error404');
							return;
						}
					}
				}
			}
		} else {
			if ($this->Auth->user() !== false) {
				$mail["Mail"]["id"] = "";
				$mail["Mail"]["data"] = "";
				$mail["Mail"]["subject"] = "Preview";
				$mail["Link"] = array();
				$a = $this->Mail->Template->read(null, $id * -1);
				$mail["Template"] = $a["Template"];
				$f = myunserialize($mail["Template"]["fields_array"]);
				if ($f !== false) {
					$cont = array();
					foreach ($f as $key => $value) {
						if (!is_array($value)) {
							if (strpos($value, "_imgchooser") !== false) {
								$cont[str_replace("_imgchooser", "", $value)] = "/uploads/logo.png";
							} else {
								if (strpos($value, "_lite") !== false) {
									$cont[str_replace("_lite", "",
										$value)] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
								} else {
									if (strpos($value, "_raw") !== false) {
										$cont[str_replace("_raw", "",
											$value)] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
									} else {
										if ($value[0] != strtoupper($value[0])) {
											$cont[$value] = "Lorem ipsum";
										} else {
											$cont[$value] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
										}
									}
								}
							}
						} else {
							$cont2 = array();
							foreach ($value as $value2) {
								if (strpos($value2, "_imgchooser") !== false) {
									$cont2[str_replace("_imgchooser", "", $value2)] = "/uploads/logo.png";
								} else {
									if (strpos($value2, "_lite") !== false) {
										$cont2[str_replace("_lite", "",
											$value2)] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
									} else {
										if (strpos($value2, "_raw") !== false) {
											$cont2[str_replace("_raw", "",
												$value2)] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
										} elseif ($value2[0] != strtoupper($value2[0])) {
											$cont2[$value2] = "Lorem ipsum";
										} else {
											$cont2[$value2] = "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.";
										}
									}
								}
							}
							for ($index = 0; $index < 3; $index++) {
								$cont[$key][] = $cont2;
							}
						}
					}

					$mail["Mail"]["Data"] = $cont;
				}
				$content = $this->loadcontent($mail);
			}
		}

		$this->set('content', $content);
		$this->set('mail', $mail);
		$this->layout = "ajax";
	}

    function image($path)
    {
        $data= base64_decode(str_pad(strtr($path, '-_', '+/'), strlen($path) % 4, '=', STR_PAD_RIGHT));
        parse_str($data, $_GET);
        include '../webroot/thumb.php';
        die();
    }
	function add($id = null) {
		if (!empty($this->data)) {
			$this->Mail->create();
			$this->data["Mail"]["last_step"] = 1;
			$this->Mail->Category->set($this->data);
			if ($this->Mail->Category->validates()) {
				$this->data["Mail"]["type"] = 1;
				if ($this->Mail->save($this->data)) {

					$this->redirect(array('action' => 'step', $this->Mail->id, 2));
				} else {
					$this->Session->setFlash(__('The mail could not be saved. Please, try again.', true));
				}
			} else {
				$this->Mail->set($this->data);
				$this->Mail->validates();
			}
		}
		if ($id != NULL) {
			$this->data["Mail"]["campaign_id"] = $id;
		} else {
			if (!isset($this->data["Mail"]["campaign_id"])) {
				$this->data["Mail"]["campaign_id"] = 0;
			}
		}
		$this->data["Mail"]["last_step"] = 0;
		$configurations = $this->Mail->Configuration->find('list');
		$categories = $this->Mail->Category->find('list', array('fields' => array('Category.fullname')));
		$this->set(compact('configurations', 'categories'));
		$this->render("step1");
	}

	function __strip_html_tags($text) {

		$text = preg_replace(
			array(
				// Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',
				// Add line breaks before & after blocks

				'@<((br)|(hr))@iu',
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			), array(
			' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
			"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
			"\n\$0", "\n\$0",
		), $text);

		return trim(preg_replace('/^[ \t]*[\r\n]+/m', "\n", str_replace("&nbsp;", " ", htmlspecialchars_decode(strip_tags($text)))));
	}

	function step($id = null, $step = 1, $loadtext = 0) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid mail', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->data["Mail"]["step"] > $this->data["Mail"]["last_step"]) {
				$this->data["Mail"]["last_step"] = $this->data["Mail"]["step"];
			}
			$this->Mail->Category->set($this->data);
			if ($this->Mail->Category->validates()) {
				if ($this->Mail->save($this->data)) {
					if (isset($_POST["loadtext"])) {
						$this->redirect(array('action' => 'step', $this->Mail->id, 3, 1));
					}
					$this->redirect(array('action' => 'step', $this->Mail->id, $this->data["Mail"]["step"] + 1));
				} else {
					$this->Session->setFlash(__('The mail could not be saved. Please, try again.', true));
				}
			} else {
				$this->Mail->set($this->data);
				$this->Mail->validates();
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Mail->read(null, $id);
			$this->data["Mail"]["step"] = $step;
			if ($loadtext == 1 && $step == 3) {
				$this->data["Mail"]["content_text"] = $this->__strip_html_tags($this->loadcontent($this->data));
			}
			if ($this->data["Mail"]["status"] != 0) {
				$this->redirect(array('action' => 'sendstatus', $this->Mail->id));
			}
		}
		if ($step == 2) {
			$templates_full = $this->Mail->Template->find('all', array("fields" => array("id", "name", "preview")));
			$this->set(compact('templates_full'));
		}

		if ($step == 5) {

			if (preg_match_all('/href=["\']([^"\']+)["\']/i', $this->loadcontent($this->data), $links, PREG_PATTERN_ORDER)) {
				$linkslist = array_unique($links[1]);
				$links = array();
				foreach ($linkslist as $value) {
					if ($value[0] != "#" && substr($value, 0, 7) != "mailto:") {
						$links[] = $value;
					}
				}
				$this->set("links", $links);
			}
		}
		$configurations = $this->Mail->Configuration->find('list');

		$categories = $this->Mail->Category->find('list', array('fields' => array('Category.fullname')));
		$templates = $this->Mail->Template->find('list');
		$types = array(__("Html and Text", true), __("Html only", true), __("Text only", true));
		$this->set(compact('configurations', 'categories', 'types', 'templates'));
		$this->render("step" . $step);
	}

	function __get_web_page($url) {
		$options = array(
			CURLOPT_RETURNTRANSFER => true, // return web page
			CURLOPT_HEADER => false, // don't return headers
			CURLOPT_FOLLOWLOCATION => true, // follow redirects
			CURLOPT_ENCODING => "", // handle all encodings
			CURLOPT_USERAGENT => "spider", // who am i
			CURLOPT_AUTOREFERER => true, // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
			CURLOPT_TIMEOUT => 120, // timeout on response
			CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg'] = $errmsg;
		$header['content'] = $content;

		return $content;
	}

	function loadcontent($input) {
		$f = myunserialize($input["Template"]["fields_array"]);

		$da = array();
		if (isset($input["Mail"]["Data"])) {
			$da = $input["Mail"]["Data"];
		}
		$input["Mail"]["Data"] = array();
		if ($f !== false) {
			foreach ($f as $key => $value) {
				if (is_array($value)) {
					if (isset($da[$key])) {
						foreach ($da[$key] as $dacont) {
							$arr = array();
							foreach ($value as $subkey) {
								$subkey = str_replace(array("_imgchooser", "_lite", "_raw"), "", $subkey);
								$arr[$subkey] = isset($dacont[$subkey]) ? $dacont[$subkey] : "";
							}
							$input["Mail"]["Data"][$key][] = $arr;
						}
					} else {
						$input["Mail"]["Data"][$key] = array();
					}
				} else {
					$value = str_replace(array("_imgchooser", "_lite", "_raw"), "", $value);
					$input["Mail"]["Data"][$value] = isset($da[$value]) ? $da[$value] : "";
				}
			}
		}

		preg_match_all("/{([a-z]*\s?)}/i", $input["Template"]["content"], $matches);

		$html_content = $input['Template']['content'];
		preg_match_all('%(.*[^>]+>|[^<\/.*>])%iU', $html_content, $tags, PREG_PATTERN_ORDER);

		foreach ($tags[1] as $t) {
			preg_match_all('/url\([\\\']?([^\)\\\']*)[\\\']?\)/i', $t, $imagescss, PREG_PATTERN_ORDER);

			if (count($imagescss[1]) > 0 && strpos($t, "background=") == false) {
				$tnew = str_replace('style=', 'background="' . $imagescss[1][0] . '" style=', $t);

				$html_content = str_replace($t, $tnew, $html_content);
			}
		}
		preg_match_all('/\[load ([^\]]*)]/i', $html_content, $load, PREG_PATTERN_ORDER);
		for ($index = 0; $index < count($load[0]); $index++) {
			preg_match_all('/([^= ]*)[ ]?=[ ]?["]?((?<=")[^"]*|[^ ]*)/i', $load[1][$index], $param, PREG_PATTERN_ORDER);

			$paras = array_combine($param[1], $param[2]);

			if (isset($paras["url"])) {
				preg_match('/([a-z]*[:]\\/\\/[a-z0-9.]*([:][0-9]*)?)(\\/[^\\/^\\?]*)*/i', $paras["url"], $murls);
				$surl = substr($murls[0], 0, strlen($murls[0]) - strlen($murls[3]));
				$data = $this->__get_web_page($paras["url"]);
				if (!isset($paras["script"])) {
					$data = preg_replace('/\<script.*?\<\/script\>/ism'
						, ' '
						, $data);
				}
				//ABSOLUTE IMAGE PATH
				$data = preg_replace_callback(
					'/(src|href)([^= ]*)[ ]?=[ ]?["]?[\']?((?<=")[^"]*|(?<=\')[^\']*|[^ ^>]*)["]?[\']?/i', create_function(
					// single quotes are essential here,
					// or alternative escape all $ as \$
						'$matches', 'preg_match(\'/[a-z]*[:]/i\', $matches[3], $matche);if(empty($matche)){if($matches[3][0]=="/"){return str_replace($matches[3],\'' . addslashes($murls[1]) . '\'.$matches[3],$matches[0]);}else{return str_replace($matches[3],\'' . addslashes($surl) . '\'.$matches[3],$matches[0]);}}else{return $matches[0];}'
					), $data
				);
				$html_content = str_replace($load[0][$index], $data, $html_content);
			}
		}

		$pattern = "/style[^>]*>(.*?)<\/style/s";

		$html_content = $this->Mail->updateUrls(preg_replace('/url\([\\\']?([^\)\\\'{]*)[\\\']?\)/i', 'url({$baseurl}\1)', preg_replace('/background=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?/i', 'background="{$baseurl}\1"', $html_content)), $input["Mail"]["id"], $input["Mail"]["Data"], $input["Mail"]["subject"]);
		preg_match_all('/\[image([^\]]*)]/i', $html_content, $imgtags, PREG_PATTERN_ORDER);
		foreach ($imgtags[1] as $im) {
			preg_match_all('/([^= ]*)[ ]?=[ ]?([^ ]*)/i', $im, $imgtags_settings, PREG_PATTERN_ORDER);
			$paras = array_combine($imgtags_settings[1], $imgtags_settings[2]);
			$htmlparas = "";
			if (isset($paras["border"]) && isset($paras["bcolor"])) {

				$htmlparas = " style=\"border: " . $paras["border"] . "px solid #" . $paras["bcolor"] . ";\"";
				if (isset($paras["w"])) {
					$paras["w"] -= $paras["border"] * 2;
				} else if (isset($paras["h"])) {
					$paras["h"] -= $paras["border"] * 2;
				}
				unset($paras["border"]);
				unset($paras["bcolor"]);
			}

			if (!empty($paras["src"])) {
                $uri = "thumb.php?" . http_build_query($paras);
               if (Configure::read('Settings.nice_image_url') == '1') {
                    $uri = 'mails/image/'. rtrim(strtr(base64_encode(http_build_query($paras).""), '+/', '-_'), '=') . '/image.jpg';
               }
				$html_content = str_replace("[image" . $im . "]", "<img " . $htmlparas . " src=\"" . Router::url("/", true) . "" . $uri . "\" />", $html_content);
			} else {
				$html_content = str_replace("[image" . $im . "]", "", $html_content);
			}
		}
		$html_content = str_replace(Router::url("/", true), '{$baseurl}', $html_content);
		$olLinks = array();
		$nwLinks = array();
		foreach ($input["Link"] as $value) {
			$olLinks[] = $value["url"] . "\"";
			$olLinks[] = $value["url"] . "'";
			$olLinks[] = $value["url"] . ">";
			$olLinks[] = $value["url"] . " ";
			$nwLinks[] = Router::url("/", true) . "links/goto/" . $value["id"] . "/{\$SUBSCRIBER_ID}" . "\"";
			$nwLinks[] = Router::url("/", true) . "links/goto/" . $value["id"] . "/{\$SUBSCRIBER_ID}" . "'";
			$nwLinks[] = Router::url("/", true) . "links/goto/" . $value["id"] . "/{\$SUBSCRIBER_ID}" . ">";
			$nwLinks[] = Router::url("/", true) . "links/goto/" . $value["id"] . "/{\$SUBSCRIBER_ID}" . " ";
		}
		if (count($olLinks) > 0) {
			$html_content = str_replace($olLinks, $nwLinks, $html_content);
		}
		$html_content = str_replace('{$baseurl}', Router::url("/", true), $html_content);
		preg_match($pattern, $html_content, $style);
		if (isset($style[1])) {
			$css = $style[1];
		} else {
			$css = "";
		}
		// $html = preg_replace('/\<style[^>]*>(.*?)<\/style\>/s', '', $html_content);
		$html = $html_content;
		if ($input["Template"]["parse_css"] == 1 && class_exists('DOMDocument')) {

			require_once "../vendors/emogrifier.php";
			$emog = new Emogrifier($html, $css);
			return str_replace(array(Router::url("/", true) . "http://", Router::url("/", true) . "https://", '{$baseurl}'), array("http://", "https://", ""), preg_replace('/%7B(\$|%24)(.*)%7D/i', '{$\2}', @$emog->emogrify()));
		} else {
			return str_replace(array(Router::url("/", true) . "http://", Router::url("/", true) . "https://", '{$baseurl}'), array("http://", "https://", ""), preg_replace('/%7B(\$|%24)(.*)%7D/i', '{$\2}', $html_content));
		}
	}

	function __makeChart($chart) {
		if (count($chart) > 0) {
			$first = $chart[0];
			$last = end($chart);
			if ((mktime(0, 0, 0, $last[0]["m"], $last[0]["d"], $last[0]["y"]) - mktime(0, 0, 0, $first[0]["m"], $first[0]["d"], $first[0]["y"])) <
					(60 * 60 * 24) * 7
			) {
				$chart[] = array(array("c" => 0, "m" => date("m", mktime(0, 0, 0, $last[0]["m"], $last[0]["d"] + 7, $last[0]["y"])), "d" => date("d", mktime(0, 0, 0, $last[0]["m"], $last[0]["d"] + 7, $last[0]["y"])), "y" => date("Y", mktime(0, 0, 0, $last[0]["m"], $last[0]["d"] + 7, $last[0]["y"]))));
			}
		}

		$chartdata = array();
		if (count($chart) > 0) {
			$oneDay = 1 * 24 * 60 * 60 * 1000;
			foreach ($chart as $c) {

				$chartdata[(mktime(0, 0, 0, $c[0]["m"], $c[0]["d"], $c[0]["y"]) * 1000) . ""] = $c[0]["c"];
			}
			ksort($chartdata);
			foreach ($chartdata as $k => $v) {
				if ($v != 0 && !array_key_exists(($k + $oneDay) . "", $chartdata)) {
					$chartdata[($k + $oneDay) . ""] = 0;
				}
				if ($v != 0 && !array_key_exists(($k - $oneDay) . "", $chartdata)) {
					$chartdata[($k - $oneDay) . ""] = 0;
				}
			}
			ksort($chartdata);
		}
		return $chartdata;
	}

	function sendstatus($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid mail', true));
			$this->redirect(array('action' => 'index'));
		}
		$ma = $this->Mail->read(null, $id);
		if ($ma["Mail"]["status"] == 0) {
			$this->redirect(array('action' => 'index'));
		}
		if ($ma["Mail"]["prepared"] == 1 || $ma["Mail"]["campaign_id"] != 0) {
			$tot = $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id)));
		} else {
			$cat = array();
			foreach ($ma["Category"] as $k) {
				$cat[] = $k["id"];
			}
			$this->CategoriesSubscriber->bindModel(
				array(
					'hasOne' => array(
						'Subscriber' => array(
							'className' => 'Subscriber',
							'foreignKey' => 'id',
						)
					)
				)
			);

			$tot = count($this->CategoriesSubscriber->find("all", array("fields" => array("Subscriber.id"), "group" => "Subscriber.id", "conditions" => array("CategoriesSubscriber.category_id" => $cat, "Subscriber.deleted" => 0))));
		}
		$this->set('mail', $ma);
		$this->set('recipient', array(
			"total" => $tot,
			"sent" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 0))),
			"read" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 1))),
			"failed" => $this->Mail->Recipient->find("count", array("conditions" => array(array("mail_id" => $id, "sent" => 0), array("OR" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL")))))
		));

		$this->set('chartdata', $this->__makeChart($this->Mail->Recipient->query("SELECT count( * ) as c , YEAR( `send_date` ) as y , MONTH( `send_date` ) as m , DAY( `send_date` ) as d FROM `recipients` WHERE sent=1 AND mail_id=" . $id . " GROUP BY YEAR( `send_date` ) , MONTH( `send_date` ) , DAY( `send_date` ) ORDER BY `send_date`")));

		$this->set('chartdata2', $this->__makeChart($this->Mail->Recipient->query("SELECT count( * ) as c , YEAR( `read_date` ) as y , MONTH( `read_date` ) as m , DAY( `read_date` ) as d
FROM `recipients` WHERE sent=1 AND `read`=1 AND mail_id=" . $id . "
GROUP BY YEAR( `read_date` ) , MONTH( `read_date` ) , DAY( `read_date` )
ORDER BY `read_date`")));
	}

	function parallelSend($job, $total) {
		if (Configure::read('Settings.parallel_jobs_count') != $total || Configure::read('Settings.parallel_jobs') != '1' || $job > $total || $job < 1) {
			echo 'Invalid Job';
			exit();
		}
		$this->sendAll(0,$job,$total);

	}

	function sendAll($in = 0, $job = 1, $total = -1) {
		if (Configure::read('Settings.parallel_jobs') != '1') {
			$job = 1;
			$total = 1;
		} else if (Configure::read('Settings.parallel_jobs_count') != $total || $job > $total || $job < 1) {
			echo 'Invalid Job';
			exit();
		}

		@set_time_limit(60 * 60);
		$cmails = $this->Mail->find("list", array("fields" => "Mail.id", "conditions" => array("campaign_id != 0")));
		foreach ($cmails as $m) {
			$this->cprep($m);
		}
		$this->Campaign->updateAll(array('last_check' => "'" . date('Y-m-d H:i:s'), "'"), array("1 = 1"));
		$this->Mail->recursive = 0;

		$this->Mail->Configuration->updateAll(array('mcount' => "0"), array("free <" => date('Y-m-d H:i:s')));
		$all = $this->Mail->find("all", array("fields" => "Mail.id", 'order' => 'rand()', "conditions" => array("mcount < mails_per_time", "OR" => array(array("Mail.status != 0", "campaign_id != 0", "active = 1"), array("Mail.status =" => 1, "send_on <" => date('Y-m-d H:i:s'))))));

		if (count($all) > 0) {
			foreach ($all as $next) {
				$this->send($next["Mail"]["id"], true, $job,$total);
			}
			$out = "DONE";
		} else {
			$out = "NO";
		}
		if ($in == 1) {
			if ($out != "NO") {
				if (!$this->cr) {
					$this->Session->setFlash(__('Job done!', true));
				}
			} else {
				$this->Session->setFlash(__('There is nothing to do :-)', true));
			}
			$this->redirect($this->referer());
		} else {
			echo $out;
			exit();
		}
	}

	function send($id = null, $in = false,$job=1, $total=-1) {
		if(Configure::read('Settings.parallel_jobs') != '1'){
			$job=1;
			$total=1;
		}else if (Configure::read('Settings.parallel_jobs_count') != $total || $job > $total || $job < 1) {
			echo 'Invalid Job';
			exit();
		}


		@set_time_limit(0);
		if (!file_exists(CONFIGS . 'lock'.$job.'_'.$total.'.yml') || intval(file_get_contents(CONFIGS . 'lock'.$job.'_'.$total.'.yml')) < time()) {
			file_put_contents(CONFIGS . 'lock'.$job.'_'.$total.'.yml', time() + (1 * 60));
			$this->Mail->Configuration->updateAll(array('mcount' => "0"), array("free <" => date('Y-m-d H:i:s')));
			if (!$id) {
				if (!$in) {
					$this->Session->setFlash(__('Invalid mail', true));
					$this->redirect(array('action' => 'index'));
				} else {
					echo "invalid";
					return;
				}
			}
			$sent_mails = 0;
			$mail = $this->Mail->read(null, $id);

			$tdiv = strtotime($mail['Mail']["send_on"]) - mktime();

			if ($tdiv > 0 && $mail['Mail']['campaign_id'] == 0) {
				if (!$in) {
					$this->Session->setFlash(__('Please wait for', true) . " " . $tdiv . " " . __('seconds', true));
					$this->redirect(array('action' => 'sendstatus', $id));
				} else {
					echo "wait";
					return;
				}
			}
			if ($mail['Mail']['prepared'] == 0 && $mail['Mail']['campaign_id'] == 0) {
				$this->prep($id);
			}
			$re = $this->Mail->query("SELECT (`mails_per_time`-`mcount`) as q FROM `configurations` WHERE `configurations`.`id` =" . $mail["Configuration"]["id"] . ";", false);
			if ($re[0][0]["q"] <= 0) {
				echo "wait";
				return;
			}
			$this->Mail->Recipient->recursive = 0;
			$rec = $this->Mail->Recipient->find("all", array('limit' => floor($re[0][0]["q"]/$total), 'order' => 'rand()', "conditions" => array('(Recipient.id MOD '.$total.')='.($job-1),"mail_id" => $id, "sent" => 0, "failed <" => 2, "Subscriber.deleted !=" => 1)));

			$content = $mail["Mail"]["final_html"];

			require_once "../vendors/Swift/lib/swift_required.php";
			try {
				try {
					$mailer = Swift_Mailer::newInstance($this->Mail->Configuration->getTransport($mail["Configuration"]));

					$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin($mail["Configuration"]["mails_per_connection"]));

					foreach ($rec as $reciver) {

						$re = $this->Mail->query("SELECT (`mails_per_time`-`mcount`) as q FROM `configurations` WHERE `configurations`.`id` = " . $mail["Configuration"]["id"] . ";", false);

						if ($re[0][0]["q"] <= 0) {

							break;
						}
						try {
							$message = Swift_Message::newInstance($this->Mail->addInfos($mail["Mail"]["subject"], $reciver["Subscriber"]))
									->setFrom(prep_add($mail["Configuration"]["from"]))
									->setTo(array($reciver["Subscriber"]["mail_adresse"]));
                            if (!empty($mail["Configuration"]["bounce_to"]))
                                $message->setReturnPath($mail["Configuration"]["bounce_to"]);

                            if (!empty($mail["Configuration"]["reply_to"]))
								$message->setReplyTo(prep_add($mail["Configuration"]["reply_to"]));
							$message->setEncoder(Swift_Encoding::get8BitEncoding());

                            $headers = $message->getHeaders();
                            $headers->addTextHeader("List-Unsubscribe: <" . Router::url("/", true) . "unsubscribe/" . $mail["Mail"]["id"] . "/"+$reciver["Subscriber"]["id"] . "-" . $reciver["Subscriber"]["unsubscribe_code"]+">");

                            $headers->addTextHeader('X-NLReft', 'id' . $reciver["Recipient"]["id"]);

							$plain = $mail["Mail"]["content_text"];
							$html = $content;
							$html = str_replace("</body>", "<img src=\"" . Router::url("/", true) . "viewed/" . $mail["Mail"]["id"] . "/{\$SUBSCRIBER_ID}\" />", $html);
							$html = $this->Mail->updateUrls($html, $mail["Mail"]["id"]);
							$html = $this->Mail->addInfos($html, $reciver["Subscriber"]);

							if ($mail["Mail"]["type"] == 0 || $mail["Mail"]["type"] == 2) {

								$plain = $this->Mail->updateUrls($plain, $mail["Mail"]["id"]);
								$plain = $this->Mail->addInfos($plain, $reciver["Subscriber"]);

								$message->addPart(wordwrap($plain));
							}
							if ($mail["Mail"]["type"] == 0 || $mail["Mail"]["type"] == 1) {
								$message->addPart($html, "text/html");
							}

							//

							$result = $mailer->send($message);
							if ($result) {
								$this->Mail->Recipient->id = $reciver["Recipient"]["id"];
								$this->Mail->Recipient->saveField("sent", 1);
								$this->Mail->Recipient->saveField("send_date", date('Y-m-d h:i:s'));
								$this->Mail->query("UPDATE `configurations` SET `mcount` = `mcount`+1 WHERE `configurations`.`id` = " . $mail["Configuration"]["id"] . ";");

								if ($mail["Configuration"]["mcount"] == 0) {
									$mail["Configuration"]["mcount"]++;
									$this->Mail->Configuration->id = $mail["Configuration"]["id"];
									$this->Mail->Configuration->saveField("free", date('Y-m-d H:i:s', mktime(date("H"), date("i") + $mail["Configuration"]["time"], date("s"), date("m"), date("d"), date("Y"))));
								}
							} else {
								$this->Mail->Recipient->id = $reciver["Recipient"]["id"];
								$this->Mail->Recipient->saveField("failed", $reciver["Recipient"]["failed"] + 1);
							}
						} catch (Exception $e) {
							if (get_class($e) == "Swift_TransportException") {
								throw $e;
							}
							$this->Mail->Recipient->id = $reciver["Recipient"]["id"];
							$this->Mail->Recipient->saveField("failed", $reciver["Recipient"]["failed"] + 1);
						}
						file_put_contents(CONFIGS . 'lock'.$job.'_'.$total.'.yml', time() + (1 * 60));
					}
				} catch (Swift_TransportException $e) {
					if (!$in) {
						$this->Session->setFlash(__('Unable to connect to server', true) . "<br />" . $e->getMessage() . "<br /><a href=\"" . Router::url("/configurations/edit/", true) . $mail["Configuration"]["id"] . "\">Edit SMTP Configuration</a>");
					} else {
						echo __('Unable to connect to server', true) . "<br />" . $e->getMessage();
					}
				}
			} catch (Exception $e) {
				if (!$in) {
					$this->Session->setFlash(__('Error failed to send mails', true) . "<br />" . $e->getMessage());
				} else {
					echo __('Error failed to send mails', true) . "<br />" . $e->getMessage();
				}
			}
			$status = array(
				"total" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id))),
				"sent" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 0))),
				"read" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 1))),
				"failed" => $this->Mail->Recipient->find("count", array("conditions" => array(array("mail_id" => $id, "sent" => 0), array("OR" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL")))))
			);
			if (($status["sent"] + $status["read"] + $status["failed"]) >= $status["total"] && $mail['Mail']['campaign_id'] == 0) {
				$this->Mail->id = $id;
				$this->Mail->saveField("status", 2);
			}

			@unlink(CONFIGS . 'lock'.$job.'_'.$total.'.yml');
		} else {

			if (!$in) {
				$this->Session->setFlash("Wait Cronjob Already Running");
				$this->cr = true;
			} else {
				die("wait cronjob already running");
				return;
			}
		}

		if (!$in) {
			$this->redirect(array('action' => 'sendstatus', $id));
		}
	}

	function track($mail = null, $subscr = null) {
		$this->Mail->Recipient->openMail($mail, $subscr);
		$this->redirect("/img/1x1.png");
	}

	function prepare($id = null) {
		if (isset($this->data) && !empty($this->data)) {
			$this->Mail->Link->deleteAll(array("mail_id" => $id));
			if (is_array($this->data['TrackedLinks']))
				foreach ($this->data['TrackedLinks'] as $v) {

					$this->Mail->Link->create();
					$this->Mail->Link->save(array("url" => $v, "mail_id" => $id));
				}
			$this->Mail->id = $id;
			$this->Mail->save(array("Mail" => array("id" => $id, "prepared" => 0, "status" => 1, "send_date" => date('Y-m-d H:i:s'), "send_on" => $this->data["Mail"]["send_on"])));
			$this->Mail->Recipient->deleteAll(array("mail_id" => $id));
		}

		$mail = $this->Mail->read(null, $id);

		$tdiv = strtotime($mail['Mail']["send_on"]) - mktime();

		if ($tdiv > 0 || $mail['Mail']['campaign_id'] != 0) {
		} else {
			$this->prep($id);
		}

		$this->Mail->id = $id;
		$this->Mail->saveField("final_html", $this->loadcontent($mail));
		if ($mail['Mail']['campaign_id'] != 0) {
			$this->redirect(array('action' => 'campaign', $mail['Mail']['campaign_id']));
		} else {
			$this->redirect(array('action' => 'sendstatus', $id));
		}
	}

	function prep($id) {
		@set_time_limit(60 * 60);
		$r = $this->Mail->recursive;
		$this->Mail->recursive = 1;
		$this->Mail->id = $id;
		$this->Mail->saveField("prepared", 1);
		$cats = $this->Mail->read(null, $id);
		$this->Mail->recursive = $r;
		$cat = array();

		if (isset($cats["Category"]) && !empty($cats["Category"])) {
			foreach ($cats["Category"] as $k) {
				$cat[] = $k["id"];
			}
		}

		$this->Mail->Recipient->deleteAll(array("mail_id" => $id));

		$this->Mail->Recipient->query("INSERT INTO
recipients
(
subscriber_id,
mail_id
)
(SELECT  DISTINCT `id`  as k, " . $id . " as m
FROM `subscribers`
LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON ( `CategoriesSubscriber`.`subscriber_id` = `subscribers`.`id` )
WHERE `CategoriesSubscriber`.`category_id` IN ( " . implode(", ", $cat) . ") AND deleted = 0
ORDER BY `subscribers`.`id` ASC)");

	}

	function reactivatefailed($id) {
		@set_time_limit(60 * 60);
		$r = $this->Mail->recursive;
		$this->Mail->recursive = 1;
		$this->Mail->id = $id;
		$this->Mail->saveField("prepared", 1);
		$this->Mail->saveField("status", 1);
		$this->Mail->Recipient->updateAll(array('Recipient.failed' => 0),
			array("Recipient.mail_id" => $id));
		$cats = $this->Mail->read(null, $id);
		$this->Mail->recursive = $r;
		$cat = array();

		if (isset($cats["Category"]) && !empty($cats["Category"])) {
			foreach ($cats["Category"] as $k) {
				$cat[] = $k["id"];
			}
		}
		$status = array(
			"total" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id))),
			"sent" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 0))),
			"read" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 1))),
			"failed" => $this->Mail->Recipient->find("count", array("conditions" => array(array("mail_id" => $id, "sent" => 0), array("OR" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL")))))
		);
		if (($status["sent"] + $status["read"] + $status["failed"]) >= $status["total"] && $cats['Mail']['campaign_id'] == 0) {
			$this->Mail->id = $id;
			$this->Mail->saveField("status", 2);
		}
		$this->redirect(array('action' => 'sendstatus', $id));
		$this->redirect(array('action' => 'sendstatus', $id));
	}

	function reactivate($id) {
		@set_time_limit(60 * 60);
		$r = $this->Mail->recursive;
		$this->Mail->recursive = 1;
		$this->Mail->id = $id;
		$this->Mail->saveField("prepared", 1);
		$this->Mail->saveField("status", 1);

		$cats = $this->Mail->read(null, $id);
		$this->Mail->recursive = $r;
		$cat = array();

		if (isset($cats["Category"]) && !empty($cats["Category"])) {
			foreach ($cats["Category"] as $k) {
				$cat[] = $k["id"];
			}
		}

		$this->Mail->Recipient->query("INSERT INTO
recipients
(
subscriber_id,
mail_id
)
(SELECT  DISTINCT `id`  as k, " . $id . " as m
FROM `subscribers`
LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON ( `CategoriesSubscriber`.`subscriber_id` = `subscribers`.`id` )
WHERE `CategoriesSubscriber`.`category_id` IN (" . implode(", ", $cat) . ") AND deleted = 0 AND (SELECT count(*) FROM `recipients` WHERE `recipients`.`subscriber_id`=`subscribers`.`id` AND `recipients`.`mail_id`=" . $id . "  )=0
ORDER BY `subscribers`.`id` ASC)
");
		$status = array(
			"total" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id))),
			"sent" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 0))),
			"read" => $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1, "read" => 1))),
			"failed" => $this->Mail->Recipient->find("count", array("conditions" => array(array("mail_id" => $id, "sent" => 0), array("OR" => array("Subscriber.deleted =" => 1, "failed >" => 1, "Subscriber.deleted is NULL")))))
		);
		if (($status["sent"] + $status["read"] + $status["failed"]) >= $status["total"] && $cats['Mail']['campaign_id'] == 0) {
			$this->Mail->id = $id;
			$this->Mail->saveField("status", 2);
		}
		$this->redirect(array('action' => 'sendstatus', $id));
	}

	function cprep($id) {

		$cats = $this->Mail->read(null, $id);
		$campaign = $this->Campaign->read(null, $cats["Mail"]["campaign_id"]);

		$cat = $campaign["Campaign"]["Category"];

		$commad = "INSERT INTO
recipients
(
subscriber_id,
mail_id
)
(SELECT  DISTINCT `id`  as k, " . $id . " as m
FROM `subscribers`
LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON ( `CategoriesSubscriber`.`subscriber_id` = `subscribers`.`id` )
WHERE
(DATE_ADD(subscribers.created, INTERVAL " . $cats["Mail"]["delay"] . " DAY)> '" . $campaign["Campaign"]["last_check"] . "')
AND subscribers.created >= '" . $campaign["Campaign"]["start"] . "'
AND	(DATE_ADD(subscribers.created, INTERVAL " . $cats["Mail"]["delay"] . " DAY)<= NOW())
AND (1 = " . $campaign["Campaign"]["forever"] . " OR (DATE_ADD('" . $campaign["Campaign"]["end"] . "', INTERVAL 1 DAY) > subscribers.created ))
AND `CategoriesSubscriber`.`category_id` IN (" . implode(", ", $cat) . ")
AND deleted = 0
AND (SELECT count(*) FROM `recipients` WHERE `recipients`.`subscriber_id`=`subscribers`.`id` AND `recipients`.`mail_id`=" . $id . "  )=0
ORDER BY `subscribers`.`id` ASC)
";

		$this->Mail->Recipient->query($commad);

		if ($campaign["Campaign"]["sendtoold"] == 1) {
			$commad = "INSERT INTO
recipients
(
subscriber_id,
mail_id
)
(SELECT  DISTINCT `id`  as k, " . $id . " as m
FROM `subscribers`
LEFT JOIN `categories_subscribers` AS `CategoriesSubscriber` ON ( `CategoriesSubscriber`.`subscriber_id` = `subscribers`.`id` )
WHERE
 subscribers.created < '" . $campaign["Campaign"]["start"] . "'
AND	(DATE_ADD('" . $campaign["Campaign"]["start"] . "', INTERVAL " . $cats["Mail"]["delay"] . " DAY)<= NOW())
AND (DATE_ADD('" . $campaign["Campaign"]["start"] . "', INTERVAL " . $cats["Mail"]["delay"] .
					" DAY)> '" . $campaign["Campaign"]["last_check"] . "')
AND `CategoriesSubscriber`.`category_id` IN (" . implode(", ", $cat) . ")
AND deleted = 0
AND (SELECT count(*) FROM `recipients` WHERE `recipients`.`subscriber_id`=`subscribers`.`id` AND `recipients`.`mail_id`=" .
					$id . "  )=0
ORDER BY `subscribers`.`id` ASC)
";

			$this->Mail->Recipient->query($commad);
		}
	}

	function back($id = null) {
		$sent = $this->Mail->Recipient->find("count", array("conditions" => array("mail_id" => $id, "sent" => 1)));
		if ($sent == 0) {
			$this->Mail->Link->deleteAll(array("Mail.id" => $id));
			$this->Mail->Link->deleteAll(array("mail_id" => $id));
			$this->Mail->id = $id;
			$this->Mail->saveField("status", 0);
			$this->Mail->saveField("final_html", 0);
			$this->Mail->saveField("send_date", date('Y-m-d H:i:s'));

			$this->Mail->Recipient->deleteAll(array("Mail.id" => $id));

			$this->redirect(array('action' => 'step', $id));
		} else {
			$this->redirect(array('action' => 'sendstatus', $id));
		}
	}

	function duplicate($id = null) {

		$mail = $this->Mail->read(null, $id);
		$this->Mail->recursive = 0;
		$mail2 = $this->Mail->read(null, $id);
		$this->Mail->create();
		unset($mail2["Mail"]["id"]);
		$mail2["Mail"]["subject"] = "Copy " . $mail2["Mail"]["subject"];
		$mail2["Mail"]["status"] = 0;
		$mail2["Mail"]["unsubscribed"] = 0;
		$mail2["Mail"]["sendtof"] = 0;
		$mail2["Mail"]["final_html"] = "";
		$mail2["Mail"]["delay"] = 0;
		unset($mail2["Mail"]["modified"]);
		unset($mail2["Mail"]["send_on"]);

		unset($mail2["Mail"]["created"]);
		foreach ($mail["Category"] as $value) {
			$mail2["Category"][] = $value["id"];
		}
		if ($this->Mail->save($mail2)) {

			$this->redirect(array('action' => 'step', $this->Mail->id, 1));
		} else {
			$this->Session->setFlash(__('The mail could not be duplicate. Please, try again.', true));

			$this->redirect($this->referer());
		}
	}

	function test($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid mail', true));
			$this->redirect(array('action' => 'index'));
		}
		$mail = $this->Mail->read(null, $id);
		$content = $this->loadcontent($mail);
		$content = $this->Mail->addInfos($content, array("form_id" => 0, "id" => 0, "mail_adresse" => "guest@guest.com", "first_name" => "Guest", "last_name" => "User", "custom1" => "custom1", "custom2" => "custom2", "custom3" => "custom3", "custom4" => "custom4", "unsubscribe_code" => "GUEST"));

		require_once "../vendors/Swift/lib/swift_required.php";
		try {
			try {

				$mailer = Swift_Mailer::newInstance($this->Mail->Configuration->getTransport($mail["Configuration"]));

				//Create a message
				$message = Swift_Message::newInstance($mail["Mail"]["subject"])
						->setFrom(prep_add($mail["Configuration"]["from"]))
						->setTo($this->data["Mail"]["TestAdresse"]);
				$headers = $message->getHeaders();
				$message->setEncoder(Swift_Encoding::get8BitEncoding());
				$headers->addTextHeader('X-NLReft', 'test');

				//Send the message
				if (!empty($mail["Configuration"]["reply_to"]))
					$message->setReplyTo(prep_add($mail["Configuration"]["reply_to"]));

				if ($mail["Mail"]["type"] == 0 || $mail["Mail"]["type"] == 2) {

					$message->addPart(wordwrap($mail["Mail"]["content_text"]));
				}
				if ($mail["Mail"]["type"] == 0 || $mail["Mail"]["type"] == 1) {
					$message->addPart($content, "text/html");
				}

				//

				$result = $mailer->send($message);
				if ($result)
					$this->Session->setFlash(__('Test mail sent', true));
				else
					$this->Session->setFlash(__('Error failed to send test mail', true));
			} catch (Swift_TransportException $e) {
				$this->Session->setFlash(__('Unable to connect to server', true) . "<br />" . $e->getMessage() . "<br /><a href=\"" . Router::url("/configurations/edit/", true) . $mail["Configuration"]["id"] . "\">Edit SMTP Configuration</a>");
			}
		} catch (Exception $e) {

			$this->Session->setFlash(__('Error failed to send test mail', true) . "<br />" . $e->getMessage());
		}
		$this->redirect(array('action' => 'step', $id, 4));
	}

	function stop($id = null) {
		$this->Mail->id = $id;
		$this->Mail->saveField("status", 3);

		$this->redirect(array('action' => 'sendstatus', $id));
	}

	function delete($id = null, $camp = 0) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for mail', true));
			$this->redirect(array('action' => 'campaign', $camp));
		}
		if ($this->Mail->delete($id)) {
			$this->Session->setFlash(__('Mail deleted', true));
			$this->redirect(array('action' => 'campaign', $camp));
		}
		$this->Session->setFlash(__('Mail was not deleted', true));
		$this->redirect(array('action' => 'campaign', $camp));
	}

}

?>