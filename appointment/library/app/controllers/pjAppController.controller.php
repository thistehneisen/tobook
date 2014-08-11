<?php
if (!defined("ROOT_PATH"))
{
    header("HTTP/1.1 403 Forbidden");
    exit;
}
if (is_file(PJ_FRAMEWORK_PATH . 'pjController.class.php'))
{
    require_once PJ_FRAMEWORK_PATH . 'pjController.class.php';
}

class pjAppController extends pjController {
    
    public $models = array();

    public $defaultLocale = 'admin_locale_id';
    
    private $layoutRange = array(1, 2, 3);
    

    public function getOwnerId(){
        $owner_id = 0;
        $use_front_owner_id = (bool)$_SESSION['use_front_owner_id'];
        if(isset($_SESSION['owner_id'])){
            $owner_id = intval($_SESSION['owner_id']);
        }
        if(isset($_SESSION['front_owner_id']) && $use_front_owner_id){
            $owner_id = intval($_SESSION['front_owner_id']);
        } 
        return $owner_id;
    }

    public function getLayoutRange()
    {
        return $this->layoutRange;
    }
    
    public static function setTimezone($timezone="UTC")
    {
        if (in_array(version_compare(phpversion(), '5.1.0'), array(0,1)))
        {
            date_default_timezone_set($timezone);
        } else {
            $safe_mode = ini_get('safe_mode');
            if ($safe_mode)
            {
                putenv("TZ=".$timezone);
            }
        }
    }

    public static function setMySQLServerTime($offset="-0:00")
    {
        pjAppModel::factory()->prepare("SET SESSION time_zone = :offset;")->exec(array('offset' => $offset));
    }
    
    public function setTime()
    {
        if (isset($this->option_arr['o_timezone']))
        {
            $offset = $this->option_arr['o_timezone'] / 3600;
            if ($offset > 0)
            {
                $offset = "-".$offset;
            } elseif ($offset < 0) {
                $offset = "+".abs($offset);
            } elseif ($offset === 0) {
                $offset = "+0";
            }
    
            pjAppController::setTimezone('Etc/GMT' . $offset);
            if (strpos($offset, '-') !== false)
            {
                $offset = str_replace('-', '+', $offset);
            } elseif (strpos($offset, '+') !== false) {
                $offset = str_replace('+', '-', $offset);
            }
            pjAppController::setMySQLServerTime($offset . ":00");
        }
    }
    
    public function beforeFilter()
    {
        $this->appendJs('jquery-1.8.2.min.js', PJ_THIRD_PARTY_PATH . 'jquery/');
        $this->appendJs('pjAdminCore.js');
        $this->appendCss('reset.css');
        
        $this->appendJs('jquery-ui.custom.min.js', PJ_THIRD_PARTY_PATH . 'jquery_ui/js/');
        $this->appendCss('jquery-ui.min.css', PJ_THIRD_PARTY_PATH . 'jquery_ui/css/smoothness/');
                
        $this->appendCss('admin.css');
        $this->appendCss('pj-all.css', PJ_FRAMEWORK_LIBS_PATH . 'pj/css/');
        
        if ($_GET['controller'] != 'pjInstaller')
        {
            $this->models['Option'] = pjOptionModel::factory();
            $this->option_arr = $this->models['Option']->getPairs($this->getForeignId());
            $this->set('option_arr', $this->option_arr);
            $this->setTime();
            
            if (!isset($_SESSION[$this->defaultLocale]))
            {
                pjObject::import('Model', 'pjLocale:pjLocale');
                $locale_arr = pjLocaleModel::factory()->where('is_default', 1)->limit(1)->findAll()->getData();
                if (count($locale_arr) === 1)
                {
                    $this->setLocaleId($locale_arr[0]['id']);
                }
            }
            pjAppController::setFields($this->getLocaleId());
        }
    }
    
    public function getForeignId()
    {
        return 1;
    }
    
    public function isEmployee()
    {
        return (int) $this->getRoleId() === 2;
    }
    
