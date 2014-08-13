<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once PJ_CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminOptions extends pjAdmin
{
	public function pjActionIndex()
	{
		$this->checkLogin();

		if ($this->isAdmin())
		{
			$owner_id = $this->getOwnerId();
			if (isset($_GET['tab']) && in_array((int) $_GET['tab'], array(5,6)))
			{
				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
				$locale_arr = pjLocaleModel::factory()->select('t1.*')
					->orderBy('t1.sort ASC')->findAll()->getData();
						
				$lp_arr = array();
				foreach ($locale_arr as &$v)
				{
                    $v['id'] = 1;
					$lp_arr[$v['id']."_"] = $v['file']; //Hack for jquery $.extend, to prevent (re)order of numeric keys in object
				}
				$this->set('lp_arr', $locale_arr);

				$arr = array();
				$arr['i18n'] = pjMultiLangModel::factory()->where('owner_id', $owner_id)->getMultiLang($this->getForeignId(), 'pjCalendar');
				$this->set('arr', $arr);
				
				if ((int) $this->option_arr['o_multi_lang'] === 1)
				{
					$this->set('locale_str', pjAppController::jsonEncode($lp_arr));
					$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
				}
			} else {
				$tab_id = isset($_GET['tab']) && (int) $_GET['tab'] > 0 ? (int) $_GET['tab'] : 1;
				$arr = pjOptionModel::factory()
					->where('tab_id', $tab_id)
					->where('owner_id', $owner_id)
					->orderBy('t1.order ASC')
					->findAll()
					->getData();
				$this->set('arr', $arr);
				
				$tmp = $this->models['Option']->reset()->findAll()->getData();
				$o_arr = array();
				foreach ($tmp as $item)
				{
					$o_arr[$item['key']] = $item;
				}
				$this->set('o_arr', $o_arr);
			}
			$this->appendJs('jquery.tipsy.js', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendCss('jquery.tipsy.css', PJ_THIRD_PARTY_PATH . 'tipsy/');
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionInstall()
	{
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
			if ((int) $this->option_arr['o_multi_lang'] === 1)
			{
				pjObject::import('Model', array('pjLocale:pjLocale', 'pjLocale:pjLocaleLanguage'));
				$locale_arr = pjLocaleModel::factory()->select('t1.*, t2.title')
					->join('pjLocaleLanguage', 't2.iso=t1.language_iso', 'left outer')
					->orderBy('t1.sort ASC')->findAll()->getData();
				$this->set('locale_arr', $locale_arr);
			}
					
			$this->appendJs('pjAdminOptions.js');
		} else {
			$this->set('status', 2);
		}
	}
	
	public function pjActionPreview()
	{
		$this->setAjax(true);
		$as_pf = htmlspecialchars($_GET['as_pf']);
        if(isset($_GET['owner_id'])){
            $owner_id = intval($_GET['owner_id']);
        }
        if(empty($owner_id)){
            $as_pf = str_replace('_hey_', '', $as_pf);
            require_once("../../includes/configsettings.php");
            $dns = sprintf("mysql:dbname=%s;host=%s", PJ_DB, PJ_HOST);
            $dbh = new PDO($dns, PJ_USER, PJ_PASS);
            $sql = "SELECT nuser_id FROM tbl_user_mast WHERE vuser_login LIKE '$as_pf%' LIMIT 0,1";
            $sth  = $dbh->prepare($sql);
            $sth->execute();
            $owner_id = intval($sth->fetchColumn());
        }
        $_SESSION['use_front_owner_id'] = true;
        $_SESSION['front_owner_id'] = $owner_id;
        $this->set('owner_id', $owner_id);
        $this->set('as_pf', $as_pf);
		$this->set('style', pjStyleModel::factory()
			->findAll()
			->getData());
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionUpdate()
	{
		$this->checkLogin();
		$owner_id = intval($_SESSION['owner_id']);
		if ($this->isAdmin())
		{
			if (isset($_POST['options_update']))
			{
				if (isset($_POST['tab']) && in_array($_POST['tab'], array(5, 6)))
				{
					if (isset($_POST['i18n']))
					{
						$data = $_POST['i18n'];
						pjMultiLangModel::factory()->updateMultiLang($data, $owner_id, $this->getForeignId(), 'pjCalendar', 'data');
					}
				} else {
					$OptionModel = new pjOptionModel();
					$OptionModel
						//->where('foreign_id', $this->getForeignId())
						->where('type', 'bool')
						->where('tab_id', $_POST['tab'])
						->where('owner_id', $owner_id)
						->modifyAll(array('value' => '1|0::0'));
						
					foreach ($_POST as $key => $value)
					{
						if (preg_match('/value-(string|text|int|float|enum|bool|color)-(.*)/', $key) === 1)
						{
							list(, $type, $k) = explode("-", $key);
							if (!empty($k))
							{
								$OptionModel
									->reset()
									//->where('foreign_id', $this->getForeignId())
									->where('`key`', $k)
									->where('owner_id', $owner_id)
									->limit(1)
									->modifyAll(array('value' => $value));
							}
						}
					}
				}
				if (isset($_POST['tab']))
				{
					switch ($_POST['tab'])
					{
						case '1':
							$err = 'AO01';
							break;
						case '2':
							$err = 'AO02';
							break;
						case '3':
							$err = 'AO03';
							break;
						case '4':
							$err = 'AO04';
							break;
						case '5':
							$err = 'AO05';
							break;
						case '6':
							$err = 'AO06';
							break;
						case '7':
							$err = 'AO07';
							break;
						case '8':
							$err = 'AO08';
							break;
					}
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=" . @$_POST['next_action'] . "&tab=" . @$_POST['tab'] . "&err=$err");
			}
		} else {
			$this->set('status', 2);
		}
	}
	
	Public function getposts() {
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
			$con = mysqli_connect(PJ_HOST, PJ_USER, PJ_PASS, PJ_DB);
			// Check connection
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
			$sql = "SELECT * FROM " . PJ_PREFIX . "posts where post_type IN ('post', 'page')";
			
			if ( isset($_GET['search']) ) {
				$search = $_GET['search'];
				$sql .= "AND post_title LIKE '%$search%' ";
			}
			
			$sql .= " AND post_status = 'publish' ORDER BY post_title ASC";
			
			$result = mysqli_query($con,  $sql);
			
			$posts = array();
			
			while($row = mysqli_fetch_array($result)) {
				$posts[] = $row;
			}
			
			$this->set('posts_arr', $posts);
			
			mysqli_close($con);
			
		}
	
	}
	
	public function pjActionInsertContent() {
		$this->setAjax(true);
	
		if ($this->isXHR() && $this->isLoged() && $this->isAdmin())
		{
				
			if (isset($_POST['id'])) {
	
				$con = mysqli_connect(PJ_HOST, PJ_USER, PJ_PASS, PJ_DB);
				// Check connection
				if (mysqli_connect_errno()) {
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}
					
				$sql = "SELECT * FROM " . PJ_PREFIX . "posts where post_type IN ('post', 'page')";
					
				$sql .= " AND ID = ". $_POST['id'] ." AND post_status = 'publish' ORDER BY post_title ASC";
					
				$result = mysqli_query($con,  $sql);
					
				$post = array();
	
				while($row = mysqli_fetch_array($result)) {
					$post = $row;
				}
				
				if ( count($post) > 0 ) {
					$post['post_content'] = $post['post_content'] . '<iframe width="100%" height="1000px" src="'. PJ_INSTALL_URL .'index.php?controller=pjAdminOptions&action=pjActionPreview&as_pf='. PREFIX .'">';
				
					$sql = "UPDATE " . PJ_PREFIX . "posts SET post_content='". $post['post_content'] ."'";
					
					$sql .= "WHERE ID = ". $_POST['id'];
				}
				
				mysqli_query($con,  $sql);
				
				mysqli_close($con);
			}
				
		}
	
		exit();
	}
	
	public function pjActionStyle() {
		$this->checkLogin();
		
		if ($this->isAdmin())
		{
            $data = $_POST;
			$owner_id = $this->getOwnerId();
            $data['owner_id'] = $owner_id;

			if ( isset($_POST['form_style']) && $_POST['form_style'] > 0 && isset($_POST['id']) && $_POST['id'] > 0 ) {
				pjStyleModel::factory()->set('id', $data['id'])->modify($data);
				
			} elseif ( isset($_POST['form_style']) && $_POST['form_style'] > 0 ) {
				pjStyleModel::factory($data)->insert();
			}
			
			$this->set('arr', pjStyleModel::factory()
						->findAll()
						->getData()
					);
		}
	}
}
?>
