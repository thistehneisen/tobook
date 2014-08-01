<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminServices extends pjAdmin
{
	public function pjActionCreate()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$owner_id = intval($_SESSION['owner_id']);
			if (isset($_POST['service_create']))
			{
				$data = array();
				$data['calendar_id'] = $this->getForeignId();
				$data['owner_id'] = $owner_id;
 				$id = pjServiceModel::factory(array_merge($_POST, $data))->insert()->getInsertId();
				if ($id !== false && (int) $id > 0)
				{
					if (isset($_POST['employee_id']) && !empty($_POST['employee_id']))
					{
						$pjEmployeeServiceModel = pjEmployeeServiceModel::factory()->setBatchFields(array('employee_id', 'service_id','owner_id'));
						foreach ($_POST['employee_id'] as $employee_id)
						{
							$pjEmployeeServiceModel->addBatchRow(array($employee_id, $id, $owner_id));
						}
						$pjEmployeeServiceModel->insertBatch();
						
						$es_arr = $pjEmployeeServiceModel
						->where('t1.service_id', $id)
						->where('t1.owner_id', $owner_id)
						->findAll()
						->getData();
							
						foreach ($es_arr as $es ) {
						
							if ( isset($_POST['employee_id']) && is_array($_POST['employee_id']) && in_array( $es['employee_id'], $_POST['employee_id'])){
									
								$es['plustime'] = $_POST['plustime'][$es['employee_id']];
								$pjEmployeeServiceModel->set('id', $es['id'])->modify($es);
							}
						}
						
					}
					
					if (isset($_POST['resources_id']) && !empty($_POST['resources_id']))
					{
						$pjResourcesServiceModel = pjResourcesServiceModel::factory()->setBatchFields(array('resources_id', 'service_id','owner_id'));
						foreach ($_POST['resources_id'] as $resources_id)
						{
							$pjResourcesServiceModel->addBatchRow(array($resources_id, $id, $owner_id));
						}
						$pjResourcesServiceModel->insertBatch();
					
					}
					
					if (isset($_POST['extra_id']) && !empty($_POST['extra_id']))
					{
						$pjServiceExtraServiceModel = pjServiceExtraServiceModel::factory()->setBatchFields(array('extra_id', 'service_id', 'owner_id'));
						foreach ($_POST['extra_id'] as $extra_id)
						{
							$pjServiceExtraServiceModel->addBatchRow(array($extra_id, $id, $owner_id));
						}
						$pjServiceExtraServiceModel->insertBatch();
							
					}
					
					$err = 'AS03';
					if (isset($_POST['i18n']))
					{
						pjMultiLangModel::factory()->saveMultiLang($_POST['i18n'], $owner_id, $id, 'pjService');
					}
				} else {
					$err = 'AS04';
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionIndex&err=$err");
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
				
				$this->set('employee_arr', pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->where('t1.is_active', 1)
					->where('t1.owner_id', $owner_id)
					->orderBy('`name` ASC')
					->findAll()
					->getData()
				);
				
				$this->set('category_arr', pjServiceCategoryModel::factory()
						->select('t1.*')
						->where('t1.owner_id', $owner_id)
						->orderBy('t1.name ASC')
						->findAll()
						->getData()
				);
				
				$this->set('extra_arr', pjExtraServiceModel::factory()
						->select('t1.*')
						->where('t1.owner_id', $owner_id)
						->orderBy('t1.name ASC')
						->findAll()
						->getData()
				);
				
				$this->set('resources_arr', pjResourcesModel::factory()
						->select('t1.*')
						->where('t1.owner_id', $owner_id)
						->orderBy('t1.name ASC')
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
				}
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminServices.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionDeleteService()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjServiceModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjMultiLangModel::factory()->where('model', 'pjService')->where('foreign_id', $_GET['id'])->eraseAll();
				pjEmployeeServiceModel::factory()->where('service_id', $_GET['id'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteServiceCategory() {
		
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjServiceCategoryModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteExtraService() {
	
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjExtraServiceModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteResources() {
	
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjResourcesModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteServiceCustomTime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_GET['id']) && (int) $_GET['id'] > 0 && pjServiceTimeModel::factory()->set('id', $_GET['id'])->erase()->getAffectedRows() == 1)
			{
				//pjMultiLangModel::factory()->where('model', 'pjService')->where('foreign_id', $_GET['id'])->eraseAll();
				//pjEmployeeServiceModel::factory()->where('service_id', $_GET['id'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteServiceBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjServiceModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjMultiLangModel::factory()->where('model', 'pjService')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				pjEmployeeServiceModel::factory()->whereIn('service_id',$_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}

	public function pjActionDeleteServiceBulkCategory()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjServiceCategoryModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}

	public function pjActionDeleteExtraServiceBulk()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjExtraServiceModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteServiceBulkResources()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjResourcesModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionDeleteServiceBulkCustomTime()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if (isset($_POST['record']) && !empty($_POST['record']))
			{
				pjServiceTimeModel::factory()->whereIn('id', $_POST['record'])->eraseAll();
				//pjMultiLangModel::factory()->where('model', 'pjService')->whereIn('foreign_id', $_POST['record'])->eraseAll();
				//pjEmployeeServiceModel::factory()->whereIn('service_id',$_POST['record'])->eraseAll();
				pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
			}
			pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
		}
		exit;
	}
	
	public function pjActionGetService()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			if ( isset($_GET['foreign_id']) && $_GET['foreign_id'] > 0 && isset($_GET['type']) && $_GET['type'] == 'service' ) {
				
				$pjServiceModel = pjServiceTimeModel::factory()
				->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.foreign_id AND t2.field='name'", 'left outer')
				->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.foreign_id AND t3.field='description'", 'left outer')
				->where('t1.foreign_id', $_GET['foreign_id']);
				
				if (isset($_GET['q']) && !empty($_GET['q']))
				{
					$q = str_replace(array('_', '%'), array('\_', '\%'), trim($_GET['q']));
					$pjServiceModel->where('t2.content LIKE', "%$q%");
					$pjServiceModel->orWhere('t3.content LIKE', "%$q%");
				}
				
				$column = 'name';
				$direction = 'ASC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
				
				$total = $pjServiceModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				
				$data = $pjServiceModel
				->select(sprintf("t1.*, t2.content AS `name`"))->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
					
				foreach ($data as $k => $v)
				{
					$data[$k]['price_format'] = pjUtil::formatCurrencySign(number_format($v['price'], 2), $this->option_arr['o_currency']);
				}
					
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} elseif ( isset($_GET['type']) && $_GET['type'] == 'category' ) {
				$pjServiceCategoryModel = pjServiceCategoryModel::factory();
				
				$column = 'name';
				$direction = 'ASC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
				
				$total = $pjServiceCategoryModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				
				$data = $pjServiceCategoryModel
				->select(sprintf("t1.*, t1.name AS `name`"))->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
					
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} elseif ( isset($_GET['type']) && $_GET['type'] == 'extraservice' ) {
				
				$pjExtraServiceModel = pjExtraServiceModel::factory();
				
				$column = 'name';
				$direction = 'ASC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
				
				$total = $pjExtraServiceModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				
				$data = $pjExtraServiceModel
				->select(sprintf("t1.*, t1.name AS `name`"))->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
					
				pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} elseif ( isset($_GET['type']) && $_GET['type'] == 'resources' ) {
				
					$pjResourcesModel = pjResourcesModel::factory();
				
					$column = 'name';
					$direction = 'ASC';
					if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
					{
						$column = $_GET['column'];
						$direction = strtoupper($_GET['direction']);
					}
				
					$total = $pjResourcesModel->findCount()->getData();
					$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
					$pages = ceil($total / $rowCount);
					$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
					$offset = ((int) $page - 1) * $rowCount;
					if ($page > $pages)
					{
						$page = $pages;
					}
				
					$data = $pjResourcesModel
					->select(sprintf("t1.*, t1.name AS `name`"))->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
						
					pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
				
			} else {
				$pjServiceModel = pjServiceModel::factory()
					->join('pjMultiLang', "t2.model='pjService' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->join('pjMultiLang', "t3.model='pjService' AND t3.foreign_id=t1.id AND t3.field='description'", 'left outer')
					->join('pjServiceCategory', 't1.category_id=t4.id', 'left outer');
					// ->where('t1.calendar_id', $this->getForeignId());
				
				if (isset($_GET['q']) && !empty($_GET['q']))
				{
					$q = str_replace(array('_', '%'), array('\_', '\%'), trim($_GET['q']));
					$pjServiceModel->where('t2.content LIKE', "%$q%");
					$pjServiceModel->orWhere('t3.content LIKE', "%$q%");
				}
	
				if (isset($_GET['is_active']) && strlen($_GET['is_active']) > 0 && in_array($_GET['is_active'], array(1, 0)))
				{
					$pjServiceModel->where('t1.is_active', $_GET['is_active']);
				}
	
				$column = 'name';
				$direction = 'ASC';
				if (isset($_GET['direction']) && isset($_GET['column']) && in_array(strtoupper($_GET['direction']), array('ASC', 'DESC')))
				{
					$column = $_GET['column'];
					$direction = strtoupper($_GET['direction']);
				}
	
				$total = $pjServiceModel->findCount()->getData();
				$rowCount = isset($_GET['rowCount']) && (int) $_GET['rowCount'] > 0 ? (int) $_GET['rowCount'] : 10;
				$pages = ceil($total / $rowCount);
				$page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
				$offset = ((int) $page - 1) * $rowCount;
				if ($page > $pages)
				{
					$page = $pages;
				}
				$owner_id = intval($_SESSION['owner_id']);
				$data = $pjServiceModel
					->select(sprintf("t1.*, t2.content AS `name`, t4.name AS `category`, 
						(SELECT COUNT(es.id)
							FROM `%1\$s` AS `es`
							INNER JOIN `%2\$s` AS `e` ON `e`.`id` = `es`.`employee_id`
							WHERE `es`.`service_id` = `t1`.`id`
							AND `es`.`owner_id` = %3\$d
							LIMIT 1) AS `employees`
						", pjEmployeeServiceModel::factory()->getTable(), pjEmployeeModel::factory()->getTable(), $owner_id))
					->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();

				foreach ($data as $k => $v)
				{
					$data[$k]['price_format'] = pjUtil::formatCurrencySign(number_format($v['price'], 2), $this->option_arr['o_currency']);
				}
					
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
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionSaveService()
	{
		$this->setAjax(true);
		$owner_id = intval($_SESSION['owner_id']);
		if ($this->isXHR() && $this->isLoged())
		{
			$pjServiceModel = pjServiceModel::factory();
			if (!in_array($_POST['column'], $pjServiceModel->getI18n()))
			{
				$pjServiceModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $owner_id, $_GET['id'], 'pjService', 'data');
			}
		}
		exit;
	}
	
	public function pjActionSaveResources()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjResourcesModel = pjResourcesModel::factory();
			if (!in_array($_POST['column'], $pjResourcesModel->getI18n()))
			{
				$pjResourcesModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} 
		}
		exit;
	}
	
	public function pjActionSaveExtraService()
	{
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged())
		{
			$pjExtraServiceModel = pjExtraServiceModel::factory();
			if (!in_array($_POST['column'], $pjExtraServiceModel->getI18n()))
			{
				$pjExtraServiceModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			}
		}
		exit;
	}
	
	public function pjActionSaveServiceCustomTime()
	{
		$this->setAjax(true);
		$owner_id = intval($_SESSION['owner_id']);
		if ($this->isXHR() && $this->isLoged())
		{
			$pjServiceModel = pjServiceTimeModel::factory();
			if (!in_array($_POST['column'], $pjServiceModel->getI18n()))
			{
				$pjServiceModel->set('id', $_GET['id'])->modify(array($_POST['column'] => $_POST['value']));
			} else {
				pjMultiLangModel::factory()->updateMultiLang(array($this->getLocaleId() => array($_POST['column'] => $_POST['value'])), $owner_id, $_GET['id'], 'pjService', 'data');
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
			if (isset($_POST['service_update']) && isset($_POST['id']) && (int) $_POST['id'] > 0)
			{
				pjServiceModel::factory()->set('id', $_POST['id'])->modify($_POST);
				if (isset($_POST['i18n']))
				{
					pjMultiLangModel::factory()->updateMultiLang($_POST['i18n'], $owner_id, $_POST['id'], 'pjService', 'data');
				}
				
				$pjEmployeeServiceModel = pjEmployeeServiceModel::factory();
				$pjEmployeeServiceModel->where('service_id', $_POST['id'])->eraseAll();
				if (isset($_POST['employee_id']) && !empty($_POST['employee_id']))
				{
					$pjEmployeeServiceModel->reset()->setBatchFields(array('employee_id', 'service_id', 'owner_id'));
					foreach ($_POST['employee_id'] as $employee_id)
					{
						$pjEmployeeServiceModel->addBatchRow(array($employee_id, $_POST['id'], $owner_id));
					}
					$pjEmployeeServiceModel->insertBatch();
					
					$es_arr = $pjEmployeeServiceModel
							->where('t1.service_id', $_POST['id'])
							->findAll()
							->getData();
					
					foreach ($es_arr as $es ) {
						
						if ( isset($_POST['employee_id']) && is_array($_POST['employee_id']) && in_array( $es['employee_id'], $_POST['employee_id'])){
							
							$es['plustime'] = $_POST['plustime'][$es['employee_id']];
							$pjEmployeeServiceModel->set('id', $es['id'])->modify($es);
						}
					}
					
				}
				
				$pjResourcesServiceModel = pjResourcesServiceModel::factory();
				$pjResourcesServiceModel->where('service_id', $_POST['id'])->eraseAll();
				if (isset($_POST['resources_id']) && !empty($_POST['resources_id']))
				{
					$pjResourcesServiceModel->reset()->setBatchFields(array('resources_id', 'service_id', 'owner_id'));
					foreach ($_POST['resources_id'] as $resources_id)
					{
						$pjResourcesServiceModel->addBatchRow(array($resources_id, $_POST['id'], $owner_id));
					}
					$pjResourcesServiceModel->insertBatch();
				}
				
				$pjServiceExtraServiceModel = pjServiceExtraServiceModel::factory();
				$pjServiceExtraServiceModel->where('service_id', $_POST['id'])->eraseAll();
				if (isset($_POST['extra_id']) && !empty($_POST['extra_id']))
				{
					$pjServiceExtraServiceModel->reset()->setBatchFields(array('extra_id', 'service_id', 'owner_id'));
					foreach ($_POST['extra_id'] as $extra_id)
					{
						$pjServiceExtraServiceModel->addBatchRow(array($extra_id, $_POST['id'], $owner_id));
					}
					$pjServiceExtraServiceModel->insertBatch();
				}
				
				pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminServices&action=pjActionIndex&err=AS01");
				
			} else {
				$arr = pjServiceModel::factory()->find($_GET['id'])->getData();
				if (empty($arr))
				{
					pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminServices&action=pjActionIndex&err=AS08");
				}
				$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjService');
				$this->set('arr', $arr);
				
				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
                //remove join from pjLocaleLanguage to avoid repeated fields
				$locale_arr = pjLocaleModel::factory()->select('t1.*')
					// ->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left')
					// ->where('t2.file IS NOT NULL')
					->orderBy('t1.sort ASC')->findAll()->getData();
				
				$lp_arr = array();
				foreach ($locale_arr as $item)
				{
					$lp_arr[$item['id']."_"] = $item['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);
				
				$this->set('employee_arr', pjEmployeeModel::factory()
					->select('t1.*, t2.content AS `name`')
					->join('pjMultiLang', "t2.model='pjEmployee' AND t2.foreign_id=t1.id AND t2.field='name'", 'left outer')
					->where('t1.is_active', 1)
					->where('t1.owner_id', $owner_id)
					->orderBy('`name` ASC')
					->findAll()
					->getData()
				);
				
				$this->set('resources_arr', pjResourcesModel::factory()
						->orderBy('`name` ASC')
						->findAll()
						->getData()
				);
				
				$this->set('rs_arr', pjResourcesServiceModel::factory()->where('t1.service_id', $arr['id'])->findAll()->getDataPair(null, 'resources_id'));
				
				$this->set('extra_arr', pjExtraServiceModel::factory()
						->orderBy('`name` ASC')
						->findAll()
						->getData()
				);
				
				$this->set('ses_arr', pjServiceExtraServiceModel::factory()->where('t1.service_id', $arr['id'])->findAll()->getDataPair(null, 'extra_id'));
				
				$this->set('category_arr', pjServiceCategoryModel::factory()
						->select('t1.*')
						->orderBy('t1.name ASC')
						->findAll()
						->getData()
				);
				
				$this->set('es_arr', pjEmployeeServiceModel::factory()->where('t1.service_id', $arr['id'])->findAll()->getData());
				
				$this->appendJs('jquery.multiselect.min.js', PJ_THIRD_PARTY_PATH . 'multiselect/');
				$this->appendCss('jquery.multiselect.css', PJ_THIRD_PARTY_PATH . 'multiselect/');
				
				$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
				if ((int) $this->option_arr['o_multi_lang'] === 1)
				{
					$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
					$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				}
				$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
				$this->appendJs('pjAdminServices.js');
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionCustomTime() {
		
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['service_custom_time'])) {
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0) {
					$foreign_id = (int) $_POST['foreign_id'];
					
				} else return; 
				
				if (isset($_POST['type']) && in_array($_POST['type'], $this->types)) {
					$type = $_POST['type'];
					
				} else $type = 'service';
		
				pjServiceTimeModel::factory($_POST)->insert();
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) )
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminServices&action=pjActionCustomTime&type=%s&foreign_id=%u&err=AT02",
					PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
		
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionCustomTime&err=AT02");
			
			} elseif ( isset($_POST['service_update_custom_time']) ) {

				pjServiceTimeModel::factory()->set('id', $_POST['id'])->modify($_POST);
				
				if (isset($_POST['foreign_id']) && (int) $_POST['foreign_id'] > 0 && isset($_POST['type']) )
				{
					pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminServices&action=pjActionCustomTime&type=%s&foreign_id=%u&err=AT02",
					PJ_INSTALL_URL, $_POST['type'], $_POST['foreign_id']));
				}
				
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionCustomTime&err=AT02");
									
			}
			
		
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
				
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdateCustomTime()
	{
		$this->checkLogin();
	
		if ($this->isAdmin())
		{
		
			$arr = pjServiceTimeModel::factory()->find($_GET['id'])->getData();
			if (empty($arr))
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminServices&action=pjActionIndex&err=AS08");
			}
			
			$this->set('arr', $arr);

			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionCategory() {
		
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if (isset($_POST['service_category'])) {
		
				if ( !isset($_POST['show_front']) || empty($_POST['show_front']) ) $_POST['show_front'] = off;
				$data = $_POST;
				$data['owner_id'] = intval($_SESSION['owner_id']);
				pjServiceCategoryModel::factory($data)->insert();
		
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionCategory");
					
			} elseif ( isset($_POST['service_update_category']) ) {
		
				if ( !isset($_POST['show_front']) || empty($_POST['show_front']) ) $_POST['show_front'] = off;
				
				$data = $_POST;
				$data['owner_id'] = intval($_SESSION['owner_id']);
				pjServiceCategoryModel::factory()->set('id', $data['id'])->modify($data);
		
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionCategory");
					
			}
				
		
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdateCategory()
	{
		$this->checkLogin();
	
		if ($this->isAdmin())
		{
	
			$arr = pjServiceCategoryModel::factory()->find($_GET['id'])->getData();
			if (empty($arr))
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminServices&action=pjActionIndex&err=AS08");
			}
				
			$this->set('arr', $arr);
	
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
			
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionResources() {
	
		$this->checkLogin();
	
		if ($this->isAdmin())
		{
			if (isset($_POST['service_resources'])) {
				$data = $_POST;
				$data['owner_id'] = intval($_SESSION['owner_id']);
				pjResourcesModel::factory($data)->insert();
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionResources");
					
			} elseif ( isset($_POST['service_resources_update']) ) {
	
				pjResourcesModel::factory()->set('id', $_POST['id'])->modify($_POST);
	
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminServices&action=pjActionResources");
					
			}
	
	
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
	
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionUpdateResources()
	{
		$this->checkLogin();
	
		if ($this->isAdmin())
		{
	
			$arr = pjResourcesModel::factory()->find($_GET['id'])->getData();
			if (empty($arr))
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminServices&action=pjActionResources&err=AS08");
			}
	
			$this->set('arr', $arr);
	
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
				
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionExtraService() {
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			$pjExtraServiceModel = pjExtraServiceModel::factory();
			if ( isset($_POST['extra_service']) && $_POST['extra_service'] == 1 ) {
				$data = $_POST;
				$data['owner_id'] = intval($_SESSION['owner_id']);
				if (isset($_POST['id']) && $_POST['id'] > 0 ) {
					$pjExtraServiceModel->where('id', $_POST['id'])->modifyAll($_POST);
					
				} else pjExtraServiceModel::factory($data)->insert();
			}
		
			if ( isset($_GET['id']) && $_GET['id'] > 0 ) {
				$this->set('arr', $pjExtraServiceModel->find($_GET['id'])->getData());
			}
			
			$this->appendJs('jquery.validate.min.js', PJ_THIRD_PARTY_PATH . 'validate/');
			$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminServices.js');
			$this->appendJs('index.php?controller=pjAdmin&action=pjActionMessages', PJ_INSTALL_URL, true);
		
		} else {
			$this->set('status', 2);
		}		
	}
}
?>
