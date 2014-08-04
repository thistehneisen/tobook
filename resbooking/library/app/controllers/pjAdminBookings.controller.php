<?php
if (! defined ( "ROOT_PATH" )) {
	header ( "HTTP/1.1 403 Forbidden" );
	exit ();
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminBookings extends pjAdmin {
	function _getPaper($date) {
		pjObject::import ( 'Model', array (
				'pjBooking',
				'pjBookingTable',
				'pjTable' 
		) );
		$pjBookingModel = new pjBookingModel ();
		$pjBookingTableModel = new pjBookingTableModel ();
		$pjTableModel = new pjTableModel ();
		
		$opts = array ();
		$opts ['t1.status'] = 'confirmed';
		$opts ['DATE(t1.dt)'] = array (
				sprintf ( "'%s'", $date ),
				'=',
				'null' 
		);
		
		// $arr_date = $pjBookingModel->getAll(array_merge($opts, array('group_by' => 't1.dt', 'col_name' => 't1.dt', 'direction' => 'ASC')));
		
		$arr = $pjBookingModel->getAll ( array_merge ( $opts, array (
				'col_name' => 't1.dt',
				'direction' => 'asc' 
		) ) );
		$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjTableModel->getTable (), 'TT', array (
				'TT.id' => 't1.table_id' 
		), array (
				'TT.name' 
		) );
		foreach ( $arr as $k => $booking ) {
			$arr [$k] ['table_arr'] = $pjBookingTableModel->getAll ( array (
					't1.booking_id' => $booking ['id'] 
			) );
		}
		// return array( 'arr' => $arr, 'arr_date' => $arr_date);
		return $arr;
	}
	function _getSchedule($date, $wt_arr) {
		pjObject::import ( 'Model', array (
				'pjBooking',
				'pjTable' 
		) );
		$pjBookingModel = new pjBookingModel ();
		$pjTableModel = new pjTableModel ();
		
		$opts = array ();
		$arr = $pjTableModel->getAll ( array_merge ( $opts, array (
				'col_name' => 't1.name',
				'direction' => 'asc' 
		) ) );
		foreach ( $arr as $k => $table ) {
			$arr [$k] ['hour_arr'] = $pjBookingModel->getBookings ( $table ['id'], $date, $wt_arr );
		}
		return $arr;
	}
	function delete() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				if ($this->isDemo ()) {
					$_GET ['err'] = 'AA07';
					$this->index ();
					return;
				}
				
				if ($this->isXHR ()) {
					$this->isAjax = true;
					$id = $_POST ['id'];
					$template = isset($_POST['template']) ? $_POST['template'] : null ;
					$menu = isset($_POST['menu']) ? $_POST['menu'] : null ;
					$tables_group = isset($_POST['tables_group']) ? $_POST['tables_group'] : null ;
				} else {
					$id = $_GET ['id'];
					$template = isset($_GET['template']) ? $_GET['template'] : null;
					$menu = isset($_GET['menu']) ? $_GET['menu'] : null;
					$tables_group = isset($_GET['tables_group']) ? $_GET['tables_group'] : null ;
				}
				
				pjObject::import ( 'Model', array('pjBooking', 'pjTemplate', 'pjMenu', 'pjTablesGroup') );
				
				$pjBookingModel = new pjBookingModel ();
				$pjTemplate = new pjTemplateModel();
				$pjMenu = new pjMenuModel();
				$pjTablesGroup = new pjTablesGroupModel();
				
				if ( isset($template) && $template == 1 ) {
					
					$arr = $pjTemplate->get ( $id );
					if (count ( $arr ) == 0) {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB08';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
						}
					}
						
					if ($pjTemplate->delete ( $id )) {
						
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB03';
							$this->index ();
							
							return ;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB03" );
						}
					} else {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB04';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB04" );
						}
					}
					
				} elseif ( isset($menu) && $menu == 1 ) {

					$arr = $pjMenu->get ( $id );
					if (count ( $arr ) == 0) {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB08';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
						}
					}
					
					if ($pjMenu->delete ( $id )) {
					
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB03';
							$this->index ();
								
							return ;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB03" );
						}
					} else {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB04';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB04" );
						}
					}
						
				} elseif ( isset($tables_group) && $tables_group == 1 ) {

					$arr = $pjTablesGroup->get ( $id );
					if (count ( $arr ) == 0) {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB08';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
						}
					}
					
					if ($pjTablesGroup->delete ( $id )) {
					
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB03';
							$this->index ();
								
							return ;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB03" );
						}
					} else {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB04';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB04" );
						}
					}
						
				} else {
				
					$arr = $pjBookingModel->get ( $id );
					if (count ( $arr ) == 0) {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB08';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
						}
					}
					
					if ($pjBookingModel->delete ( $id )) {
						pjObject::import ( 'Model', 'pjBookingTable' );
						$pjBookingTableModel = new pjBookingTableModel ();
						$pjBookingTableModel->delete ( array (
								'booking_id' => $id 
						) );
						
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB03';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB03" );
						}
					} else {
						if ($this->isXHR ()) {
							$_GET ['err'] = 'AB04';
							$this->index ();
							return;
						} else {
							pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB04" );
						}
					}
				}
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function getAvailability() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			pjObject::import ( 'Model', array (
					'pjBooking',
					'pjBookingTable',
					'pjTable' 
			) );
			$pjBookingModel = new pjBookingModel ();
			$pjBookingTableModel = new pjBookingTableModel ();
			$pjTableModel = new pjTableModel ();
			
			$date = pjUtil::formatDate ( $_GET ['date'], $this->option_arr ['date_format'] );
			
			$this->tpl ['table_arr'] = $pjTableModel->getAll ( array (
					'col_name' => 't1.name',
					'direction' => 'asc' 
			) );
			$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjBookingModel->getTable (), 'TB', array (
					'TB.id' => 't1.booking_id',
					'DATE(dt)' => sprintf ( "'%s'", $date ),
					'TB.status' => "'confirmed'" 
			), array (
					'TB.id.bid' 
			), 'inner' );
			$this->tpl ['bt_arr'] = $pjBookingTableModel->getAllPair ( 'id', 'table_id' );
		}
	}
	function getPaper() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			$date = pjUtil::formatDate ( $_GET ['date'], $this->option_arr ['date_format'] );
			$this->tpl ['arr'] = $this->_getPaper ( $date );
			// $arr = $this->_getPaper($date);
			// $this->tpl = array_merge($this->tpl, $arr);
		}
	}
	function getSchedule() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			$date = pjUtil::formatDate ( $_GET ['date'], $this->option_arr ['date_format'] );
			$this->tpl ['wt_arr'] = pjAppController::getWorkingTime ( $date );
			$this->tpl ['arr'] = $this->_getSchedule ( $date, $this->tpl ['wt_arr'] );
			
			pjObject::import ( 'Model', 'pjDate' );
			$pjDateModel = new pjDateModel ();
			
			$this->tpl ['date_arr'] = $pjDateModel->getAll ( array (
					't1.date' => $date,
					'offset' => 0,
					'row_count' => 1 
			) );
		}
	}
	function getTables() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			pjObject::import ( 'Model', array (
					'pjTable',
					'pjBooking',
					'pjBookingTable',
					'pjTablesGroup' 
			) );
			$pjTableModel = new pjTableModel ();
			$pjBookingModel = new pjBookingModel ();
			$pjBookingTableModel = new pjBookingTableModel ();
			$pjTablesGroupModel = new pjTablesGroupModel();
			
			$dt_from = sprintf ( "%s %s:%s:00", pjUtil::formatDate ( $_POST ['date'], $this->option_arr ['date_format'] ), $_POST ['hour'], $_POST ['minute'] );
			$dt_to = sprintf ( "%s %s:%s:00", pjUtil::formatDate ( $_POST ['date_to'], $this->option_arr ['date_format'] ), $_POST ['hour_to'], $_POST ['minute_to'] );
			
			$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjBookingModel->getTable (), 'TB', array (
					'TB.id' => 't1.booking_id',
					'TB.status' => "'confirmed' AND `dt` <= '$dt_to' AND `dt_to` >= '$dt_from'" 
			), array (
					'TB.id.bid' 
			), 'inner' );
			$bt_arr = $pjBookingTableModel->getAllPair ( 'table_id', 'table_id' );
			
			$opts = array ();
			if (count ( $bt_arr ) > 0) {
				$opts ['t1.id'] = array (
						sprintf ( "('%s')", join ( "','", $bt_arr ) ),
						'NOT IN',
						'null' 
				);
			}
			$this->tpl ['table_arr'] = $pjTableModel->getAll ( array_merge ( $opts, array (
					'col_name' => 't1.name',
					'direction' => 'asc' 
			) ) );
			
			$this->tpl['tg_arr'] = $pjTablesGroupModel->getAll(array('col_name' => 't1.name', 'direction' => 'asc' ));
			
			$this->tpl['bt_not_arr'] = $bt_arr;
		}
	}
	function index() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjTable',
						'pjTemplate',
						'pjMenu',
						'pjTablesGroup',
				) );
				
				$pjBookingModel = new pjBookingModel ();
				$pjTableModel = new pjTableModel ();
				$pjTemplateModel = new pjTemplateModel();
				$pjMenuModel = new pjMenuModel();
				$pjTablesGroupModel = new pjTablesGroupModel();
				
				if (isset($_POST['template'])) {
						
					if ( isset($_POST['id'])) {
						$pjTemplateModel->update($_POST);
					} else
						$pjTemplateModel->save($_POST);
						
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
					
				}elseif (isset($_POST['menu'])) {
				
					if ( isset($_POST['id'])) {
						$pjMenuModel->update($_POST);
					} else
						$pjMenuModel->save($_POST);
				
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
					
				}elseif (isset($_POST['tables_group'])) {
				
					if (isset($_POST['table_id']) && is_array($_POST['table_id']) ) {
						$_POST['tables_id'] = join(', ', $_POST['table_id']);
						unset($_POST['table_id']);
					} else $_POST['tables_id'] = '';
					
					if ( isset($_POST['id'])) {
						$pjTablesGroupModel->update($_POST);
						
					} else {
						$pjTablesGroupModel->save($_POST);
					}
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
				}
				
				
				$page = isset($_GET['tpage']) && (int) $_GET['tpage'] > 0 ? intval($_GET['tpage']) : 1;
				$count = $pjTemplateModel->getCount();
				$row_count = 10;
				$pages = ceil($count / $row_count);
				$offset = ((int) $page - 1) * $row_count;
				
				$this->tpl['template_arr'] = $pjTemplateModel->getAll(array_merge(compact('offset', 'row_count'), array('col_name' => 't1.name', 'direction' => 'ASC')));
				$this->tpl['tpaginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
				
				$this->tpl['menu_arr'] = $pjMenuModel->getAll(array('col_name' => 't1.m_name', 'direction' => 'ASC'));
				
				$this->tpl['tg_arr'] = $pjTablesGroupModel->getAll(array('col_name' => 't1.name', 'direction' => 'ASC'));
				
				$opts = array ();
				if (isset ( $_GET ['table_id'] ) && ( int ) $_GET ['table_id'] > 0) {
					pjObject::import ( 'Model', 'pjBookingTable' );
					$pjBookingTableModel = new pjBookingTableModel ();
					$opts ['t1.id'] = array (
							sprintf ( "(SELECT `booking_id` FROM `%s` WHERE `table_id` = '%u')", $pjBookingTableModel->getTable (), $_GET ['table_id'] ),
							'IN',
							'null' 
					);
				}
				if (isset ( $_GET ['c_name'] ) && ! empty ( $_GET ['c_name'] )) {
					$q = pjObject::escapeString ( $_GET ['c_name'] );
					$opts ['t1.c_name'] = array (
							"'%$q%'",
							'LIKE',
							'null' 
					);
				}
				if (isset ( $_GET ['c_email'] ) && ! empty ( $_GET ['c_email'] )) {
					$q = pjObject::escapeString ( $_GET ['c_email'] );
					$opts ['t1.c_email'] = array (
							"'%$q%'",
							'LIKE',
							'null' 
					);
				}
				if (isset ( $_GET ['c_phone'] ) && ! empty ( $_GET ['c_phone'] )) {
					$q = pjObject::escapeString ( $_GET ['c_phone'] );
					$opts ['t1.c_phone'] = array (
							"'%$q%'",
							'LIKE',
							'null' 
					);
				}
				if (isset ( $_GET ['date_from'] ) && ! empty ( $_GET ['date_from'] ) && isset ( $_GET ['date_to'] ) && ! empty ( $_GET ['date_to'] )) {
					$df = pjUtil::formatDate ( $_GET ['date_from'], $this->option_arr ['date_format'] );
					$dt = pjUtil::formatDate ( $_GET ['date_to'], $this->option_arr ['date_format'] );
					$opts ['(DATE(t1.dt)'] = array (
							"'$df' AND '$dt')",
							'BETWEEN',
							'null' 
					);
				} else {
					if (isset ( $_GET ['date_from'] ) && ! empty ( $_GET ['date_from'] )) {
						$df = pjUtil::formatDate ( $_GET ['date_from'], $this->option_arr ['date_format'] );
						$opts ['DATE(t1.dt)'] = array (
								"'$df'",
								'>=',
								'null' 
						);
					} elseif (isset ( $_GET ['date_to'] ) && ! empty ( $_GET ['date_to'] )) {
						$dt = pjUtil::formatDate ( $_GET ['date_to'], $this->option_arr ['date_format'] );
						$opts ['DATE(t1.dt)'] = array (
								"'$dt'",
								'<=',
								'null' 
						);
					}
				}
				if (isset ( $_GET ['status'] ) && ! empty ( $_GET ['status'] )) {
					$opts ['t1.status'] = $_GET ['status'];
				}
				if (isset ( $_GET ['date'] ) && ! empty ( $_GET ['date'] )) {
					$date = pjUtil::formatDate ( $_GET ['date'], $this->option_arr ['date_format'] );
					$opts ['DATE(t1.dt)'] = array (
							sprintf ( "'%s'", $date ),
							'=',
							'null' 
					);
				}
				
				$page = isset ( $_GET ['page'] ) && ( int ) $_GET ['page'] > 0 ? intval ( $_GET ['page'] ) : 1;
				
				$count =  $pjBookingModel->getAll ( array_merge ( $opts, array (
						'col_name' => 't1.created',
						'direction' => 'desc', 
						't1.people' => array( $this->tpl['option_arr']['booking_group_booking'], '<', 'smallint')
				) ) );
				
				$row_count = 20;
				$pages = ceil ( count($count) / $row_count );
				$offset = (( int ) $page - 1) * $row_count;
				
				$this->tpl ['arr'] = $pjBookingModel->getAll ( array_merge ( $opts, compact ( 'offset', 'row_count' ), array (
						'col_name' => 't1.created',
						'direction' => 'desc', 
						't1.people' => array( $this->tpl['option_arr']['booking_group_booking'], '<', 'smallint')
				) ) );
				
				$this->tpl ['paginator'] = compact ( 'pages' );
				
				
				$page = isset ( $_GET ['pageg'] ) && ( int ) $_GET ['pageg'] > 0 ? intval ( $_GET ['pageg'] ) : 1;
				
				$count = $pjBookingModel->getAll ( array_merge ( $opts, array (
						'col_name' => 't1.created',
						'direction' => 'desc',
						't1.people' => array( $this->tpl['option_arr']['booking_group_booking'], '>=', 'smallint')
				) ) );
				
				$pages = ceil ( count($count) / $row_count );
				$offset = (( int ) $page - 1) * $row_count;
				
				$this->tpl ['arr_group'] = $pjBookingModel->getAll ( array_merge ( $opts, compact ( 'offset', 'row_count' ), array (
						'col_name' => 't1.created',
						'direction' => 'desc',
						't1.people' => array( $this->tpl['option_arr']['booking_group_booking'], '>=', 'smallint')
				) ) );
				
				$this->tpl ['paginator_group'] = compact ( 'pages' );
				
				
				$this->tpl ['table_arr'] = $pjTableModel->getAll ( array (
						'col_name' => 't1.name',
						'direction' => 'asc' 
				) );
				
				$this->js [] = array (
						'file' => 'jquery.ui.button.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->js [] = array (
						'file' => 'jquery.ui.position.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->js [] = array (
						'file' => 'jquery.ui.dialog.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->js [] = array (
						'file' => 'jabb-0.4.1.js',
						'path' => JS_PATH 
				);
				
				$this->css [] = array (
						'file' => 'jquery.ui.button.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.dialog.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.datepicker.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.datepicker.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
				);
				
				$this->js [] = array (
						'file' => 'jquery.metadata.js',
						'path' => LIBS_PATH . 'jquery/plugins/metadata/' 
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH 
				);
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function paper() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				$date = date ( "Y-m-d" );
				$this->tpl ['arr'] = $this->_getPaper ( $date );
				
				// $arr = $this->_getPaper($date);
				// $this->tpl = array_merge($this->tpl, $arr);
				
				$this->js [] = array (
						'file' => 'jquery.ui.datepicker.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->js [] = array (
						'file' => 'jabb-0.4.1.js',
						'path' => JS_PATH 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.datepicker.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH 
				);
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function printer() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				$this->layout = 'print';
				
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjCountry',
						'pjTable' 
				) );
				$pjBookingModel = new pjBookingModel ();
				$pjCountryModel = new pjCountryModel ();
				$pjTableModel = new pjTableModel ();
				
				// $pjBookingModel->addJoin($pjBookingModel->joins, $pjSpaceModel->getTable(), 'TS', array('TS.id' => 't1.space_id'), array('TS.name.space_name'));
				$pjBookingModel->addJoin ( $pjBookingModel->joins, $pjCountryModel->getTable (), 'TC', array (
						'TC.id' => 't1.c_country' 
				), array (
						'TC.country_title' 
				) );
				$arr = $pjBookingModel->get ( $_GET ['id'] );
				if (count ( $arr ) === 0) {
					pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
				}
				$this->tpl ['arr'] = $arr;
				
				pjObject::import ( 'Model', array (
						'pjBookingTable',
						'pjTable',
						'pjBookingMenu',
						'pjMenu'
				) );
				
				$pjBookingTableModel = new pjBookingTableModel ();
				$pjTableModel = new pjTableModel ();
				
				$pjBookingMenuModel =  new pjBookingMenuModel();
				$pjMenuModel = new pjMenuModel();
				
				$this->tpl ['table_arr'] = $pjTableModel->getAll ( array (
						'col_name' => 't1.name',
						'direction' => 'asc' 
				) );
				$this->tpl ['bt_arr'] = $pjBookingTableModel->getAllPair ( 'table_id', 'id', array (
						't1.booking_id' => $arr ['id'] 
				) );
				
				$this->tpl ['menu_arr'] = $pjMenuModel->getAll ( array (
						'col_name' => 't1.m_name',
						'direction' => 'asc'
					) );
				
				$this->tpl ['bm_arr'] = $pjBookingMenuModel->getAllPair ( 'id', 'menu_id', array (
						't1.booking_id' => $arr ['id']
					) );
				
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function printPaper() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				$this->layout = 'print';
				
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjBookingTable',
						'pjTable' 
				) );
				$pjBookingModel = new pjBookingModel ();
				
				$date = pjUtil::formatDate ( $_GET ['date'], $this->option_arr ['date_format'] );
				
				$this->tpl ['arr'] = $this->_getPaper ( $date );
				
				$opts ['t1.status'] = 'confirmed';
				$opts ['DATE(t1.dt)'] = array (
						sprintf ( "'%s'", $date ),
						'=',
						'null' 
				);
				$this->tpl ['arr_date'] = $pjBookingModel->getAll ( array_merge ( $opts, array (
						'group_by' => 't1.dt',
						'col_name' => 't1.dt',
						'direction' => 'ASC' 
				) ) );
				
				if (isset ( $this->tpl ['arr_date'] ) && is_array ( $this->tpl ['arr_date'] )) {
					
					foreach ( $this->tpl ['arr_date'] as $key => $booking_date ) {
						$people = 0;
						foreach ( $this->tpl ['arr'] as $booking ) {
							if ($booking_date ['dt'] == $booking ['dt']) {
								$people += ( int ) $booking ['people'];
							}
						}
						
						$this->tpl ['arr_date'] [$key] ['people'] = $people;
					}
				}
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function reminder() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				if (isset ( $_POST ['reminder'] )) {
					pjObject::import ( 'Component', 'pjEmail' );
					
					pjObject::import('Model', array('pjBooking'));
					
					$pjEmail = new pjEmail ();
					$pjBookingModel = new pjBookingModel();
					
					if ($pjEmail->send ( $_POST ['to'], $_POST ['subject'], $_POST ['message'], $this->option_arr ['email_address'] )) {
						$err = 'AB09';
						$pjBookingModel->update(array('reminder_email' => 1, 'id' => $_POST['id']));
					} else {
						$err = 'AB10';
					}
					pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=$err" );
				} else {
					pjObject::import ( 'Model', array (
							'pjBooking',
					) );
					
					$pjBookingModel = new pjBookingModel ();
					
					$arr = $pjBookingModel->get ( $_GET ['id'] );
					if (count ( $arr ) === 0) {
						pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
					}
					
					pjObject::import ( 'Model', array (
							'pjBookingTable',
							'pjTable',
							'pjTemplate'
					) );
					
					$pjBookingTableModel = new pjBookingTableModel ();
					$pjTableModel = new pjTableModel ();
					$pjTemplateModel = new pjTemplateModel();
					
					$arr['template_arr'] = $pjTemplateModel->getAll(array('col_name' => 't1.name', 'direction' => 'ASC'));
					
					$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjTableModel->getTable (), 'TE', array (
							'TE.id' => 't1.table_id' 
					), array (
							'TE.name' 
					) );
					$arr ['table_arr'] = $pjBookingTableModel->getAll ( array (
							't1.booking_id' => $arr ['id'] 
					) );
					$arr ['data'] = pjAppController::getData ( $this->option_arr, $arr, $this->salt );
					$this->tpl ['arr'] = $arr;
					
					$this->js [] = array (
							'file' => 'jquery.validate.min.js',
							'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
					);
					$this->js [] = array (
							'file' => 'pjAdminBookings.js',
							'path' => JS_PATH 
					);
				}
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function customer() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjTable' 
				) );
				$pjBookingModel = new pjBookingModel ();
				
				$page = isset ( $_GET ['page'] ) && ( int ) $_GET ['page'] > 0 ? intval ( $_GET ['page'] ) : 1;
				$count = $pjBookingModel->getAll ( array (
						'group_by' => 't1.c_email' 
				) );
				
				$row_count = 20;
				$pages = ceil ( count ( $count ) / $row_count );
				$offset = (( int ) $page - 1) * $row_count;
				
				$this->tpl ['arr'] = $pjBookingModel->getcustomer ( array_merge ( compact ( 'offset', 'row_count' ), array (
						'col_name' => 't1.c_fname',
						'direction' => 'ASC',
						'group_by' => 't1.c_email' 
				) ) );
				$this->tpl ['paginator'] = compact ( 'pages' );
				
				$this->js [] = array (
						'file' => 'jquery.ui.datepicker.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.datepicker.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.dialog.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.dialog.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jabb-0.4.1.js',
						'path' => JS_PATH 
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.button.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.button.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'bootstrap-modal.js',
						'path' => JS_PATH 
				);
				$this->css [] = array (
						'file' => 'bootstrap-modal.css',
						'path' => CSS_PATH 
				);
				
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH 
				);
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	function download_csv() {
		$this->layout = 'download_csv';
		
		pjObject::import ( 'Model', array (
				'pjBooking',
				'pjTable' 
		) );
		$pjBookingModel = new pjBookingModel ();
		
		$this->tpl ['arr'] = $pjBookingModel->getcustomer ( array (
				'col_name' => 't1.c_fname',
				'direction' => 'ASC',
				'group_by' => 't1.c_email' 
		) );
		
		//$this->download_send_headers("customer_" . date("Y-m-d") . ".csv");
		//echo $this->array_csv($this->tpl);
		//exit();
	}
	
	function array_csv($tpl) {
		if (count ( $tpl ['arr'] ) == 0) {
			return null;
		}
		ob_start ();
		$df = fopen ( "php://output", 'w' );
		
		// Download the file
		if (count ( $tpl ['arr'] ) > 0) {
			
			// Language
			require ROOT_PATH . 'app/locale/en.php';
			
			$title = array ();
			
			if ((isset ( $tpl ['option_arr'] ['cm_include_fname'] ) && $tpl ['option_arr'] ['cm_include_fname'] == 2) || (isset ( $tpl ['option_arr'] ['cm_include_lname'] ) && $tpl ['option_arr'] ['cm_include_lname'] == 2)) {
				$title [] = $RB_LANG ['booking_name'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_phone'] ) && $tpl ['option_arr'] ['cm_include_phone'] == 2) {
				$title [] = $RB_LANG ['booking_phone'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_email'] ) && $tpl ['option_arr'] ['cm_include_email'] == 2) {
				$title [] = $RB_LANG ['booking_email'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_company'] ) && $tpl ['option_arr'] ['cm_include_company'] == 2) {
				$title [] = $RB_LANG ['booking_company'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_address'] ) && $tpl ['option_arr'] ['cm_include_address'] == 2) {
				$title [] = $RB_LANG ['booking_address'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_city'] ) && $tpl ['option_arr'] ['cm_include_city'] == 2) {
				$title [] = $RB_LANG ['booking_city'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_state'] ) && $tpl ['option_arr'] ['cm_include_state'] == 2) {
				$title [] = $RB_LANG ['booking_state'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_zip'] ) && $tpl ['option_arr'] ['cm_include_zip'] == 2) {
				$title [] = $RB_LANG ['booking_zip'];
			}
			
			if (isset ( $tpl ['option_arr'] ['cm_include_count'] ) && $tpl ['option_arr'] ['cm_include_count'] == 2) {
				$title [] = $RB_LANG ['booking_count'];
			}
			
			fputcsv ( $df, $title );
			
			// foreach ($this->tpl['arr'] as $a) {
			for($i = 0; $i < count ( $tpl ['arr'] ); $i ++) {
				$value = array ();
				
				if ((isset ( $tpl ['option_arr'] ['cm_include_fname'] ) && $tpl ['option_arr'] ['cm_include_fname'] == 2) || (isset ( $tpl ['option_arr'] ['cm_include_lname'] ) && $tpl ['option_arr'] ['cm_include_lname'] == 2)) {
					$c_title = isset ( $tpl ['arr'] [$i] ['c_title'] ) ? $RB_LANG ['_titles'] [$tpl ['arr'] [$i] ['c_title']] . ' ' : '';
					$value [] = $c_title . stripslashes ( $tpl ['arr'] [$i] ['c_fname'] . " " . $tpl ['arr'] [$i] ['c_lname'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_phone'] ) && $tpl ['option_arr'] ['cm_include_phone'] == 2) {
					$value [] = $tpl ['arr'] [$i] ['c_phone'];
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_email'] ) && $tpl ['option_arr'] ['cm_include_email'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_email'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_company'] ) && $tpl ['option_arr'] ['cm_include_company'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_company'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_address'] ) && $tpl ['option_arr'] ['cm_include_address'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_address'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_city'] ) && $tpl ['option_arr'] ['cm_include_city'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_city'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_state'] ) && $tpl ['option_arr'] ['cm_include_state'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_state'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_zip'] ) && $tpl ['option_arr'] ['cm_include_zip'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['c_zip'] );
				}
				
				if (isset ( $tpl ['option_arr'] ['cm_include_count'] ) && $tpl ['option_arr'] ['cm_include_count'] == 2) {
					$value [] = stripslashes ( $tpl ['arr'] [$i] ['count'] );
				}
				
				fputcsv ( $df, $value, ',', '"' );
			}
			
		}
		
		fclose ( $df );
		return ob_get_clean ();
	}
	function download_send_headers($filename) {
		// disable caching
		$now = gmdate ( "D, d M Y H:i:s" );
		header ( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
		header ( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
		header ( "Last-Modified: {$now} GMT" );
		
		// force download
		header ( "Content-Type: application/force-download" );
		header ( "Content-Type: application/octet-stream" );
		header ( "Content-Type: application/download" );
		
		// disposition / encoding on response body
		header ( "Content-Disposition: attachment;filename={$filename}" );
		header ( "Content-Transfer-Encoding: binary" );
	}
	
	function schedule() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjBookingTable',
						'pjTable',
						'pjService'
					) );
				
				$pjBookingModel = new pjBookingModel ();
				$pjBookingTableModel = new pjBookingTableModel ();
				$pjTableModel = new pjTableModel ();
				$pjServiceModel = new pjServiceModel();
						
				$services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
				
				if (isset ( $_POST ['rbBookingForm'] ) && isset ( $_POST ['rbBooking_date'] ) && isset ( $_POST ['rbBooking_hour'] ) && isset ( $_POST ['rbBooking_tableid'] )) {
					
					$data = array ();
					
					$date = pjUtil::formatDate ( $_POST ['rbBooking_date'], $this->option_arr ['date_format'] );
					$time = $_POST ['rbBooking_hour'] . ":00:00";
					unset ( $_POST ['rbBooking_date'] );
					unset ( $_POST ['rbBooking_hour'] );
					
					$data ['dt'] = $date . " " . $time;
					
					$booking_length = $this->option_arr['booking_length'] * 60;
					$start_hour = strtotime($time);
						
					foreach ($services as $service) {
						if ( strtotime($service['start_time']) <= $start_hour && strtotime($service['end_time']) >= $start_hour) {
							$booking_length = $service['s_length'] * 3600;
							break;
						}
					}
					
					$data ['dt_to'] = date ( "Y-m-d H:i:s", strtotime ( $data ['dt'] ) + $booking_length );
					
					$data ['uuid'] = time ();
					

					$data ['status'] = ((int) $_POST['people'] > (int) $this->option_arr['booking_group_booking'])
                        ? 'enquiry'
                        : 'confirmed';
					
					$table_id = $_POST ['rbBooking_tableid'];
					unset ( $_POST ['rbBooking_tableid'] );
					
					$data = array_merge ( $_POST, $data );
					
					$booking_id = $pjBookingModel->save ( $data );
					
					if ($booking_id !== false && ( int ) $booking_id > 0) {
						$booking_arr = $pjBookingModel->get ( $booking_id );
						if ($table_id !== false && ( int ) $table_id > 0) {
							
							$pjBookingTableModel->save ( array (
									'booking_id' => $booking_id,
									'table_id' => $table_id 
							) );
							if (count ( $booking_arr ) > 0) {
								$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjTableModel->getTable (), 'TT', array (
										'TT.id' => 't1.table_id' 
								), array (
										'TT.name' 
								) );
								$booking_arr ['table_arr'] = $pjBookingTableModel->getAll ( array (
										't1.booking_id' => $booking_arr ['id'] 
								) );
							}
							$op = 2;
							$json = array (
									'code' => 200,
									'text' => '',
									'booking_id' => $booking_id 
							);
						} else {
							$booking_arr ['table_arr'] = array ();
							$op = 4;
							$json = array (
									'code' => 201,
									'text' => '' 
							);
						}
						
						pjObject::import ( 'Model', 'pjOption' );
						$pjOptionModel = new pjOptionModel ();
						$option_arr = $pjOptionModel->getPairs ();
						$this->confirmSend ( $option_arr, $booking_arr, $this->salt, $op );
					}
					
				} elseif ( isset ( $_POST ['rbBookingForm'] ) && isset ( $_POST ['frmAddGroup'] ) ) {
					
					$data = array ();
						
					$data ['dt'] = pjUtil::formatDate ( $_POST ['date'], $this->option_arr ['date_format'] ) . " " . $_POST ['hour'] . ":" . $_POST ['minute'] . ":00";
					$time = $_POST ['hour'] . ":" . $_POST ['minute'] . ":00";
					unset ( $_POST ['rbBooking_date'] );
					unset ( $_POST ['rbBooking_hour'] );
						
					$booking_length = $this->option_arr['booking_length'] * 60;
					$start_hour = strtotime($time);
					
					foreach ($services as $service) {
						if ( strtotime($service['start_time']) <= $start_hour && strtotime($service['end_time']) >= $start_hour) {
							$booking_length = $service['s_length'] * 3600;
							break;
						}
					}
						
					$data ['dt_to'] = date ( "Y-m-d H:i:s", strtotime ( $data ['dt'] ) + $booking_length );
						
					$data ['uuid'] = time ();
						
					$data ['status'] = 'enquiry';
						
					$data = array_merge ( $_POST, $data );
						
					$booking_id = $pjBookingModel->save ( $data );
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
				}
				
				$date = date ( "Y-m-d" );
				$this->tpl ['wt_arr'] = pjAppController::getWorkingTime ( $date );
				
				pjObject::import ( 'Model', array('pjDate') );
				$pjDateModel = new pjDateModel ();
				
				$this->tpl ['date_arr'] = $pjDateModel->getAll ( array (
						't1.date' => $date,
						'offset' => 0,
						'row_count' => 1 
				) );
				
				
				
				$this->tpl ['arr'] = $this->_getSchedule ( $date, $this->tpl ['wt_arr'] );
				
				$this->js [] = array (
						'file' => 'jquery.ui.datepicker.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.datepicker.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.dialog.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.dialog.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'jabb-0.4.1.js',
						'path' => JS_PATH 
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.button.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->css [] = array (
						'file' => 'jquery.ui.button.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
				
				$this->js [] = array (
						'file' => 'bootstrap-modal.js',
						'path' => JS_PATH 
				);
				$this->css [] = array (
						'file' => 'bootstrap-modal.css',
						'path' => CSS_PATH 
				);
				
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
				);
				
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH
				);
				
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	
	function statistics() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				
				pjObject::import ( 'Model', array (
				'pjBooking',
				'pjService'
						) );
				
				$pjBookingModel = new pjBookingModel ();
				$pjServiceModel = new pjServiceModel();
				
				$services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
				
				
				$opts ['t1.status'] = array (
						sprintf ( "('%s')", join ( "','", array('complete', 'confirmed') ) ),
						'IN',
						'null'
				);
				
				$today 		= date('Y-m-d');
				$yesterday 	= date('Y-m-d', time() - 24*3600 );
				$week 		= date('Y-m-d', time() + 8*24*3600 );
				
				$this->tpl ['booking_arr'] = $pjBookingModel->getAll ( array_merge($opts, array (
						'UNIX_TIMESTAMP(`t1`.`dt_to`)' => array( strtotime($yesterday . ' 00:00:00'), '>=', 'datetime'),
						'UNIX_TIMESTAMP(`t1`.`dt`)' => array( strtotime($week . ' 00:00:00'), '<', 'datetime'),
				)) );
				
				$this->tpl ['booking_old_arr'] = $pjBookingModel->getAll ( array_merge($opts, array (
						'col_name' => 't1.dt',
						'direction' => 'ASC',
						'UNIX_TIMESTAMP(`t1`.`dt_to`)' => array( strtotime(date('Y-m-d', time() - 6*24*3600 ) . ' 00:00:00'), '>=', 'datetime'),
						'UNIX_TIMESTAMP(`t1`.`dt`)' => array( strtotime(date('Y-m-d', time() + 24*3600 ) . ' 00:00:00'), '<', 'datetime'),
				) ));
				
				$this->tpl ['services_arr'] = $services;
				
				$this->js [] = array (
						'file' => 'raphael-min.js',
						'path' => JS_PATH
				);
				
				$this->js [] = array (
						'file' => 'morris.js',
						'path' => JS_PATH
				);
				$this->css [] = array (
						'file' => 'morris.css',
						'path' => CSS_PATH
				);
				
				$this->js [] = array (
						'file' => 'prettify.js',
						'path' => JS_PATH
				);
				$this->css [] = array (
						'file' => 'prettify.css',
						'path' => CSS_PATH
				);
				
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH
				);
				
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	
	public function monthly() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			
			pjObject::import ( 'Model', array (
			'pjBooking',
			'pjService'
					) );
			
			$pjBookingModel = new pjBookingModel ();
			$pjServiceModel = new pjServiceModel();
			
			$services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
			
			$this->tpl ['services_arr'] = $services;
			
			if ( isset($_GET['m']) && !empty($_GET['m']) ){
					$monthly = $_GET['m'];
				
				} else $monthly = date('m');
			
			$monthly_f = $monthly - 3;
			$monthly_t = $monthly + 3;
			
			if ($monthly_f < 0) {
				$monthly_f = 12 + $monthly_f;
				$monthly_from = (date('Y') -1) . '-' . $monthly_f . '-1 00:00:00' ;
				
			} else $monthly_from = date('Y') . '-' . $monthly_f . '-1 00:00:00' ;
			
			if ( $monthly_t > 12 ) {
				$monthly_t = $monthly_t - 12;
				$monthly_to = (date('Y') + 1) . '-' . $monthly_t . '-1 00:00:00' ;
				
			} else $monthly_to = date('Y') . '-' . $monthly_t . '-1 00:00:00' ;
			
			$opts ['t1.status'] = array (
					sprintf ( "('%s')", join ( "','", array('complete', 'confirmed') ) ),
					'IN',
					'null'
			);
			
			$this->tpl ['monthly_arr'] = $pjBookingModel->getAll ( array_merge($opts, array (
					'UNIX_TIMESTAMP(`t1`.`dt_to`)' => array( strtotime($monthly_from), '>=', 'datetime'),
					'UNIX_TIMESTAMP(`t1`.`dt`)' => array( strtotime($monthly_to), '<', 'datetime'),
			)) );
		}
	}
	
	function calendar() {
		
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
		
			if( isset($_GET['year']) ){
				$year = $_GET['year'];
					
			} else {
				$year = date("Y",time());
					
			}
			
			if( isset($_GET['month']) ){
				$month = $_GET['month'];
					
			} else {
				$month = date("m",time());
					
			}
			
			pjObject::import ( 'Model', array (
				'pjBooking',
				'pjService',
				'pjCalendar',
				'pjTable'
			) );
			
			$pjBookingModel = new pjBookingModel ();
			$pjServiceModel = new pjServiceModel();
			$pjCalendar = new pjCalendarModel();
			$pjTable = new pjTableModel();
			
			$pjCalendar->currency = $this->tpl['option_arr']['currency'];
			
			$pjCalendar->services = $pjServiceModel->getAll(array('col_name' => 't1.start_time', 'direction' => 'ASC'));
			
			$table_arr = $pjTable->getAll();
			
			if (isset ($table_arr) && count($table_arr) > 0) {
				$pjCalendar->s_seats = 0;
				
				foreach ($table_arr as $table) {
					$pjCalendar->s_seats += $table['seats'];
				}
			}
			
			$opts ['t1.status'] = array (
					sprintf ( "('%s')", join ( "','", array('complete', 'confirmed') ) ),
					'IN',
					'null'
			);
			
			$strtotime_fm = strtotime( $year . '-' . $month .'-1 00:00:00');
			$strtotime_lm = strtotime( $year . '-' . $month .'-31 23:59:59');
			
			$pjCalendar->booking_arr = $pjBookingModel->getAll ( array_merge($opts, array (
					'UNIX_TIMESTAMP(`t1`.`dt_to`)' => array( $strtotime_fm, '>=', 'datetime'),
					'UNIX_TIMESTAMP(`t1`.`dt`)' => array( $strtotime_lm, '<', 'datetime'),
			)) );
			
			$this->tpl['calendar'] = $pjCalendar->show($year, $month);
			
		} else {
			
			$this->tpl['status'] = 2;
		}
	}
	
	function confirmSend($option_arr, $booking_arr, $salt, $opt) {
		if (! in_array ( ( int ) $opt, array (
				2,
				3,
				4 
		) )) {
			return false;
		}
		pjObject::import ( 'Component', 'pjEmail' );
		$pjEmail = new pjEmail ();
		
		$data = pjAppController::getData ( $option_arr, $booking_arr, $salt );
		
		// Payment email
		if ($option_arr ['email_payment'] == $opt) {
			$message = str_replace ( $data ['search'], $data ['replace'], $option_arr ['email_payment_message'] );
			// Send to ADMIN
			$pjEmail->send ( $option_arr ['email_address'], $option_arr ['email_payment_subject'], $message, $option_arr ['email_address'] );
			// Send to CLIENT
			$pjEmail->send ( $booking_arr ['c_email'], $option_arr ['email_payment_subject'], $message, $option_arr ['email_address'] );
		}
		
		// Confirmation email
		if ($option_arr ['email_confirmation'] == $opt) {
			$message = str_replace ( $data ['search'], $data ['replace'], $option_arr ['email_confirmation_message'] );
			// Send to ADMIN
			$pjEmail->send ( $option_arr ['email_address'], $option_arr ['email_confirmation_subject'], $message, $option_arr ['email_address'] );
			// Send to CLIENT
			$pjEmail->send ( $booking_arr ['c_email'], $option_arr ['email_confirmation_subject'], $message, $option_arr ['email_address'] );
		}
		
		// Enquiry email
		if ($option_arr ['email_enquiry'] == $opt) {
			$message = str_replace ( $data ['search'], $data ['replace'], $option_arr ['email_enquiry_message'] );
			// Send to ADMIN
			$pjEmail->send ( $option_arr ['email_address'], $option_arr ['email_enquiry_subject'], $message, $option_arr ['email_address'] );
			// Send to CLIENT
			$pjEmail->send ( $booking_arr ['c_email'], $option_arr ['email_enquiry_subject'], $message, $option_arr ['email_address'] );
		}
		
		if (isset ( $_SERVER ['SERVER_ADDR'] ) && $_SERVER ['SERVER_ADDR'] == '127.0.0.1') {
		} else {
			// SMS
			$message = str_replace ( $data ['search'], $data ['replace'], $option_arr ['reminder_sms_message'] );
			
			$phone = $booking_arr ['c_phone'];
			if (strpos ( $phone, '0' ) == 0) {
				$phone = ltrim ( $phone, '0' );
			}
			
			$phone = isset ( $option_arr ['reminder_sms_country_code'] ) ? $option_arr ['reminder_sms_country_code'] . $phone : $phone;
			
			$send_address = isset($option_arr['reminder_sms_send_address']) ? $option_arr['reminder_sms_send_address'] : $phone;
			
			$sendsms = new pjSMS ();
			// Send to CLIENT
			$sendsms->sendSMS ( $send_address, $phone, $message );
		}
	}
	function update() {
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				pjObject::import ( 'Model', array (
						'pjBooking',
						'pjBookingTable',
						'pjTable',
						'pjMenu',
						'pjBookingMenu',
						'pjTablesGroup',
						'pjBookingTableGroup'
				) );
				$pjBookingModel = new pjBookingModel ();
				$pjBookingTableModel = new pjBookingTableModel ();
				$pjTableModel = new pjTableModel ();
				$pjMenuModel = new pjMenuModel();
				$pjBookingMenuModel = new pjBookingMenuModel();
				$pjTablesGroupModel = new pjTablesGroupModel();
				$pjBookingTableGroupModel = new pjBookingTableGroupModel();
				
				if (isset ( $_POST ['booking_update'] )) {
					if ($this->isDemo ()) {
						pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule" );
					}
					$data = array ();
					$data ['dt'] = pjUtil::formatDate ( $_POST ['date'], $this->option_arr ['date_format'] ) . " " . $_POST ['hour'] . ":" . $_POST ['minute'] . ":00";
					$data ['dt_to'] = pjUtil::formatDate ( $_POST ['date_to'], $this->option_arr ['date_format'] ) . " " . $_POST ['hour_to'] . ":" . $_POST ['minute_to'] . ":00";
					// $data['dt_to'] = date("Y-m-d H:i:s", strtotime($data['dt']) + $this->option_arr['booking_length'] * 60);
					if (isset ( $_POST ['payment_method'] ) && $_POST ['payment_method'] == 'creditcard') {
						$data ['cc_exp'] = $_POST ['cc_exp_year'] . '-' . $_POST ['cc_exp_month'];
					}
					
					$stop = false;
					if (! $stop) {
						if (isset ( $_POST ['c_notes'] )) {
							$_POST ['c_notes'] = strip_tags ( $_POST ['c_notes'] );
						}
						
						$pjBookingModel->update ( array_merge ( $_POST, $data ) );
						
						$pjBookingTableModel->delete ( array (
								'booking_id' => $_POST ['id'] 
						) );
						
						$pjBookingTableGroupModel->delete ( array (
								'booking_id' => $_POST ['id']
						) );
						
						$pjBookingMenuModel->delete ( array (
								'booking_id' => $_POST ['id']
						) );
						
						if ( (isset ( $_POST ['table_id'] ) && count ( $_POST ['table_id'] ) > 0) || 
							(isset ( $_POST ['tables_group_id'] ) && count ( $_POST ['tables_group_id'] ) > 0))  {
							
							if (isset ( $_POST ['table_id'] ) && count ( $_POST ['table_id'] ) > 0) {
								$data = array ();
								$data ['booking_id'] = $_POST ['id'];
								foreach ( $_POST ['table_id'] as $value ) {
									list ( $table_id, ) = explode ( "|", $value );
									$data ['table_id'] = $table_id;
									$pjBookingTableModel->save ( $data );
								}
							}
							
							if (isset ( $_POST ['tables_group_id'] ) && count ( $_POST ['tables_group_id'] ) > 0) {
								
								$data_group = array ();
								$data_group['booking_id'] = $_POST ['id'];
								$data = array ();
								$data ['booking_id'] = $_POST ['id'];
								
								foreach ( $_POST ['tables_group_id'] as $value ) {
									
									list ( $tables_group_id, $tables_id ) = explode ( "|", $value );
									$data_group ['tables_group_id'] = $tables_group_id;
									$pjBookingTableGroupModel->save($data_group);
									
									foreach ( explode(',',$tables_id) as $table_id) {
										$data ['table_id'] = $table_id;
										$pjBookingTableModel->save ( $data );
									}
								}
							}
						}
						
						if (isset ( $_POST ['menu_id'] ) && count ( $_POST ['menu_id'] ) > 0) {
							$data = array ();
							$data ['booking_id'] = $_POST ['id'];
							foreach ( $_POST ['menu_id'] as $value ) {
								list ( $menu_id, ) = explode ( "|", $value );
								$data ['menu_id'] = $menu_id;
								$pjBookingMenuModel->save ( $data );
							}
						}
						
						$err = 'AB05';
					}
					if (isset ( $_POST ['customer'] ) && $_POST ['customer'] == 1) {
						pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=customer" );
					} else
						pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule" );
				} else {
					$arr = $pjBookingModel->get ( $_GET ['id'] );
					$customer = isset ( $_GET ['customer'] ) ? $_GET ['customer'] : null;
					
					if (count ( $arr ) === 0) {
						pjUtil::redirect ( $_SERVER ['PHP_SELF'] . "?controller=pjAdminBookings&action=index&err=AB08" );
					}
					
					$this->tpl ['arr'] = $arr;
					
					$this->tpl['menu_arr'] = $pjMenuModel->getAll(array('col_name' => 't1.m_name', 'direction' => 'ASC'));
					
					
					$pjBookingMenuModel->joins = array ();
					$this->tpl ['bm_arr'] = $pjBookingMenuModel->getAllPair ( 'id', 'menu_id', array (
							't1.booking_id' => $arr ['id']
					) );
					
					if (isset ( $customer ) && $customer == 1) {
						$arr_customer = $pjBookingModel->getAll ( array (
								'col_name' => 't1.id',
								'direction' => 'DESC',
								't1.c_email' => $arr ['c_email'] 
						) );
						
						if (count ( $arr_customer ) > 0) {
							list ( $last_booking, $time ) = explode ( " ", $arr_customer [0] ['created'] );
							$total = 0;
							$rate = 0;
							
							foreach ( $arr_customer as $arr_c ) {
								
								if (strtotime ( $arr_c ['dt'] ) < strtotime ( 'now' )) {
									$total += 1;
									
									if ($arr_c ['status'] = 'complete') {
										$rate += 1;
									}
								}
							}
							
							if ($total == 0) {
								$rate = 100;
							} else {
								$rate = $rate * 100 / $total;
							}
							
							$arr_customer = array ();
							$arr_customer ['last_booking'] = $last_booking;
							$arr_customer ['rate'] = $rate;
							
							$this->tpl ['arr_customer'] = $arr_customer;
						}
					}
					
					pjObject::import ( 'Model', 'pjCountry' );
					$pjCountryModel = new pjCountryModel ();
					$this->tpl ['country_arr'] = $pjCountryModel->getAll ( array (
							'col_name' => 't1.country_title',
							'direction' => 'asc' 
					) );
					
					$pjBookingTableModel->addJoin ( $pjBookingTableModel->joins, $pjBookingModel->getTable (), 'TB', array (
							'TB.id' => 't1.booking_id',
							'TB.status' => "'confirmed' AND `dt` < '" . $arr ['dt_to'] . "' AND `dt_to` > '" . $arr ['dt'] . "'" 
					), array (
							'TB.id.bid' 
					), 'inner' );
					
					$bt_arr = $pjBookingTableModel->getAllPair ( 'table_id', 'table_id', array (
							't1.booking_id' => array (
									$arr ['id'],
									'!=',
									'int' 
							) 
					) );
					
					$opts = array ();
					if (count ( $bt_arr ) > 0) {
						$opts ['t1.id'] = array (
								sprintf ( "('%s')", join ( "','", $bt_arr ) ),
								'NOT IN',
								'null' 
						);
					}
					
					$this->tpl ['table_arr'] = $pjTableModel->getAll ( array_merge ( $opts, array (
							'col_name' => 't1.name',
							'direction' => 'asc' 
					) ) );
					
					$pjBookingTableModel->joins = array ();
					$this->tpl ['bt_arr'] = $pjBookingTableModel->getAllPair ( 'id', 'table_id', array (
							't1.booking_id' => $arr ['id'] 
					) );
					
					$this->tpl['tg_arr'] = $pjTablesGroupModel->getAll(array('col_name' => 't1.name', 'direction' => 'asc' ));
					
					$this->tpl['btg_arr'] = $pjBookingTableGroupModel->getAll(array ('t1.booking_id' => $arr ['id']));
					
					$this->tpl['bt_not_arr'] = $bt_arr;
					
					$this->js [] = array (
							'file' => 'jquery.ui.position.min.js',
							'path' => LIBS_PATH . 'jquery/ui/js/' 
					);
					$this->js [] = array (
							'file' => 'jquery.ui.dialog.min.js',
							'path' => LIBS_PATH . 'jquery/ui/js/' 
					);
					$this->js [] = array (
							'file' => 'jabb-0.4.1.js',
							'path' => JS_PATH 
					);
					
					$this->css [] = array (
							'file' => 'jquery.ui.dialog.css',
							'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
					);
					
					$this->js [] = array (
							'file' => 'jquery.ui.datepicker.min.js',
							'path' => LIBS_PATH . 'jquery/ui/js/' 
					);
					$this->css [] = array (
							'file' => 'jquery.ui.datepicker.css',
							'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
					);
					
					$this->js [] = array (
							'file' => 'jquery.ui.button.min.js',
							'path' => LIBS_PATH . 'jquery/ui/js/' 
					);
					$this->css [] = array (
							'file' => 'jquery.ui.button.css',
							'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
					);
					
					$this->js [] = array (
							'file' => 'jquery.validate.min.js',
							'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
					);
					$this->js [] = array (
							'file' => 'pjAdminBookings.js',
							'path' => JS_PATH 
					);
				}
			} else {
				$this->tpl ['status'] = 2;
			}
		} else {
			$this->tpl ['status'] = 1;
		}
	}
	
	public function tupdate() {
		
		if ($this->isLoged())
		{
			if ($this->isAdmin() && isset($_GET['id']) )
			{
				pjObject::import('Model', array('pjTemplate'));
				$pjTemplateModel = new pjTemplateModel();
		
				$arr = $pjTemplateModel->get($_GET['id']);
		
				if (count($arr) == 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
				}
				$this->tpl['arr'] = $arr;
		
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/' 
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH 
				);
				
			} else {
				$this->tpl['status'] = 2;
			}
				} else {
				$this->tpl['status'] = 1;
		}
	}
	
	public function mupdate() {
	
		if ($this->isLoged())
		{
			if ($this->isAdmin() && isset($_GET['id']) )
			{
				pjObject::import('Model', array('pjMenu'));
				$pjMenuModel = new pjMenuModel();
	
				$arr = $pjMenuModel->get($_GET['id']);
	
				if (count($arr) == 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
				}
				$this->tpl['arr'] = $arr;
	
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/'
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH
				);
	
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	public function tgupdate() {
	
		if ($this->isLoged())
		{
			if ($this->isAdmin() && isset($_GET['id']) )
			{
				pjObject::import('Model', array('pjTablesGroup', 'pjTable'));
				
				$pjTablesGroupModel = new pjTablesGroupModel();
				$pjTableModel = new pjTableModel();
	
				$arr = $pjTablesGroupModel->get($_GET['id']);
	
				if (count($arr) == 0)
				{
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&tab=6");
				}
				
				$this->tpl['arr'] = $arr;
	
				$this->tpl ['table_arr'] = $pjTableModel->getAll ( array (
						'col_name' => 't1.name',
						'direction' => 'asc'
				) );
				
				$this->tpl['tg_arr'] = $pjTablesGroupModel->getAll(array('col_name' => 't1.name', 'direction' => 'ASC'));
				
				$this->js [] = array (
						'file' => 'jquery.validate.min.js',
						'path' => LIBS_PATH . 'jquery/plugins/validate/js/'
				);
				$this->js [] = array (
						'file' => 'pjAdminBookings.js',
						'path' => JS_PATH
				);
	
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	public function formstyle() {
		
		if ($this->isLoged ()) {
			if ($this->isAdmin ()) {
				pjObject::import ( 'Model', array (
						'pjFormStyle',
					) );
				
				$pjFormStyleModel = new pjFormStyleModel ();
		
				if (isset($_POST['form_style'])) {
					
					if ( isset($_POST['id']) ){
						$pjFormStyleModel->update($_POST);
						
					} else {
						$pjFormStyleModel->save($_POST);
					}
					
				}
				
				$this->tpl ['arr'] = $pjFormStyleModel->getAll();
			}
		}
	}
}
?>
