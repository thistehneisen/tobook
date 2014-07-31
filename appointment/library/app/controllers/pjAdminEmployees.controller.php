<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminEmployees extends pjAdmin
{
	public function pjActionCheckEmail()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (!isset($_GET['email']) || empty($_GET['email']))
			{
				echo 'false';
				exit;
			}
			$pjEmployeeModel = pjEmployeeModel::factory()->where('t1.email', $_GET['email']);
			if ($this->isEmployee())
			{
				$pjEmployeeModel->where('t1.id !=', $this->getUserId());
			} elseif (isset($_GET['id']) && (int) $_GET['id'] > 0) {
				$pjEmployeeModel->where('t1.id !=', $_GET['id']);
			}

			echo $pjEmployeeModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}
	
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{	
			$owner_id = intval($_SESSION['owner_id']);
			if (isset($_POST['employee_create']))
			{
				$data = array();
				$data['calendar_id'] = $this->getForeignId();
				$data['owner_id'] = $owner_id;
				$id = pjEmployeeModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					if (isset($_FILES['avatar']))
					{
						$pjImage = new pjImage();
						$pjImage->setAllowedExt($this->extensions)->setAllowedTypes($this->mimeTypes);
						if ($pjImage->load($_FILES['avatar']))
						{
							$dst = PJ_UPLOAD_PATH . md5($id . PJ_SALT) . ".jpg";
							$pjImage
								->loadImage()
								->resizeSmart(100, 100)
								->saveImage($dst);
								
							pjEmployeeModel::factory()->set('id', $id)->modify(array('avatar' => $dst));
						}
					}
					
					if (isset($_POST['service_id']) && !empty($_POST['service_id']))
					{
						$pjEmployeeServiceModel = pjEmployeeServiceModel::factory()->setBatchFields(array('employee_id', 'service_id'));
						foreach ($_POST['service_id'] as $service_id)
						{
							$pjEmployeeServiceModel->addBatchRow(array($id, $service_id));
						}
						$pjEmployeeServiceModel->insertBatch();
					}
					pjWorkingTimeModel::factory()->initFrom($this->getForeignId(), $id);
					$err = 'AE03';
					if (isset($_POST['i18n']))
					{
						$owner_id = intval($_SESSION['owner_id']);
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $owner_id, $id, 'pjEmployee');
					}
				} else {
					$err = 'AE04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminEmployees&action=pjActionIndex&err=$err");
			} else {

				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
				$locale_arr = pjLocaleModel::factory()->select('t1.*')
                    ->where('t1.owner_id', $owner_id)
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				
				$this->set('service_arr', pjServiceModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->orderBy('`name` ASC')
                    ->where('t1.owner_id', $owner_id)
					->where('t2.owner_id', $owner_id)
					->findAll()
					->getData()
				);
				
				$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
				$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				if ((int) $this->option_arr['o_multi_lang'] === 1)
				{
					$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
					$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
					$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
					$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				}
				$this->appendJs('pjAdminEmployees.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteAvatar()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$id = NULL;
			if ($this->isEmployee())
			{
				$id = $this->getUserId();
			} elseif (isset($_POST['id']) && (int) $_POST['id'] > 0) {
				$id = $_POST['id'];
			}
			
			if (!is_null($id))
			{
				$pjEmployeeModel = pjEmployeeModel::factory();
				$arr = $pjEmployeeModel->find($id)->getData();
				if (!empty($arr))
				{
					$pjEmployeeModel->modify(array('avatar' => ':NULL'));
					
					@clearstatcache();
					if (!empty($arr['avatar']) && is_file($arr['avatar']))
					{
						@unlink($arr['avatar']);
					}
					
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
				}
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteEmployee()
	{
		die('1');
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjEmployeeModel = pjEmployeeModel::factory();
				$arr = $pjEmployeeModel->find($_GET['id'])->getData();
				if (!empty($arr) && $pjEmployeeModel->set('id', $arr['id'])->erase()->getAffectedRows() == 1)
				{
					pjMultiLangModel::factory()->where('model', 'pjEmployee')->where('foreign_id', $arr['id'])->eraseAll();
					pjEmployeeServiceModel::factory()->where('employee_id', $arr['id'])->eraseAll();
					pjWorkingTimeModel::factory()->where('foreign_id', $arr['id'])->where('`type`', 'employee')->limit(1)->eraseAll();
					pjDateModel::factory()->where('foreign_id', $arr['id'])->where('`type`', 'employee')->eraseAll();
					if (!empty($arr['avatar']) && is_file($arr['avatar']))
					{
						@unlink($arr['avatar']);
					}
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Employee have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Employee not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteEmployeeFreetime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjEmployeeFreetimeModel = pjEmployeeFreetimeModel::factory();
				$arr = $pjEmployeeFreetimeModel->find($_GET['id'])->getData();
				if (!empty($arr) && $pjEmployeeFreetimeModel->set('id', $arr['id'])->erase()->getAffectedRows() == 1)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Employee have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Employee not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteCustomtime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0)
			{
				$pjCustomTimesModel = pjCustomTimesModel::factory();
				$arr = $pjCustomTimesModel->find($_GET['id'])->getData();
				if (!empty($arr) && $pjCustomTimesModel->set('id', $arr['id'])->erase()->getAffectedRows() == 1)
				{
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Custom time have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Custom time not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteEmployeeBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				$pjEmployeeModel = pjEmployeeModel::factory();
				$arr = pjEmployeeModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
				if (!empty($arr))
				{
					$pjEmployeeModel->reset()->whereIn('id', $_POST['record'])->eraseAll();
					pjMultiLangModel::factory()->where('model', 'pjEmployee')->whereIn('foreign_id', $_POST['record'])->eraseAll();
					pjEmployeeServiceModel::factory()->whereIn('employee_id', $_POST['record'])->eraseAll();
					pjWorkingTimeModel::factory()->whereIn('foreign_id', $_POST['record'])->where('`type`', 'employee')->eraseAll();
					pjDateModel::factory()->whereIn('foreign_id', $_POST['record'])->where('`type`', 'employee')->eraseAll();
					foreach ($arr as $employee)
					{
						if (!empty($employee['avatar']) && is_file($employee['avatar']))
						{
							@unlink($employee['avatar']);
						}
					}
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Employee(s) have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Employee(s) not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteEmployeeFreetimeBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				$pjEmployeeFreetimeModel = pjEmployeeFreetimeModel::factory();
				$arr = pjEmployeeFreetimeModel::factory()->whereIn('id', $_POST['record'])->findAll()->getData();
				if (!empty($arr))
				{
					$pjEmployeeFreetimeModel->reset()->whereIn('id', $_POST['record'])->eraseAll();
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Employee(s) have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Employee(s) not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionDeleteCustomtimeBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				$pjCustomTimesModel = pjCustomTimesModel::factory();
				$arr = $pjCustomTimesModel->whereIn('id', $_POST['record'])->findAll()->getData();
				if (!empty($arr))
				{
					$pjCustomTimesModel->reset()->whereIn('id', $_POST['record'])->eraseAll();
					pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Custom time(s) have been deleted.'));
				}
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Custom time(s) not found.'));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		}
		exit;
	}
	
	public function pjActionGetEmployee()
	{
		$this->setAjax(true);
		if ($this->isXHR() && $this->isLoged())
		{
            $owner_id = intval($_SESSION['owner_id']);
			if ( isset($_GET['tyle']) && $_GET['tyle'] == 'freetime' ) {
				$pjEmployeeFreetimeModel = pjEmployeeFreetimeModel::factory()
						->select('t1.*, t2.*')
                        ->where('t1.owner_id', $owner_id)
						->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.employee_id AND t2.locale='".$this->getLocaleId()."'", 'left outer');
						
				$column = 'date';
				$direction = 'DESC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
				
				$total = $pjEmployeeFreetimeModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				
				$data = $pjEmployeeFreetimeModel
				->select(sprintf("t1.*, t2.content AS `name`"))->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
				$_data = $data;
				foreach ( $data as $k => $freetime ) {
					$_data[$k]['date'] = date($this->option_arr['o_date_format'], $freetime['start_ts']);
					$_data[$k]['start_ts'] = date($this->option_arr['o_time_format'], $freetime['start_ts']);
					$_data[$k]['end_ts'] = date($this->option_arr['o_time_format'], $freetime['end_ts']);
				}
				
				$data = $_data;
				
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} elseif ( isset($_GET['tyle']) && $_GET['tyle'] == 'customtime' ) {

				$pjEmployeesCustomTimes = pjCustomTimesModel::factory()
					->select('t1.*')
                    ->where('t1.owner_id', $owner_id);
						
				$column = 'name';
				$direction = 'DESC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
				
				$total = $pjEmployeesCustomTimes->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				
				$data = $pjEmployeesCustomTimes
					->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
				
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} else {
				$pjEmployeeModel = pjEmployeeModel::factory()
					->select('t1.*, t2.*')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer') 
					->where('t1.owner_id', $owner_id)
                    // Éo hiểu dòng này làm cái mẹ gì, nên thôi comment cho lành
                    // ->where('t1.calendar_id', $this->getForeignId());
				
				if (isset($_GET['q']) && !empty($_GET['q']))
				{
					$q = str_replace(array('_', '%'), array('\_', '\%'), trim($_GET['q']));
					$pjEmployeeModel->where('t2.content LIKE', "%$q%");
					$pjEmployeeModel->orWhere('t1.email LIKE', "%$q%");
					$pjEmployeeModel->orWhere('t1.phone LIKE', "%$q%");
					$pjEmployeeModel->orWhere('t1.notes LIKE', "%$q%");
				}
	
				if (isset($_GET['is_active']) && strlen($_GET['is_active']) > 0 && in_array($_GET['is_active'], array(1, 0)))
				{
					$pjEmployeeModel->where('t1.is_active', $_GET['is_active']);
				}
	
				$column = 'name';
				$direction = 'ASC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
	
				$total = $pjEmployeeModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
	
				$data = $pjEmployeeModel
					->select(sprintf("t1.*, AES_DECRYPT(t1.password, '%2\$s') AS `password`, t2.content AS `name`,
						(SELECT COUNT(es.id)
							FROM `%1\$s` AS `es`
							WHERE `es`.`employee_id` = `t1`.`id`
							LIMIT 1) AS `services`
						", pjEmployeeServiceModel::factory()->getTable(), PJ_SALT))
					->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
					
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
			}
		}
		exit;
	}
	
	public function pjActionIndex()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminEmployees.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveEmployee()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjEmployeeModel = pjEmployeeModel::factory();
			if (!in_array($_POST['column'], $pjEmployeeModel->getI18n()))
			{
				$pjEmployeeModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $_GET['id'], 'pjEmployee', 'data');
			}
		}
		exit;
	}

	public function pjActionSaveEmployeeFreetime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{/*
			$pjEmployeeFreetimeModel = pjEmployeeFreetimeModel::factory();
			if (!in_array($_POST['column'], $pjEmployeeFreetimeModel->getI18n()))
			{
				$pjEmployeeFreetimeModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
			}*/
		}
		exit;
	}
	
	public function pjActionSaveCustomtime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjCustomTimesModel = pjCustomTimesModel::factory();
			if (!in_array($_POST['column'], $pjCustomTimesModel->getI18n()))
			{
				$pjCustomTimesModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			}
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$owner_id = intval($_SESSION['owner_id']);
			if (isset($_POST['employee_update']) && isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				$data = array();
				if (isset($_FILES['avatar']))
				{
					$pjImage = new pjImage();
					$pjImage->setAllowedExt($this->extensions)->setAllowedTypes($this->mimeTypes);
					if ($pjImage->load($_FILES['avatar']))
					{
						$data['avatar'] = PJ_UPLOAD_PATH . md5($_POST['id'] . PJ_SALT) . ".jpg";
						$pjImage
							->loadImage()
							->resizeSmart(100, 100)
							->saveImage($data['avatar']);
					}
				}
				$data['is_subscribed'] = isset($_POST['is_subscribed']) ? 1 : 0;
				$data['is_subscribed_sms'] = isset($_POST['is_subscribed_sms']) ? 1 : 0;
				
				pjEmployeeModel::factory()->set('id', $_POST['id'])->modify(array_merge($_POST, $data));
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $_POST['id'], 'pjEmployee', 'data');
				}
				
				$pjEmployeeServiceModel = pjEmployeeServiceModel::factory();
				$pjEmployeeServiceModel->where('employee_id', $_POST['id'])->eraseAll();
				if (isset($_POST['service_id']) && !empty($_POST['service_id']))
				{
					$pjEmployeeServiceModel->reset()->setBatchFields(array('employee_id', 'service_id'));
					foreach ($_POST['service_id'] as $service_id)
					{
						$pjEmployeeServiceModel->addBatchRow(array($_POST['id'], $service_id));
					}
					$pjEmployeeServiceModel->insertBatch();
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminEmployees&action=pjActionIndex&err=AE01");
				
			} else {
				$arr = pjEmployeeModel::factory()->find($_GET['id'])->getData();
				if (count($arr) === 0)
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminEmployees&action=pjActionIndex&err=AE08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjEmployee');
				$this->set('arr', $arr);
				
				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.file')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					->where('t2.file IS NOT NULL')
					->where('t1.owner_id', $owner_id)
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				
				$this->set('service_arr', pjServiceModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where('t1.is_active', 1)
					->where('t1.owner_id', $owner_id)
					->orderBy('`name` ASC')
					->findAll()
					->getData()
				)->set('es_arr', pjEmployeeServiceModel::factory()
					->where('t1.employee_id', $arr['id'])
					->findAll()
					->getDataPair('id', 'service_id'));
				
				$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
				$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				if ((int) $this->option_arr['o_multi_lang'] === 1)
				{
					$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
					$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
					$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
					$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				}
				$this->appendJs('pjAdminEmployees.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionFreetime()
	{
		$this->checkLogin();
		if ($this->isAdmin())
		{
            $owner_id = intval($_SESSION['owner_id']);
			if ( isset($_POST['freetime']) && $_POST['freetime'] == 1 ) {
				
				$_POST['date'] = pjUtil::formatDate($_POST['date'], $this->option_arr['o_date_format']);
				
				if ( $_POST['end_ts'] > $_POST['start_ts'] ) {
					$data = $_POST;
					$data['owner_id'] = $owner_id;
					if ( isset($_POST['id']) && !empty($_POST['id']) ) {
						pjEmployeeFreetimeModel::factory()->set('id', $_POST['id'])->modify($data);
						
					} else 
						pjEmployeeFreetimeModel::factory($data)->insert();
				}
				
				if ( isset($_POST['pjAdmin']) && !empty($_POST['pjAdmin']) ) {
					
					//pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=pjActionIndex&date=" . $_POST['date']);
					
				}
			}
			
			$employee_arr = pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', sprintf("t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.locale='%u' AND t2.field='name'", $this->getLocaleId()), 'left outer')
                    ->where('t1.owner_id', $owner_id)
					->where('t2.owner_id', $owner_id)
                    ->findAll()
					->getData();
			
			$ef_arr = array();
			
			if ( isset($_GET['id']) && !empty($_GET['id']) ) {
				$ef_arr = pjEmployeeFreetimeModel::factory()
					->where('t1.id', $_GET['id'])
					->find($_GET['id'])
					->getData();
			}
			
			if ( ( (isset($_GET['date']) && !empty($_GET['date'])) || (isset($_GET['start_ts']) && !empty($_GET['start_ts'])) )  
					&& isset($_GET['employee_id']) && !empty($_GET['employee_id']) ) {
				
				if ( isset($_GET['date']) ) {
					$date = $_GET['date'];
					
				} else $date = date("Y-m-d", $_GET['start_ts']);
				
				$this->set('t_arr', pjAppController::getRawSlotsPerEmployeeAdmin($_GET['employee_id'], $date, $this->getForeignId()));
				
			} elseif ( count($ef_arr) > 0 ) {
				$this->set('t_arr', pjAppController::getRawSlotsPerEmployeeAdmin($ef_arr['employee_id'], date("Y-m-d", $ef_arr['start_ts']), $this->getForeignId()));
			
			} elseif ( isset($employee_arr) && count($employee_arr) > 0 ) {
				$this->set('t_arr', pjAppController::getRawSlotsPerEmployeeAdmin($employee_arr[0]['id'], date("Y-m-d"), $this->getForeignId()));
				
			} else {
				$this->set('t_arr', pjAppController::getRawSlots($this->getForeignId(), date("Y-m-d"), 'calendar', $this->option_arr));
			}
			
			$this->set('ef_arr', $ef_arr);
			$this->set('employee_arr', $employee_arr);
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminEmployees.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionCustomTimes() {
		$this->checkLogin();
		
		if ($this->isAdmin()) {
			
			if ( isset($_POST['customtine']) && $_POST['customtine'] == 1 ) {
				
				$data = array();
				$data['start_time'] = join(":", array($_POST['start_hour'], $_POST['start_minute']));
				$data['end_time'] = join(":", array($_POST['end_hour'], $_POST['end_minute']));
				$data['start_lunch'] = join(":", array($_POST['start_lunch_hour'], $_POST['start_lunch_minute']));
				$data['end_lunch'] = join(":", array($_POST['end_lunch_hour'], $_POST['end_lunch_minute']));
				$data['owner_id'] = intval($_SESSION['owner_id']);
				if ( isset($_POST['id']) && $_POST['id'] > 0 ) {
					$data['is_dayoff'] = isset($_POST['is_dayoff']) ? 'T' : 'F';
					pjCustomTimesModel::factory()->where('id', $_POST['id'])->modifyAll(array_merge($_POST, $data));
					
				} else pjCustomTimesModel::factory(array_merge($_POST, $data))->insert();
			}
			
			if (  isset($_GET['id']) && $_GET['id'] > 0 ) {
				$this->set('arr', pjCustomTimesModel::factory()
											->where('id', $_GET['id'])
											->find($_GET['id'])
											->getData());
			}
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminEmployees.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionEmployeeCustomTime() {
		$this->checkLogin();
		
		if ($this->isAdmin()) { 
		
			if ( isset($_GET['date']) && !empty($_GET['date']) ){
				$date = date('Y-m-d', strtotime( $_GET['date']));
					
			} else $date = date('Y-m-d');
			
			if ( isset($_POST['EmployeeCustomTime']) && $_POST['EmployeeCustomTime'] == 1 ) {
				$pjEmployeeCustomTime = pjEmployeeCustomTime::factory();
				$pjDateModel = pjDateModel::factory();
				foreach ($_POST as $k => $v) {
					
					if ( strpos($k, "id") !== false ) {
						$_array = explode('_', $k);
						
						if ( count($_array) > 2 ) {
							$data = array();
							$data['employee_id'] = $_array[2];
							$data['customtime_id'] = $v;
							$data['date'] = date('Y-m-d', strtotime(date('Y-m', strtotime($date)) . '-' . $_array[1]));
							$data['owner_id'] = intval($_SESSION['owner_id']);
							$pjEmployeeCustomTime
								->reset()
								->where('employee_id', $data['employee_id'])
								->where('date', $data['date'])
								->eraseAll();
							
							$pjDateModel
								->reset()
								->where('foreign_id', $data['employee_id'])
								->where('`type`', 'employee')
								->where('`date`', $data['date'])
								->eraseAll();
							
							if ( !empty($v) ) {
								$pjEmployeeCustomTime
									->reset()
									->setAttributes($data)
									->insert();
								
								$customtime = pjCustomTimesModel::factory()
										->find($v)
										->getData();
								
								if ( count($customtime) > 0 ) {
									$data_time['date'] = $data['date'];
									$data_time['foreign_id'] = $data['employee_id'];
									$data_time['type'] = 'employee';
									$data_time['start_time'] = $customtime['start_time'];
									$data_time['end_time'] = $customtime['end_time'];
									$data_time['start_lunch'] = $customtime['start_lunch'];
									$data_time['end_lunch'] = $customtime['end_lunch'];
									$data_time['is_dayoff'] = $customtime['is_dayoff'];
									
									$pjDateModel
										->reset()
										->setAttributes($data_time)
										->insert();
								}
							}
						}
					}
				}
				
				exit();
			}
			
			$pjEmployeeModel = pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
					->where('t1.is_active', 1)
					->orderBy('`name` ASC')
					->findAll();
			
			$employee_arr = $pjEmployeeModel->getData();
			$employee_ids = $pjEmployeeModel->getDataPair(null, 'id');
			$employee_ids = array_flip($employee_ids);
			$e_arr = array();
			$employee_hours = array();
			if (count($employee_arr) > 0 ) {
				if ( isset($_GET['employee_id']) && $_GET['employee_id'] > 0 ) {
				
					foreach ($employee_arr as $employee ) {
						if ( $_GET['employee_id'] == $employee['id'] ) {
							$e_arr = $employee;
							break;
						}
					}
				} else {
					$e_arr = $employee_arr[0];
				}
				
				if (isset($employee_ids[$e_arr['id']])) unset($employee_ids[$e_arr['id']]);
				
				$employee_ids = array_keys($employee_ids);
			
				foreach ($employee_arr as $k => $employee ) {
					$employee_customtime = pjEmployeeCustomTime::factory()
						->select('t1.*, t2.name')
						->join('pjCustomTimes', 't2.id=t1.customtime_id', 'inner')
						->where('employee_id', $employee['id'])
						->where('t1.date >=', date('Y-m', strtotime($date)) . '-0')
						->where('t1.date <', date('Y-m', strtotime($date)) . '-32')
						->findAll()
						->getDataPair('date');
					
					if ($e_arr['id'] == $employee['id']) {
						$e_arr = array_merge($e_arr, $employee_customtime);
					}
					
					$employee_arr[$k] = array_merge($employee_arr[$k], $employee_customtime);
				}
				
				$week = array( "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" );
				$week_start = $week[$this->option_arr['o_week_start']];
				$saturday_hours = 0;
				$sunday_hours = 0;
				$week_hours = 0;
				$month_hours = 0;
				$count_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($date)), strtotime($date));
				for ( $i = 1; $i <= $count_days; $i++) {
					
					$day = date('Y-m-d', strtotime(date('Y-m', strtotime($date)).'-' . $i));
					if ( $week_start == date('l', strtotime($day)) ) {
						$week_hours = 0;
					}
					
					$t_arr = pjAppController::getRawSlotsPerEmployee($e_arr['id'], $day, $this->getForeignId());
					
					if ($t_arr) {
						$week_hours += $t_arr['end_ts'] - $t_arr['start_ts'];
						$month_hours += $t_arr['end_ts'] - $t_arr['start_ts'];
						
						if ( "Saturday" == date('l', strtotime($day)) ) {
							$saturday_hours += $t_arr['end_ts'] - $t_arr['start_ts'];
							
						} elseif ( "Sunday" == date('l', strtotime($day)) )
							$sunday_hours += $t_arr['end_ts'] - $t_arr['start_ts'];
					}
					
					if ( $i < 7 && $week_start == date('l', strtotime($day) + 86400 ) ) {
						$_m = date('m', strtotime($date));
						
						if ( $_m == 1 ) {
							$y = date('Y', strtotime($date)) - 1;
							$_m = 12;
							$_date = date('Y-m-d', strtotime($y .'-'. $_m .'-'. date('d', strtotime($date))));
						} else {
							$_m = $_m - 1;
							$_date = date('Y-m-d', strtotime(date('Y', strtotime($date)) .'-'. $_m .'-'. date('d', strtotime($date))));
						}
						
						$_count_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($_date)) , strtotime($_date));
						for ( $j = $_count_days; $j > $_count_days - (7 - $i); $j--) {
							$_day = date('Y-m-d', strtotime(date('Y-m', strtotime($_date)).'-' . $j));
							$t_arr = pjAppController::getRawSlotsPerEmployee($e_arr['id'], $_day, $this->getForeignId());
							if ($t_arr) {
								$week_hours += ($t_arr['end_ts'] - $t_arr['start_ts']);
							}
						}
						
						
						$e_arr[$i] = $week_hours;
						$employee_hours[$e_arr['id']][$i] = $week_hours;
						
					}elseif ( $i >= 7 && $week_start == date('l', strtotime($day) + 86400 ) ) { 
						$e_arr[$i] = $week_hours;
						$employee_hours[$e_arr['id']][$i] = $week_hours;
					}
					
					foreach ( $employee_ids as $employee_id ) {
						
						if ( $i == 1 ) {
							$employee_hours[$employee_id]['saturday_hours'] = 0;
							$employee_hours[$employee_id]['sunday_hours'] = 0;
							$employee_hours[$employee_id]['month_hours'] = 0;
							$employee_hours[$employee_id]['week_hours'] = 0;
						}
						
						if ( $week_start == date('l', strtotime($day)) ) {
							$employee_hours[$employee_id]['week_hours'] = 0;
						}
						
						$t_arr = pjAppController::getRawSlotsPerEmployee($employee_id, $day, $this->getForeignId());
						
						if ($t_arr) {
							
							$employee_hours[$employee_id]['week_hours'] += $t_arr['end_ts'] - $t_arr['start_ts'];
							
							$employee_hours[$employee_id]['month_hours'] += $t_arr['end_ts'] - $t_arr['start_ts'];
							
							if ( "Saturday" == date('l', strtotime($day)) ) {
								$employee_hours[$employee_id]['saturday_hours'] += $t_arr['end_ts'] - $t_arr['start_ts'];
									
							} elseif ( "Sunday" == date('l', strtotime($day)) )
								$employee_hours[$employee_id]['sunday_hours'] += $t_arr['end_ts'] - $t_arr['start_ts'];
						}
						
						if ( $i < 7 && $week_start == date('l', strtotime($day) + 86400 ) ) {
							$_m = date('m', strtotime($date));
						
							if ( $_m == 1 ) {
								$y = date('Y', strtotime($date)) - 1;
								$_m = 12;
								$_date = date('Y-m-d', strtotime($y .'-'. $_m .'-'. date('d', strtotime($date))));
							} else {
								$_m = $_m - 1;
								$_date = date('Y-m-d', strtotime(date('Y', strtotime($date)) .'-'. $_m .'-'. date('d', strtotime($date))));
							}
						
							$_count_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($_date)) , strtotime($_date));
							for ( $j = $_count_days; $j > $_count_days - (7 - $i); $j--) {
								$_day = date('Y-m-d', strtotime(date('Y-m', strtotime($_date)).'-' . $j));
								$t_arr = pjAppController::getRawSlotsPerEmployee($employee_id, $_day, $this->getForeignId());
								if ($t_arr) {
									$employee_hours[$employee_id]['week_hours'] += ($t_arr['end_ts'] - $t_arr['start_ts']);
								}
							}
						
						
							$employee_hours[$employee_id][$i] = $employee_hours[$employee_id]['week_hours'];
						
						} elseif ( $i >= 7 && $week_start == date('l', strtotime($day) + 86400 ) ) {
							$employee_hours[$employee_id][$i] = $employee_hours[$employee_id]['week_hours'];
						}
						
					}
					
				}
				
				/*$e_arr["saturday_hours"] = $saturday_hours;
				$e_arr["sunday_hours"] = $sunday_hours;
				$e_arr["month_hours"] = $month_hours;*/
				$employee_hours[$e_arr['id']]['saturday_hours'] = $saturday_hours;
				$employee_hours[$e_arr['id']]['sunday_hours'] = $sunday_hours;
				$employee_hours[$e_arr['id']]['month_hours'] = $month_hours;
			}
			
			$this->set('employees_arr', $employee_arr)
				 ->set('e_arr', $e_arr)
				 ->set('employee_hours', $employee_hours) 
				 ->set('customtime_arr', pjCustomTimesModel::factory()
								->findAll()
								->getData());
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminEmployees.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		
		} else {
				
			$this->set('status', 2);
		}
	}
}
?>
