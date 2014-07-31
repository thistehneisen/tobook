<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminMaps extends pjAdmin
{
	function deleteFile()
	{
		$this->isAjax = true;
		
		if ($this->isXHR())
		{
			if ($this->isDemo())
			{
				header("Content-type: application/json; charset=utf-8");
				echo '{"code":7}';
				exit;
			}
			
			$map = UPLOAD_PATH . 'maps/map.jpg';
			if (is_file($map))
			{
				@unlink($map);
				pjObject::import('Model', 'pjTable');
				$pjTableModel = new pjTableModel();
				$pjTableModel->truncate();
			}
		}
	}
	
	function deleteSeat()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjSeat');
			$pjSeatModel = new pjSeatModel();
			$pjSeatModel->delete(array('id' => $_POST['id']));
		}
	}
	
	function saveSeat()
	{
		$this->isAjax = true;
	
		if ($this->isXHR())
		{
			pjObject::import('Model', 'pjTable');
			$pjTableModel = new pjTableModel();
			
			$sdata = $resp = array();
			list($id, $sdata['width'], $sdata['height'], $sdata['left'], $sdata['top'],) = explode("|", $_POST['hidden']);
			if ((int) $id > 0)
			{
				# Update
				$pjTableModel->update(array_merge($sdata, $_POST), array('id' => $id, 'limit' => 1));
				$resp = array('code' => 201, 'id' => $id);
			} else {
				# Save
				$insert_id = $pjTableModel->save(array_merge($sdata, $_POST));
				if ($insert_id !== false && (int) $insert_id > 0)
				{
					$resp = array('code' => 200, 'id' => $insert_id);
				} else {
					$resp = array('code' => 100);
				}
			}
			
			pjObject::import('Component', 'pjServices_JSON');
			$pjServices_JSON = new pjServices_JSON();
			
			header("Content-Type: application/json; charset=utf-8");
			echo $pjServices_JSON->encode($resp);
			exit;
		}
	}
/**
 * List maps
 *
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', 'pjTable');
				$pjTableModel = new pjTableModel();
					
				if (isset($_POST['map_update']))
				{
					if ($this->isDemo())
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminMaps&action=index&err=7");
					}

					if (isset($_FILES['path']))
					{
						pjObject::import('Component', 'pjImage');
						$pjImage = new pjImage();
						if ($pjImage->load($_FILES['path']))
						{
							$dst = UPLOAD_PATH . 'maps/map.jpg';
							$pjImage->loadImage()->saveImage($dst);
						}
					}
					
					if (isset($_POST['use_map']) && (int) $this->option_arr['use_map'] === 0)
					{
						$this->models['pjOption']->update(array('value' => '1|0::1'), array('`key`' => 'use_map', 'limit' => 1));
					} elseif (!isset($_POST['use_map']) && (int) $this->option_arr['use_map'] === 1) {
						$this->models['pjOption']->update(array('value' => '1|0::0'), array('`key`' => 'use_map', 'limit' => 1));
					}
					
					if (!isset($_POST['seats']))
					{
						$pjTableModel->truncate();
					}
					
					if (isset($_POST['seats']))
					{
						$s1_arr = array_values($pjTableModel->getAllPair('id', 'id'));
						$s2_arr = array();
						$sdata = array();
						foreach ($_POST['seats'] as $seat)
						{
							list($id, $sdata['width'], $sdata['height'], $sdata['left'], $sdata['top'], $sdata['name'], $sdata['seats'], $sdata['minimum']) = explode("|", $seat);
							$s2_arr[] = $id;
							$pjTableModel->update($sdata, array('limit' => 1, 'id' => $id));
							//TODO Refactoring: Bulk insert
						}
						$diff = array_diff($s1_arr, $s2_arr);
						if (count($diff) > 0)
						{
							$pjTableModel->delete(array('id' => array("('".join("','", $diff)."')", 'IN', 'null')));
						}
					}
					
					if (isset($_POST['seats_new']))
					{
						$sdata = array();
						foreach ($_POST['seats_new'] as $seat)
						{
							list(, $sdata['width'], $sdata['height'], $sdata['left'], $sdata['top'], $sdata['name'], $sdata['seats'], $sdata['minimum']) = explode("|", $seat);
							$pjTableModel->save($sdata);
							//TODO Refactoring: Bulk insert
						}
					}
					
					pjUtil::redirect(sprintf("%s?controller=pjAdminMaps&action=index&id=%u&err=5&tab_id=%s", $_SERVER['PHP_SELF'], 1, $_POST['tab_id']));
					
				} else {
					$this->tpl['seat_arr'] = $pjTableModel->getAll();
					
					$this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					
					$this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
					$this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
					
					$this->js[] = array('file' => 'jquery.ui.mouse.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					$this->js[] = array('file' => 'jquery.ui.draggable.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
					
					$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
					$this->js[] = array('file' => 'pjAdminMaps.js', 'path' => JS_PATH);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}
?>