    public function isInvoiceReady()
    {
        return $this->isAdmin();
    }
    
    public function isCountryReady()
    {
        return $this->isAdmin();
    }
    
    public static function setFields($locale)
    {
        $fields = pjMultiLangModel::factory()
            ->select('t1.content, t2.key')
            ->join('pjField', "t2.id=t1.foreign_id", 'inner')
            ->where('t1.locale', $locale)
            ->where('t1.model', 'pjField')
            ->where('t1.field', 'title')
            ->findAll()
            ->getDataPair('key', 'content');
        $registry = pjRegistry::getInstance();
        $tmp = array();
        if ($registry->is('fields'))
        {
            $tmp = $registry->get('fields');
        }
        $arrays = array();
        foreach ($fields as $key => $value)
        {
            if (strpos($key, '_ARRAY_') !== false)
            {
                list($prefix, $suffix) = explode("_ARRAY_", $key);
                if (!isset($arrays[$prefix]))
                {
                    $arrays[$prefix] = array();
                }
                $arrays[$prefix][$suffix] = $value;
            }
        }
        require PJ_CONFIG_PATH . 'settings.inc.php';
        $fields = array_merge($tmp, $fields, $settings, $arrays);
        $registry->set('fields', $fields);
    }

    public static function jsonDecode($str)
    {
        $Services_JSON = new pjServices_JSON();
        return $Services_JSON->decode($str);
    }
    
    public static function jsonEncode($arr)
    {
        $Services_JSON = new pjServices_JSON();
        return $Services_JSON->encode($arr);
    }
    
    public static function jsonResponse($arr)
    {
        header("Content-Type: application/json; charset=utf-8");
        echo pjAppController::jsonEncode($arr);
        exit;
    }

    public function getLocaleId()
    {
        return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : 1;
    }
    
    public function setLocaleId($locale_id)
    {
        $_SESSION[$this->defaultLocale] = (int) $locale_id;
    }
    
    public static function friendlyURL($str, $divider='-')
    {
        $str = pjMultibyte::strtolower($str);
        $str = trim($str); //  trim leading and trailing spaces
        $str = preg_replace('/[_|\s]+/', $divider, $str); //  change all spaces and underscores to a hyphen
        $str = preg_replace('/\x{00C5}/u', 'AA', $str);
        $str = preg_replace('/\x{00C6}/u', 'AE', $str);
        $str = preg_replace('/\x{00D8}/u', 'OE', $str);
        $str = preg_replace('/\x{00E5}/u', 'aa', $str);
        $str = preg_replace('/\x{00E6}/u', 'ae', $str);
        $str = preg_replace('/\x{00F8}/u', 'oe', $str);
        $str = preg_replace('/[^a-z\x{0400}-\x{04FF}0-9-]+/u', '', $str); //  remove all non-cyrillic, non-numeric characters except the hyphen
        $str = preg_replace('/[-]+/', $divider, $str); //  replace multiple instances of the hyphen with a single instance
        $str = preg_replace('/^-+|-+$/', '', $str); //  trim leading and trailing hyphens
        return $str;
    }
    
    public function pjActionAfterInstall()
    {
        pjObject::import('Model', 'pjInvoice:pjInvoiceConfig');
        pjInvoiceConfigModel::factory()->set('id', 1)->modify(array(
            'o_booking_url' => "index.php?controller=pjAdminBookings&action=pjActionUpdate&uuid={ORDER_ID}"
        ));
        
        $query = sprintf("UPDATE `%s`
            SET `content` = :content
            WHERE `model` = :model
            AND `foreign_id` = (SELECT `id` FROM `%s` WHERE `key` = :key LIMIT 1)
            AND `field` = :field",
            pjMultiLangModel::factory()->getTable(), pjFieldModel::factory()->getTable()
        );
        pjAppModel::factory()->prepare($query)->exec(array(
            'content' => 'Booking URL - Token: {ORDER_ID}',
            'model' => 'pjField',
            'field' => 'title',
            'key' => 'plugin_invoice_i_booking_url'
        ));
        
        $query = sprintf("UPDATE `%s`
            SET `label` = :label
            WHERE `key` = :key
            LIMIT 1",
            pjFieldModel::factory()->getTable()
        );
        pjAppModel::factory()->prepare($query)->exec(array(
            'label' => 'Invoice plugin / Booking URL - Token: {ORDER_ID}',
            'key' => 'plugin_invoice_i_booking_url'
        ));
        
        pjObject::import('Model', 'pjLocale:pjLocale');
        pjLocaleModel::factory()->where("`id` != '1'")->eraseAll();
        pjMultiLangModel::factory()->where("`locale` != '1'")->eraseAll();
    }
    
