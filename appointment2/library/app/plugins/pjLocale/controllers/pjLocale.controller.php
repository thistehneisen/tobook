<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once dirname(__FILE__) . '/pjLocaleAppController.controller.php';
class pjLocale extends pjLocaleAppController
{
	public function pjActionLocales()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($this->option_arr['o_multi_lang']) && (int) $this->option_arr['o_multi_lang'] === 1)
		{
			pjObject::import('Model', 'pjLocale:pjLocaleLanguage');
			$this->set('language_arr', pjLocaleLanguageModel::factory()->where('t1.file IS NOT NULL')->orderBy('t1.title ASC')->findAll()->getData());
			
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 3);
			return;
		}
	}
	
	public function pjActionSaveFields()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($_POST['i18n']) && count($_POST['i18n']) > 0)
		{
			$pjFieldModel = pjFieldModel::factory();
			$MultiLangModel = pjMultiLangModel::factory();
			$MultiLangModel->begin();
			foreach ($_POST['i18n'] as $locale_id => $arr)
			{
				foreach ($arr as $foreign_id => $locale_arr)
				{
					$data = array();
					$data[$locale_id] = array();
					foreach ($locale_arr as $name => $content)
					{
						$data[$locale_id][$name] = $content;
					}
					$fids = $MultiLangModel->updateMultiLang($data, $foreign_id, 'pjField');
					if (!empty($fids))
					{
						$pjFieldModel->reset()->whereIn('id', $fids)->limit(count($fids))->modifyAll(array('modified' => ':NOW()'));
					}
				}
			}
			$MultiLangModel->commit();
		}
		pjUtil::redirect(sprintf("%sindex.php?controller=pjLocale&action=%s&err=PAL01&tab=1&q=%s&locale=%u&page=%u", PJ_INSTALL_URL, $_POST['next_action'], urlencode($_POST['q']), $_POST['locale'], $_POST['page']));
		exit;
	}
	
	private function pjActionCheckDefault()
	{
		pjObject::import('Model', 'pjLocale:pjLocale');
		if (0 == pjLocaleModel::factory()->where('is_default', 1)->findCount()->getData())
		{
			pjLocaleModel::factory()->limit(1)->modifyAll(array('is_default' => 1));
		}
	}
	
	public function pjActionDeleteLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			$response = array();
			pjObject::import('Model', 'pjLocale:pjLocale');
			if (pjLocaleModel::factory()->setAttributes(array('id' => $_GET['id']))->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('locale', $_GET['id'])->eraseAll();
				$response['code'] = 200;
				
				$this->pjActionCheckDefault();
			} else {
				$response['code'] = 100;
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionGetLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
			$pjLocaleModel = pjLocaleModel::factory();
			
			$column = 't1.sort';
			$direction = 'ASC';
			if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
			{
				$column = $_GET['column'];
				$direction = strtoupper($_GET['direction']);
			}

			$total = $pjLocaleModel->findCount()->getData();
			$rowCount = 100;
			$pages = ceil($total / $rowCount);
			$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjLocaleModel->select('t1.*, t2.title, t2.file')
				->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
				->orderBy("$column $direction")->findAll()->getData();
						
			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}
	
	public function pjActionImportExport()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}

		pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
		$this->set('locale_arr', pjLocaleModel::factory()
			->select('t1.*, t2.title')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso')
			->orderBy('t1.sort ASC')
			->findAll()
			->getData()
		);
	}
	
	public function pjActionImport()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
		
		$err = 'PAL02';
		if (isset($_POST['import']) && $this->isLoged() && $this->isAdmin())
		{
			set_time_limit(600); //10 min
			
			if (isset($_FILES['file']) && isset($_POST['locale']))
			{
				$locale = (int) $_POST['locale'];
				
				$pjUpload = new pjUpload();
				$pjUpload->setAllowedExt(array('sql'));
				
				if ($pjUpload->load($_FILES['file']))
				{
					$string = "";
					$handle = fopen($pjUpload->getFile('tmp_name'), "r") or die("Couldn't get handle");
					if ($handle)
					{
						while (!feof($handle))
						{
							$string .= fgets($handle, 4096);
					    }
					    fclose($handle);
								
						if (!empty($string))
						{
							$arr = preg_split('/;(\s+)?\n/', $string);
							$pjAppModel = pjAppModel::factory();
							$pjAppModel->begin();
							foreach ($arr as $statement)
							{
								$statement = trim($statement);
								if (!empty($statement))
								{
									$statement = preg_replace('/`model`\s*=\s*\'pjField\'\s*AND\s*`locale`\s*=\s*\'\d+\'\s*AND\s*`source`/', "`model` = 'pjField' AND `locale` = '$locale' AND `source`", $statement);
									$statement = preg_replace('/\'pjField\',\s*\'\d+\',\s*\'title\'/', "'pjField', '$locale', 'title'", $statement);
									$pjAppModel->execute($statement);
									if (!$pjAppModel->getResult())
									{
										$err = 'PAL04';
										break;
									}
								}
							}
							if ($err != 'PAL04')
							{
								$pjAppModel->commit();
								$err = 'PAL03';
							} else {
								$pjAppModel->rollback();
							}
						}
					}
				}
			}
		}
		pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjLocale&action=pjActionImportExport&tab=1&err=".$err);
	}
	
	public function pjActionExport()
	{
		$this->setAjax(true);
		$this->setLayout('pjActionEmpty');
		
		if (isset($_POST['export']) && $this->isLoged() && $this->isAdmin())
		{
			set_time_limit(600); //10 min
			
			$name = 'pjLocale-'.time();

			$AppModel = pjAppModel::factory();
			$pjFieldModel = pjFieldModel::factory();
			$pjMultiLangModel = pjMultiLangModel::factory();
				
			$sql = array();
			$arr = array(
				'pjMultiLang' => $pjMultiLangModel->getTable()
			);

			foreach ($arr as $model => $table)
			{
				if (strpos($table, PJ_PREFIX . PJ_SCRIPT_PREFIX) !== 0)
				{
					continue;
				}

				$result = $AppModel
					->reset()
					->prepare(sprintf("SELECT * FROM `%s` WHERE `model` = :model AND `locale` = :locale AND `source` != :source", $table))
					->exec(array(
						'model' => 'pjField',
						'source' => 'data',
						'locale' => (int) @$_POST['locale']
					))
					->getData();
				
				$sql[] = sprintf("DELETE FROM `%s` WHERE `model` = 'pjField' AND `locale` = '%u' AND `source` != 'data';\n\n", $table, $_POST['locale']);

   				$this->pjActionProcessResult($sql, $result, $table, $model);
   				$sql[] = "\n";
			}

   			$content = join("", $sql);
			
			pjToolkit::download($content, $name.'.sql');
		}
		exit;
	}
	
	private function pjActionProcessResult(&$sql, $result, $table, $model, $opts=array())
	{
		if (!empty($result))
		{
			$defaults = array(
				'duplicate' => FALSE,
				'ignore' => FALSE,
				'truncate' => FALSE
			);
			$options = array_merge($defaults, $opts);
			
			if ($options['truncate'])
			{
				$sql[] = sprintf("TRUNCATE TABLE `%s`;\n", $table);
			}
			
			$start = sprintf("INSERT%s INTO `%s` VALUES", ($options['ignore'] ? ' IGNORE' : NULL), $table);
			$records = $queries = array();
			foreach ($result as $row)
			{
				$insert = $update = array();
				foreach ($row as $key => $val)
				{
					if (in_array($key, array('id')) || (in_array($key, array('created', 'modified')) && (empty($val) || $val == '0000-00-00 00:00:00')))
					{
						$val = 'NULL';
						$insert[] = $val;
						$update[] = "`$key` = $val";
					} else {
						$val = str_replace('\n', '\r\n', $val);
						$val = preg_replace("/\r\n|\n/", '\r\n', $val);
						$insert[] = "'" . str_replace("'", "''", $val) . "'";
						$update[] = "`$key` = '" . str_replace("'", "''", $val) . "'";
					}
				}

				if ($options['duplicate'])
				{
					$queries[] = $start . " (" . join(", ", $insert) . ") ON DUPLICATE KEY UPDATE " . join(", ", $update) . ";";
				} else {
					$record = "(" . join(", ", $insert) . ")";
					$records[] = $record;
				}
			}
			if (!empty($records))
			{
				$sql[] = $start . "\n" . join(",\n", $records) . ";\n";
			}
			if (!empty($queries))
			{
				$sql[] = "\n" . join("\n", $queries);
			}
		}
	}

	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
		$this->set('field_arr', pjFieldModel::factory()->findAll()->getDataPair('id', 'label'));
		$pjLocaleModel = pjLocaleModel::factory()->select('t1.*, t2.file')
			->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
			->where('t2.file IS NOT NULL')
			->orderBy('t1.sort ASC');
		if (!isset($this->option_arr['o_multi_lang']) || (int) $this->option_arr['o_multi_lang'] === 0)
		{
			$pjLocaleModel->where('t1.is_default', 1);
		}
		$locale_arr = $pjLocaleModel->findAll()->getData();
			
		$lp_arr = array();
		foreach ($locale_arr as $item)
		{
			$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
		}
		$this->set('lp_arr', $locale_arr);
		$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
		
		$pjFieldModel = pjFieldModel::factory()
			->join('pjMultiLang', "t2.model='pjField' AND t2.foreign_id=t1.id AND t2.locale='".$this->getLocaleId()."'", 'left');
		if (isset($_GET['q']) && !empty($_GET['q']))
		{
			$q = $pjFieldModel->escapeStr(trim($_GET['q']));
			$q = str_replace(array('%', '_'), array('\%', '\_'), $q);
			$pjFieldModel->where("(t1.label LIKE '%$q%' OR t2.content LIKE '%$q%')");
		}
		$pjMultiLangModel = pjMultiLangModel::factory()->where('model', 'pjField')->where('field', 'title');
		
		$column = 'id';
		$direction = 'ASC';
		if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
		{
			$column = $_GET['column'];
			$direction = strtoupper($_GET['direction']);
		}
			
		$total = $pjFieldModel->findCount()->getData();
		$row_count = isset($_GET['row_count']) && (int) $_GET['row_count'] > 0 ? (int) $_GET['row_count'] : 15;
		$pages = ceil($total / $row_count);
		$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? (int) $_GET['page'] : 1;
		$offset = ((int) $page - 1) * $row_count;
		
		$_arr = $pjFieldModel->select("t1.*")->orderBy("$column $direction")->limit($row_count, $offset)->findAll()->getData();

		foreach ($_arr as $_k => $_v)
		{
			$tmp = $pjMultiLangModel->reset()
				->select('t1.*, t2.is_default')
				->join('pjLocale', 't2.id=t1.locale', 'left')
				->where('model', 'pjField')
				->where('field', 'title')
				->where('foreign_id', $_v['id'])
				->findAll()
				->getData();
			$_arr[$_k]['i18n'] = array();
			foreach ($tmp as $item)
			{
				$_arr[$_k]['i18n'][$item['locale']] = $item;
			}
		}

		$this->set('arr', $_arr);
		$this->set('paginator', compact('pages'));
		
		$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
		$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
		$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
		$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
	}
		
	public function pjActionSaveLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjLocale:pjLocale');
			$response = array();
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				pjLocaleModel::factory()->where('id', $_GET['id'])->limit(1)->modifyAll(array($_POST['column'] => $_POST['value']));
				$response['code'] = 201;
			} else {
				$pjLocaleModel = pjLocaleModel::factory();
				$arr = $pjLocaleModel->select('t1.sort')->orderBy('t1.sort DESC')->limit(1)->findAll()->getData();
				$sort = 1;
				if (count($arr) === 1)
				{
					$sort = (int) $arr[0]['sort'] + 1;
				}
				
				pjObject::import('Model', 'pjLocale:pjLocaleLanguage');
				$lang = pjLocaleLanguageModel::factory()->where(sprintf("t1.iso NOT IN (SELECT `language_iso` FROM `%s`)", $pjLocaleModel->getTable()))->where('t1.file IS NOT NULL')->orderBy('t1.title ASC')->limit(1)->findAll()->getDataPair(null, 'iso');
				
				$insert_id = pjLocaleModel::factory(array('sort' => $sort, 'is_default' => '0', 'language_iso' => @$lang[0]))->insert()->getInsertId();
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					$response['code'] = 200;
					$response['id'] = $insert_id;
					
					$locale_id = NULL;
					$arr = $pjLocaleModel->reset()->findAll()->getData();
					foreach ($arr as $locale)
					{
						if ($locale['language_iso'] == 'en')
						{
							$locale_id = $locale['id'];
							break;
						}
					}
					if (is_null($locale_id) && count($arr) > 0)
					{
						$locale_id = $arr[0]['id'];
					}
					if (!is_null($locale_id))
					{
						$sql = sprintf("INSERT INTO `%1\$s` (`foreign_id`, `model`, `locale`, `field`, `content`)
							SELECT t1.foreign_id, t1.model, :insert_id, t1.field, t1.content
							FROM `%1\$s` AS t1
							WHERE t1.locale = :locale", pjMultiLangModel::factory()->getTable());
						pjMultiLangModel::factory()->prepare($sql)->exec(array(
							'insert_id' => $insert_id,
							'locale' => (int) $locale_id
						));
					}
				} else {
					$response['code'] = 100;
				}
			}
			pjAppController::jsonResponse($response);
		}
		exit;
	}
	
	public function pjActionSaveDefault()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjLocale:pjLocale');
			pjLocaleModel::factory()
				->where(1,1)
				->modifyAll(array('is_default' => '0'))
				->reset()
				->set('id', $_POST['id'])
				->modify(array('is_default' => 1));
				
			$this->setLocaleId($_POST['id']);
		}
		exit;
	}
	
	public function pjActionSortLocale()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjLocale:pjLocale');
			$LocaleModel = new pjLocaleModel();
			$arr = $LocaleModel->whereIn('id', $_POST['sort'])->orderBy("t1.sort ASC")->findAll()->getDataPair('id', 'sort');
			$fliped = array_flip($_POST['sort']);
			$combined = array_combine(array_keys($fliped), $arr);
			$LocaleModel->begin();
			foreach ($combined as $id => $sort)
			{
				$LocaleModel->setAttributes(compact('id'))->modify(compact('sort'));
			}
			$LocaleModel->commit();
		}
		exit;
	}

	public function pjActionClean()
	{
		$this->checkLogin();
		
		if (!$this->isAdmin())
		{
			$this->set('status', 2);
			return;
		}
		
		if (isset($_POST['clean_step']))
		{
			if ($_POST['clean_step'] == 1)
			{
				$pjMultiLangModel = pjMultiLangModel::factory();
				$arr = pjMultiLangModel::factory()
					->select('t1.id')
					->join('pjField', 't2.id=t1.foreign_id', 'left')
					->where('t1.model', 'pjField')
					->where('t2.id IS NULL')
					->findAll()
					->getDataPair(null, 'id');
	
				if (!empty($arr))
				{
					$pjMultiLangModel->reset()->whereIn('id', $arr)->eraseAll();
				}
			}
			
			if ($_POST['clean_step'] == 2)
			{
				if (isset($_POST['field_id']) && !empty($_POST['field_id']))
				{
					pjFieldModel::factory()->whereIn('id', $_POST['field_id'])->eraseAll();
					pjMultiLangModel::factory()->where('model', 'pjField')->whereIn('foreign_id', $_POST['field_id'])->eraseAll();
				}
			}
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjLocale&action=pjActionClean");
		}
		
		# Step 1
		$step1_arr = pjMultiLangModel::factory()
			->select('t1.id')
			->join('pjField', 't2.id=t1.foreign_id', 'left')
			->where('t1.model', 'pjField')
			->where('t2.id IS NULL')
			->findAll()
			->getDataPair(null, 'id');
		
		$this->set('step1_arr', $step1_arr);
		
		# Step 2
		$keys = $start = $data = array();
		pjToolkit::readDir($data, PJ_APP_PATH);
		
		foreach ($data as $file)
		{
			$ext = pjToolkit::getFileExtension($file);
			if ($ext !== 'php')
			{
				continue;
			}
			
			$string = file_get_contents($file);
			if ($string !== FALSE)
			{
				preg_match_all('/__\(\s*\'(\w+)\'\s*(?:,\s*(true|false))?\)/i', $string, $matches);
				if (!empty($matches[1]))
				{
					foreach ($matches[1] as $k => $m)
					{
						if (!empty($matches[2][$k]) && strtolower($matches[2][$k]) == 'true')
						{
							$start[] = $m;
						} else {
							$keys[] = $m;
						}
					}
				}
			}
		}
		$keys = array_unique($keys);
		$keys = array_values($keys);
		
		$start = array_unique($start);
		$start = array_values($start);
		
		if (!empty($keys) || !empty($start))
		{
			$field_arr = pjFieldModel::factory()
				->whereNotIn('t1.key', $keys)
				->whereNotIn("SUBSTRING_INDEX(t1.key, '_ARRAY_', 1)", $start)
				->orderBy("FIELD(t1.type, 'backend', 'frontend', 'arrays'), t1.key ASC", false)
				->findAll()
				->getData();
			
			$this->set('field_arr', $field_arr);
		}
		
		$this->appendJs('pjLocale.js', $this->getConst('PLUGIN_JS_PATH'));
	}
}
?>