    protected function pjActionGenerateInvoice($booking_id, $owner_id)
    {
        if (!isset($booking_id) || (int) $booking_id <= 0)
        {
            return array('status' => 'ERR', 'code' => 400, 'text' => 'ID is not set ot invalid.');
        }
        $arr = pjBookingModel::factory()->find($booking_id)->getData();
        if (empty($arr))
        {
            return array('status' => 'ERR', 'code' => 404, 'text' => 'Order not found.');
        }
        
        $bs_arr = pjBookingServiceModel::factory()
            ->select("t1.*, t2.content AS `name`")
            ->join('pjMultiLang', sprintf("t2.model='pjService' AND t2.foreign_id=t1.service_id AND t2.field='name'"), 'left outer')
            ->where('t1.booking_id', $booking_id)
            ->findAll()
            ->getData();
        
        $services = array();
        if (!empty($bs_arr))
        {
            foreach ($bs_arr as $service)
            {
                $services[] = array(
                    'name' => $service['name'],
                    'description' => NULL,
                    'qty' => 1,
                    'unit_price' => $service['price'],
                    'amount' => number_format(1 * $service['price'], 2, ".", "")
                );
            }
        } else {
            $services[] = array(
                'name' => 'Booking payment',
                'description' => '',
                'qty' => 1,
                'unit_price' => $arr['booking_total'],
                'amount' => $arr['booking_total']
            );
        }
        
        $map = array(
            'confirmed' => 'paid',
            'cancelled' => 'cancelled',
            'pending' => 'not_paid'
        );
        
        $response = $this->requestAction(
            array(
                'controller' => 'pjInvoice',
                'action' => 'pjActionCreate',
                'params' => array(
                    'key' => md5($this->option_arr['private_key'] . PJ_SALT),
                    // -------------------------------------------------
                    'uuid' => pjUtil::uuid(),
                    'order_id' => $arr['uuid'],
                    'owner_id' => $owner_id,
                    'foreign_id' => $arr['calendar_id'],
                    'issue_date' => ':CURDATE()',
                    'due_date' => ':CURDATE()',
                    'created' => ':NOW()',
                    // 'modified' => ':NULL',
                    'status' => @$map[$arr['booking_status']],
                    'subtotal' => $arr['booking_total'],
                    // 'discount' => $arr['discount'],
                    'tax' => $arr['booking_tax'],
                    // 'shipping' => $arr['shipping'],
                    'total' => $arr['booking_total'],
                    'paid_deposit' => 0,
                    'amount_due' => 0,
                    'currency' => $this->option_arr['o_currency'],
                    'notes' => $arr['c_notes'],
                    // 'y_logo' => $arr[''],
                    // 'y_company' => $arr[''],
                    // 'y_name' => $arr[''],
                    // 'y_street_address' => $arr[''],
                    // 'y_city' => $arr[''],
                    // 'y_state' => $arr[''],
                    // 'y_zip' => $arr[''],
                    // 'y_phone' => $arr[''],
                    // 'y_fax' => $arr[''],
                    // 'y_email' => $arr[''],
                    // 'y_url' => $arr[''],
                    'b_billing_address' => $arr['c_address_1'],
                    // 'b_company' => ':NULL',
                    'b_name' => $arr['c_name'],
                    'b_address' => $arr['c_address_1'],
                    'b_street_address' => $arr['c_address_2'],
                    'b_city' => $arr['c_city'],
                    'b_state' => $arr['c_state'],
                    'b_zip' => $arr['c_zip'],
                    'b_phone' => $arr['c_phone'],
                    // 'b_fax' => ':NULL',
                    'b_email' => $arr['c_email'],
                    // 'b_url' => $arr['url'],
                    // 's_shipping_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
                    // 's_company' => ':NULL',
                    // 's_name' => (int) $arr['same_as'] === 1 ? $arr['b_name'] : $arr['s_name'],
                    // 's_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_1'] : $arr['s_address_1'],
                    // 's_street_address' => (int) $arr['same_as'] === 1 ? $arr['b_address_2'] : $arr['s_address_2'],
                    // 's_city' => (int) $arr['same_as'] === 1 ? $arr['b_city'] : $arr['s_city'],
                    // 's_state' => (int) $arr['same_as'] === 1 ? $arr['b_state'] : $arr['s_state'],
                    // 's_zip' => (int) $arr['same_as'] === 1 ? $arr['b_zip'] : $arr['s_zip'],
                    // 's_phone' => $arr['phone'],
                    // 's_fax' => ':NULL',
                    // 's_email' => $arr['email'],
                    // 's_url' => $arr['url'],
                    // 's_date' => ':NULL',
                    // 's_terms' => ':NULL',
                    // 's_is_shipped' => ':NULL',
                    'items' => $services
                    // -------------------------------------------------
                )
            ),
            array('return')
        );

        return $response;
    }

    public static function getRawSlots($foreign_id, $date, $type, $option_arr)
    {
        $date_arr = pjDateModel::factory()->getDailyWorkingTime($foreign_id, $date, $type);
        
        if ($date_arr === false)
        {
            # There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
            $pjWorkingTimeModel = pjWorkingTimeModel::factory();
            $wt_data = $pjWorkingTimeModel->getWorkingTime($foreign_id, $type);
            $wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
            
            if (empty($wt_arr))
            {
                # It's Day off
                return false;
            }
            // $wt_arr['slot_length'] = $option_arr['slot_length'];
            return $wt_arr;
        } else {
            # There is custom working time/prices for given date
            if (count($date_arr) === 0)
            {
                # It's Day off
                return false;
            }
            return $date_arr;
        }
    }
    
    public static function getRawSlotsAdmin($foreign_id, $date, $type, $option_arr)
    {
        $date_arr = pjDateModel::factory()->getDailyWorkingTime($foreign_id, $date, $type);
    
        if ($date_arr === false)
        {
            # There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
            $pjWorkingTimeModel = pjWorkingTimeModel::factory();
            $wt_data = $pjWorkingTimeModel->getWorkingTime($foreign_id, $type);
            $wt_arr = $pjWorkingTimeModel->filterDateAdmin($wt_data, $date);
            if (empty($wt_arr))
            {
            # It's Day off
                return false;
            }
            // $wt_arr['slot_length'] = $option_arr['slot_length'];
            return $wt_arr;
        } else {
        # There is custom working time/prices for given date
            if (count($date_arr) === 0)
            {
                # It's Day off
                return false;
            }
            return $date_arr;
        }
    }
    
    public static function getSingleDateSlots($calendar_id, $date)
    {
        $pjDateModel = pjDateModel::factory();
        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
        
        $employee_arr = pjEmployeeModel::factory()
            //->where('t1.calendar_id', $calendar_id)
            ->where('t1.is_active', 1)
            ->findAll()
            ->getData();

        foreach ($employee_arr as $key => $employee)
        {
            $employee_arr[$key]['custom'] = $pjDateModel->reset()->getDailyWorkingTime($employee['id'], $date, 'employee');
            $wt_data = $pjWorkingTimeModel->reset()->getWorkingTime($employee['id'], 'employee');
            $employee_arr[$key]['default'] = $pjWorkingTimeModel->filterDate($wt_data, $date);
        }
        
        $general_custom = $pjDateModel->reset()->getDailyWorkingTime($calendar_id, $date, 'calendar');
        
        $start_ts = array();
        $end_ts = array();
        foreach ($employee_arr as $i => $employee)
        {
            if (is_array($employee['custom']) && !empty($employee['custom']))
            {
                $start_ts[$i] = $employee['custom']['start_ts'];
                $end_ts[$i] = $employee['custom']['end_ts'];
                continue;
            }
        
            if (!empty($general_custom))
            {
                $start_ts[$i] = $general_custom['start_ts'];
                $end_ts[$i] = $general_custom['end_ts'];
                continue;
            }

            if (is_array($employee['default']) && !empty($employee['default']))
            {
                $start_ts[$i] = $employee['default']['start_ts'];
                $end_ts[$i] = $employee['default']['end_ts'];
            }
        }
        
        if (empty($start_ts) || empty($end_ts))
        {
            $wt_data = $pjWorkingTimeModel->reset()->getWorkingTime($calendar_id, 'calendar');
            $general_default = $pjWorkingTimeModel->filterDate($wt_data, $date);
            if (empty($start_ts) && !empty($general_default))
            {
                $start_ts[] = $general_default['start_ts'];
            }
            if (empty($end_ts) && !empty($general_default))
            {
                $end_ts[] = $general_default['end_ts'];
            }
        }
        
        return array(
            'start_ts' => min($start_ts),
            'end_ts' => max($end_ts)
        );
    }
    
    public static function getRawSlotsInRange($foreign_id, $date_from, $date_to, $type)
    {
        $date_arr = pjDateModel::factory()->getRangeWorkingTime($foreign_id, $date_from, $date_to, $type);
        
        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
        $wt_data = $pjWorkingTimeModel->getWorkingTime($foreign_id, $type);

        $t_arr = array();
        foreach ($date_arr as $date => $item)
        {
            $t_arr[$date] = array();
            
            # There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
            if (empty($item))
            {
                $wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
                # It's Day off
                if (empty($wt_arr))
                {
                    $t_arr[$date]['is_dayoff'] = 'T';
                    continue;
                }
                
                $t_arr[$date] = $wt_arr;
                continue;
            }
            
            # Custom day is off
            if ($item['is_dayoff'] == 'T')
            {
                $t_arr[$date]['is_dayoff'] = 'T';
                continue;
            }
            
            $t_arr[$date] = $item;
        }
        
        return $t_arr;
    }
    
    public static function getRawSlotsPerEmployee($employee_id, $date, $cid)
    {
        $pjDateModel = pjDateModel::factory();
        
        # Get custom working time for given employee
        $date_arr = $pjDateModel->getDailyWorkingTime($employee_id, $date, 'employee');

        if ($date_arr !== false)
        {
            # It's Day off
            if (count($date_arr) === 0)
            {
                return false;
            }
            
            # Return custom working time per employee
            return $date_arr;
        }
        
        # There is not custom working time for given date & employee
         
        # Now check for default/global custom working time
        $date_arr = $pjDateModel->getDailyWorkingTime($cid, $date);
        if ($date_arr !== false)
        {
            # It's Day off
            if (count($date_arr) === 0)
            {
                return false;
            }
            
            # Return default/global custom working time
            return $date_arr;
        }
        
        # There is not default/global custom working time for given date,
        
        # Now get default working time for given employee per weekday (Monday, Tuesday...)
        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
        $wt_data = $pjWorkingTimeModel->getWorkingTime($employee_id, 'employee');
        $wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
        #$wt_arr = pjWorkingTimeModel::factory()->getWorkingTime($employee_id, $date, 'employee');
        if ($wt_arr === false)
        {
            return false; //FIXME
        }
        # It's Day off
        if (count($wt_arr) === 0)
        {
            return false;
        }
        
        # Return default working time per employee
        return $wt_arr;
    }
    
    public static function getRawSlotsPerEmployeeAdmin($employee_id, $date, $cid)
    {
        $pjDateModel = pjDateModel::factory();
    
        # Get custom working time for given employee
        //$date_arr = $pjDateModel->getDailyWorkingTime($employee_id, $date, 'employee');
        $date_arr = false;
        if ($date_arr !== false)
        {
            # It's Day off
            if (count($date_arr) === 0)
            {
                return false;
            }
            # Return custom working time per employee
            return $date_arr;
        }
    
        # There is not custom working time for given date & employee

        # Now check for default/global custom working time
        $date_arr = $pjDateModel->getDailyWorkingTime($cid, $date);

        if ($date_arr !== false)
        {
        # It's Day off
        if (count($date_arr) === 0)
            {
            return false;
        }
                
            # Return default/global custom working time
            return $date_arr;
        }
    
        # There is not default/global custom working time for given date,
    
        # Now get default working time for given employee per weekday (Monday, Tuesday...)
        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
        $wt_data = $pjWorkingTimeModel->getWorkingTime($employee_id, 'employee');
        $wt_arr = $pjWorkingTimeModel->filterDateAdmin($wt_data, $date);
        #$wt_arr = pjWorkingTimeModel::factory()->getWorkingTime($employee_id, $date, 'employee');
        if ($wt_arr === false)
        {
            return false; //FIXME
        }
        # It's Day off
        if (count($wt_arr) === 0)
        {
            return false;
        }

        # Return default working time per employee
        return $wt_arr;
    }
    
    public static function getDatesInRange($calendar_id, $date_from, $date_to)
    {
        # Build date array
        $_arr = array();
        $from = strtotime($date_from);
        $to = strtotime($date_to);
        if ($from > $to)
        {
            $tmp = $from;
            $from = $to;
            $to = $tmp;
        }
        for ($i = $from; $i <= $to; $i += 86400)
        {
            $_arr[date("Y-m-d", $i)] = '';
        }
        
        $pjDateModel = pjDateModel::factory();
        $pjWorkingTimeModel = pjWorkingTimeModel::factory();
        
        $employee_arr = pjEmployeeModel::factory()
            // banana code
            //->where('t1.calendar_id', $calendar_id)
            ->where('t1.is_active', 1)
            ->findAll()
            ->getData();

        foreach ($employee_arr as $key => $employee)
        {
            $employee_arr[$key]['custom'] = $pjDateModel->reset()->getRangeWorkingTime($employee['id'], $date_from, $date_to, 'employee');
            $employee_arr[$key]['default'] = $_arr;
            $wt_data = $pjWorkingTimeModel->reset()->getWorkingTime($employee['id'], 'employee');
            foreach ($_arr as $date => $whatever)
            {
                $employee_arr[$key]['default'][$date] = $pjWorkingTimeModel->filterDate($wt_data, $date);
            }
        }
        
        $general_custom = $pjDateModel->reset()->getRangeWorkingTime($calendar_id, $date_from, $date_to, 'calendar');
        
        $stack = array();
        $employee_cnt = count($employee_arr);
        foreach ($_arr as $date => $whatever)
        {
            $stack[$date] = array();
            foreach ($employee_arr as $key => $employee)
            {
                $stack[$date][$key] = NULL;
                
                if (!empty($employee['custom'][$date]) && isset($employee['custom'][$date]['is_dayoff']))
                {
                    $stack[$date][$key] = $employee['custom'][$date]['is_dayoff'] == 'F' ? 'ON' : 'OFF';
                    continue;
                }
                
                if (isset($general_custom[$date]) && !empty($general_custom[$date]) &&
                    isset($general_custom[$date]['is_dayoff']))
                {
                    $stack[$date][$key] = $general_custom[$date]['is_dayoff'] == 'F' ? 'ON' : 'OFF';
                    continue;
                }
                
                if (isset($employee['default'][$date]))
                {
                    $stack[$date][$key] = !empty($employee['default'][$date]) ? 'ON' : 'OFF';
                }
            }
        }
        
        $result = array();
        foreach ($stack as $date => $values)
        {
            if (in_array('ON', $values))
            {
                $result[$date] = 'ON';
            } else {
                $result[$date] = 'OFF';
            }
        }
        
        return $result;
    }
    
    static public function getSingleService($booking, $option_arr)
    {
        //  Before and after time is not included in booking time
        $booking_data = stripslashes($booking['service_name']) . ": ".
            date($option_arr['o_date_format'], strtotime($booking['date'])). ", ".
            date($option_arr['o_time_format'], $booking['start_ts'] + $booking['before'] * 60). " - ".
            date($option_arr['o_time_format'], $booking['start_ts'] + $booking['before'] * 60 + $booking['length'] * 60 + @$booking['extra_length'] * 60);
        return $booking_data;
    }
    
    static public function getMultiService($booking, $option_arr)
    {
        //  Before and after time is not included in booking time
        $booking_data = array();
        if (isset($booking['bs_arr']))
        {
            foreach ($booking['bs_arr'] as $item)
            {
                $booking_data[] = stripslashes($item['service_name']) . ": ".
                    date($option_arr['o_date_format'], strtotime($item['date'])). ", ".
                    date($option_arr['o_time_format'], $item['start_ts'] + $item['before'] * 60). " - ".
                    date($option_arr['o_time_format'], $item['start_ts'] + $item['before'] * 60 + $item['length'] * 60 + @$item['extra_length'] * 60);
            }
        }

        return join("\n", $booking_data);
    }
    
    static public function getTokens($booking, $option_arr, $type='single')
    {
        switch ($type)
        {
            case 'single':
                $booking_data = pjAppController::getSingleService($booking, $option_arr);
                break;
            case 'multi':
            default:
                $booking_data = pjAppController::getMultiService($booking, $option_arr);
                break;
        }

        $cc = $booking['payment_method'] == 'creditcard';
        $cancelURL = PJ_INSTALL_URL . 'index.php?controller=pjFrontEnd&action=pjActionCancel&cid='.$booking['calendar_id'].'&id='.$booking['booking_id'].'&hash='.sha1($booking['booking_id'].$booking['created'].PJ_SALT);
        
        $search = array(
            '{Name}', '{Email}', '{Phone}', '{Country}', '{City}',
            '{State}', '{Zip}', '{Address1}', '{Address2}', '{Notes}',
            '{CCType}', '{CCNum}', '{CCExpMonth}', '{CCExpYear}', '{CCSec}', '{PaymentMethod}',
            '{Price}', '{Deposit}', '{Total}', '{Tax}',
            '{BookingID}', '{Services}', '{CancelURL}'
        );
        $replace = array(
            $booking['c_name'], $booking['c_email'], $booking['c_phone'], $booking['country_name'], $booking['c_city'],
            $booking['c_state'], $booking['c_zip'], $booking['c_address_1'], $booking['c_address_2'], $booking['c_notes'],
            $cc ? $booking['cc_type'] : NULL,
            $cc ? $booking['cc_num'] : NULL,
            $cc ? $booking['cc_exp_month'] : NULL,
            $cc ? $booking['cc_exp_year'] : NULL,
            $cc ? $booking['cc_code'] : NULL,
            $booking['payment_method'],
            pjUtil::formatCurrencySign(number_format($booking['booking_price'], 2), $option_arr['o_currency']),
            pjUtil::formatCurrencySign(number_format($booking['booking_deposit'], 2), $option_arr['o_currency']),
            pjUtil::formatCurrencySign(number_format($booking['booking_total'], 2), $option_arr['o_currency']),
            pjUtil::formatCurrencySign(number_format($booking['booking_tax'], 2), $option_arr['o_currency']),
            $booking['uuid'], $booking_data, $cancelURL);

        return compact('search', 'replace');
    }
}
?>
