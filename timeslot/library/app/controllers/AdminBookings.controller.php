<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
/**
 * AdminBookings controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class AdminBookings extends Admin
{
/**
 * Create booking
 *
 * @access public
 * @return void
 */
    function create()
    {
        if ($this->isLoged())
        {
            if ($this->isAdmin() || $this->isOwner())
            {
                $err = NULL;
                if (isset($_POST['booking_create']))
                {
                    if ($this->isDemo())
                    {
                        $err = 7;
                    } else {
                        
                        Object::import('Model', array('Booking', 'BookingSlot'));
                        
                        
                        $passCheck = true;
                        foreach ($_SESSION[$this->cartName] as $cid => $date_arr)
                        {
                        	$BookingModel = new BookingModel();
                        	$BookingSlotModel = new BookingSlotModel();
                            if (!$passCheck) continue;
                            // Modified by Raccoon
                            // if ($cid != $this->getCalendarId())    continue;
                            foreach ($date_arr as $date => $time_arr)
                            {
                                if (!$passCheck) continue;
                                $t_arr = AppController::getRawSlots($cid, $date, $this->option_arr);
                                if ($t_arr === false)
                                {
                                    $passCheck = false;
                                    continue;
                                }
                                foreach ($time_arr as $time => $q)
                                {
                                    if (!$passCheck) continue;
                                    list($start_ts, $end_ts) = explode("|", $time);
                                    if ($t_arr['slot_limit'] <= $BookingSlotModel->checkBooking(/* $this->getCalendarId() */$cid, $start_ts, $end_ts))
                                    {
                                        $passCheck = false;
                                        continue;
                                    }
                                }
                            }
                            
                            if (!$passCheck)
                            {
                                $err = 11;
                            } else {
                                $data = array();
                                $data['calendar_id'] = $cid;
                                if ($_POST['payment_method'] == 'creditcard')
                                {
                                    $data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
                                }
                                    
                                $insert_id = $BookingModel->save(array_merge($_POST, $data));
                                if ($insert_id !== false && (int) $insert_id > 0)
                                {
                                    $slot = array();
                                    $slot['booking_id'] = $insert_id;
                                    
                                    foreach ($date_arr as $date => $time_arr)
                                    {
                                        $slot['booking_date'] = $date;
                                        foreach ($time_arr as $time => $q)
                                        {
                                            list($start_ts, $end_ts) = explode("|", $time);
                                            $slot['start_time'] = date("H:i:s", $start_ts);
                                            $slot['end_time'] = date("H:i:s", $end_ts);
                                            $slot['start_ts'] = $start_ts;
                                            $slot['end_ts'] = $end_ts;
                                            $BookingSlotModel->save($slot);
                                        }
                                     }
                                    
                                     $err = 1;
                                  } else {
                                        $err = 2;
                                  }
                              }
                          }
                      }
                      $_SESSION[$this->cartName] = array();
                    }
                Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=$err");
            } else {
                $this->tpl['status'] = 2;
            }
        } else {
            $this->tpl['status'] = 1;
        }
    }
/**
 * Delete booking, support AJAX too
 *
 * @access public
 * @return void
 */
    function delete()
    {
        if ($this->isLoged())
        {
            if ($this->isAdmin() || $this->isOwner())
            {
                if ($this->isDemo())
                {
                    $_GET['err'] = 7;
                    $this->index();
                    return;
                }
                
                if ($this->isXHR())
                {
                    $this->isAjax = true;
                    $id = $_POST['id'];
                } else {
                    $id = $_GET['id'];
                }
                
                Object::import('Model', array('Booking', 'Calendar'));
                $BookingModel = new BookingModel();
                $CalendarModel = new CalendarModel();
                
                $BookingModel->addJoin($BookingModel->joins, $CalendarModel->getTable(), 'TC', array('TC.id' => 't1.calendar_id'), array('TC.user_id'));
                $arr = $BookingModel->get($id);
                if (count($arr) == 0)
                {
                    if ($this->isXHR())
                    {
                        $_GET['err'] = 8;
                        $this->index();
                        return;
                    } else {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=8");
                    }
                } elseif ($this->isOwner() && $arr['user_id'] != $this->getUserId()) {
                    if ($this->isXHR())
                    {
                        $_GET['err'] = 9;
                        $this->index();
                        return;
                    } else {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=9");
                    }
                }
                
                if ($BookingModel->delete($id))
                {
                    Object::import('Model', 'BookingSlot');
                    $BookingSlotModel = new BookingSlotModel();
                    $BookingSlotModel->delete(array('booking_id' => $id));
                    
                    if ($this->isXHR())
                    {
                        $_GET['err'] = 3;
                        $this->index();
                        return;
                    } else {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=3");
                    }
                } else {
                    if ($this->isXHR())
                    {
                        $_GET['err'] = 4;
                        $this->index();
                        return;
                    } else {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=4");
                    }
                }
            } else {
                $this->tpl['status'] = 2;
            }
        } else {
            $this->tpl['status'] = 1;
        }
    }
    
    function export()
    {
        $this->isAjax = true;
        
        Object::import('Model', array('Booking', 'BookingSlot', 'Country', 'Calendar'));
        $BookingModel = new BookingModel();
        $BookingSlotModel = new BookingSlotModel();
        $CountryModel = new CountryModel();
        $CalendarModel = new CalendarModel();
        
        require_once APP_PATH . 'locale/' . $this->getLanguage() . '.php';
        
        $csv_header = array();
        $fields = array();
        foreach ($BookingSlotModel->schema as $column)
        {
            if (!in_array($column['name'], array('start_ts', 'end_ts'))) {
                $csv_header[] = @$TS_LANG['booking_export_map'][$column['name']];
            }
        }
        foreach ($BookingModel->schema as $column)
        {
            if (!in_array($column['name'], array('id', 'created')))
            {            
                $fields[] = 'TB.' . $column['name'];
                $csv_header[] = @$TS_LANG['booking_export_map'][$column['name']];
            }
        }
        $opts = array();
        if (isset($_POST['date_from']) && !empty($_POST['date_from']) && isset($_POST['date_to']) && !empty($_POST['date_to']))
        {
            $opts['t1.start_ts'] = array(strtotime($_POST['date_from']), '>=', 'int');
            $opts['t1.end_ts'] = array(strtotime($_POST['date_to']), '<=', 'int');
        } else {
            if (isset($_POST['date_to']) && !empty($_POST['date_to']))
            {
                $opts['t1.end_ts'] = array(strtotime($_POST['date_to']), '<=', 'int');
            } elseif (isset($_POST['date_from']) && !empty($_POST['date_from'])) {
                $opts['t1.start_ts'] = array(strtotime($_POST['date_from']), '>=', 'int');
            }
        }    

        $calendar_id = isset($_POST['calendar_id']) && (int) $_POST['calendar_id'] > 0 ? (int) $_POST['calendar_id'] : $this->getCalendarId();
        $BookingSlotModel->addJoin($BookingSlotModel->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => $calendar_id), $fields, 'inner');
        $BookingSlotModel->addJoin($BookingSlotModel->joins, $CalendarModel->getTable(), 'TC', array('TC.id' => $calendar_id), array('TC.calendar_title'));
        $BookingSlotModel->addJoin($BookingSlotModel->joins, $CountryModel->getTable(), 'TCO', array('TCO.id' => 'TB.customer_country'), array('TCO.country_title'));
        $arr = $BookingSlotModel->getAll(array_merge($opts, array('col_name' => 't1.booking_id DESC, t1.booking_date DESC, t1.start_time', 'direction' => 'asc')));

        switch (strtolower($_POST['format']))
        {
            case 'csv':
                $mime_type = 'text/csv';
                $ext = '.csv';
                $row = array();
                $row[] = join(',', $csv_header);
                foreach ($arr as $booking)
                {
                    $cell = array();
                    foreach ($BookingSlotModel->schema as $column)
                    {
                        if ($column['name'] == 'booking_date') 
                        {
                            $cell[] = date($this->option_arr['date_format'], strtotime($booking[$column['name']]));
                        } elseif (in_array($column['name'], array('start_time', 'end_time'))) {
                            $cell[] = date($this->option_arr['time_format'], strtotime($booking[$column['name']]));
                        } elseif (!in_array($column['name'], array('start_ts', 'end_ts'))) {
                            $cell[] = $booking[$column['name']];
                        }
                    }
                    foreach ($BookingModel->schema as $column)
                    {
                        if ($column['name'] == 'customer_country')
                        {
                            $cell[] = $booking['country_title'];
                        } elseif (!in_array($column['name'], array('id', 'created'))) {
                            $cell[] = $booking[$column['name']];
                        }
                    }
                    $row[] = join(',', array_map('_clean', $cell));
                }
                $content = join("\n", $row);
                break;
            case 'xml':
                $mime_type = 'text/xml';
                $ext = '.xml';
                $row[] = '<bookings>';
                foreach ($arr as $booking)
                {
                    $cell = array();
                    $cell[] = "\t<booking>";
                    foreach ($BookingSlotModel->schema as $column)
                    {
                        if ($column['name'] == 'booking_date') 
                        {
                            $cell[] = "\t\t<". $column['name'] . ">" . htmlspecialchars(date($this->option_arr['date_format'], strtotime($booking[$column['name']]))) . "</" . $column['name'] . ">";
                        } elseif (in_array($column['name'], array('start_time', 'end_time'))) {
                            $cell[] = "\t\t<". $column['name'] . ">" . htmlspecialchars(date($this->option_arr['time_format'], strtotime($booking[$column['name']]))) . "</" . $column['name'] . ">";
                        } elseif (!in_array($column['name'], array('start_ts', 'end_ts'))) {
                            $cell[] = "\t\t<". $column['name'] . ">" . htmlspecialchars($booking[$column['name']]) . "</" . $column['name'] . ">";
                        }
                    }
                    foreach ($BookingModel->schema as $column)
                    {
                        if ($column['name'] == 'customer_country')
                        {
                            $cell[] = "\t\t<" . $column['name'] . ">" . htmlspecialchars($booking['country_title']) . "</" . $column['name'] . ">";
                        } elseif (!in_array($column['name'], array('id', 'created'))) {                        
                            $cell[] = "\t\t<" . $column['name'] . ">" . htmlspecialchars($booking[$column['name']]) . "</" . $column['name'] . ">";
                        }
                    }
                    $cell[] = "\t</booking>";
                    $row[] = join("\n", $cell);
                }
                $row[] = "</bookings>";
                $content = join("\n", $row);
                break;
            case 'icalendar':
                $mime_type = 'text/calendar';
                $ext = '.ics';
                
                $timezone = Util::getTimezone($this->option_arr['timezone']);
                
                $row[] = "BEGIN:VCALENDAR";
                $row[] = "VERSION:2.0";
                $row[] = "PRODID:-//Time Slots Booking Calendar//NONSGML Foobar//EN";
                $row[] = "METHOD:UPDATE"; // requied by Outlook
                foreach ($arr as $booking)
                {
                    $cell = array();
                    $cell[] = "BEGIN:VEVENT";
                    $cell[] = "UID:".md5($booking["id"]); // required by Outlok
                    $cell[] = "SEQUENCE:1";
                    $cell[] = "DTSTAMP;TZID=$timezone:".date('Ymd',$booking["start_ts"])."T000000Z"; // required by Outlook
                    $cell[] = "DTSTART;TZID=$timezone:".date('Ymd',$booking["start_ts"])."T".date('His',$booking["start_ts"])."Z"; 
                    $cell[] = "DTEND;TZID=$timezone:".date('Ymd', $booking["end_ts"])."T".date('His', $booking["end_ts"])."Z";
                    $cell[] = "SUMMARY:Booking";
                    $cell[] = "DESCRIPTION: Name: ".stripslashes($booking["customer_name"])."; Email: ".stripslashes($booking["customer_email"])."; Phone: ".stripslashes($booking["customer_phone"])."; Price: ".stripslashes($booking["booking_total"])."; Notes: ".stripslashes(preg_replace('/\n|\r|\r\n/', ' ', $booking["customer_notes"]))."; Status: ".stripslashes($booking["booking_status"]);
                    $cell[] = "ATTENDEE;CN=\"".stripslashes($booking["customer_name"])."\";PARTSTAT=NEEDS-ACTION;ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:".stripslashes($booking["customer_email"]);
                    $cell[] = "END:VEVENT";
                    $row[] = join("\n", $cell);
                }
                $row[] = "END:VCALENDAR";
                $content = join("\n", $row);
                break;
        }
        AppController::download($content, 'TSBC_Bookings_' . time() . $ext, $mime_type);
    }
    
    function getPrices()
    {
        $this->isAjax = true;
        
        if ($this->isXHR())
        {
            $calendar_id = isset($_GET['calendar_id']) ? $_GET['calendar_id'] : $this->getCalendarId();
            $arr = AppController::getCartTotal($calendar_id, $this->cartName, $this->option_arr);
            $arr['code'] = 200;
            
            Object::import('Component', 'Services_JSON');
            $Services_JSON = new Services_JSON();

            header("Content-type: text/json");
            echo $Services_JSON->encode($arr);
            exit;
        }
    }
    
    // Added by Raccoon
    function getBulkPrices() {
    $this->isAjax = true;
        
        if ($this->isXHR())
        {
            // $calendar_id = isset($_GET['calendar_id']) ? $_GET['calendar_id'] : $this->getCalendarId();
            $cal_model = new CalendarModel();
            $callist = $cal_model->getAll(array('col_name'=>'t1.id', 'direction'=>'asc'));
            foreach ($callist as $k => $v) {
                $arr['slots'][$v['id']] = AppController::getCartTotal($v['id'], $this->cartName, $this->option_arr);
                $arr['code'] = 200;
            }
            
            Object::import('Component', 'Services_JSON');
            $Services_JSON = new Services_JSON();

            header("Content-type: text/json");
            echo $Services_JSON->encode($arr);
            exit;
        }
    }
    
    function getSlots()
    {
        $this->isAjax = true;
        
        if ($this->isXHR())
        {
            Object::import('Model', array('BookingSlot', 'Booking', 'Calendar'));
            
            $CalendarModel = new CalendarModel();
            $all_cal = $CalendarModel->getAll(array('col_name'=>'t1.id', "direction"=>"asc"));
            // Added by Raccoon
            foreach ($all_cal as $k=>$v) {
                $BookingModel = new BookingModel();
                $BookingSlotModel = new BookingSlotModel();
                $t_arr = AppController::getRawSlots(/* $this->getCalendarid() */$v['id'], $_GET['date'], $this->option_arr);
                
                $this->tpl['slots'][$v['id'].""]['name'] = $v['calendar_title'];
                
                if ($t_arr === false)
                {
                    # It's Day off
                    $this->tpl['slots'][$v['id'].""]['dayoff'] = true;
                    //return;
                    continue;
                }
                
    
                $this->tpl['slots'][$v['id'].""]['price_arr'] = AppController::getPricesDate(/* $this->getCalendarid() */$v['id'], $_GET['date'], $this->option_arr);
    
                # Get booked slots for given date
                $BookingSlotModel->addJoin($BookingSlotModel->joins, $BookingModel->getTable(), 'TB', array('TB.id' => 't1.booking_id', 'TB.calendar_id' => /* $this->getCalendarId() */$v['id'],'(booking_status' =>" 'pending' OR booking_status='confirmed' ) "), array('TB.calendar_id'), 'inner');
                $bs_arr = $BookingSlotModel->getAll(array('t1.booking_date' => $_GET['date'], 't1.booking_status' => array("('cancelled')", 'NOT IN', 'null')));
                $this->tpl['slots'][$v['id'].""]['bs_arr'] = $bs_arr;
                $this->tpl['slots'][$v['id'].""]['t_arr'] = $t_arr;
            }
        }
    }
/**
 * List of bookings
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
            if ($this->isAdmin() || $this->isOwner())
            {
                $opts = array();
                //$opts['t1.calendar_id'] = $this->getCalendarId();
                if ($this->isOwner())
                {
                    $opts['TC.user_id'] = $this->getUserId();
                }
                if (isset($_GET['q']) && !empty($_GET['q']))
                {
                    $q = Object::escapeString($_GET['q']);
                    $opts['(t1.id'] = array("'$q' OR t1.customer_name LIKE '%$q%' OR t1.customer_email LIKE '%$q%' OR t1.customer_phone LIKE '%$q%')", '=', 'null');
                }
                Object::import('Model', array('Booking', 'Calendar', 'Country', 'BookingSlot'));
                $BookingModel = new BookingModel();
                $BookingSlotModel = new BookingSlotModel();
                $CalendarModel = new CalendarModel();
                $CountryModel = new CountryModel();
                                
                $page = isset($_GET['page']) && (int) $_GET['page'] > 0 ? intval($_GET['page']) : 1;
                $count = $BookingModel->getCount($opts);
                $row_count = 10;
                $pages = ceil($count / $row_count);
                $offset = ((int) $page - 1) * $row_count;
                
                #$BookingModel->debug = 1;
                $BookingModel->addJoin($BookingModel->joins, $CalendarModel->getTable(), 'TC', array('TC.id' => 't1.calendar_id'), array('TC.calendar_title'), 'inner');
                $arr = $BookingModel->getAll(array_merge($opts, compact('offset', 'row_count'), array('col_name' => 't1.created', 'direction' => 'desc')));
                foreach ($arr as $k => $v)
                {
                    $arr[$k]['booking_slots'] = $BookingSlotModel->getAll(array('t1.booking_id' => $v['id'], 'col_name' => 't1.booking_date desc, t1.start_time', 'direction' => 'asc'));
                }
                
                $this->tpl['arr'] = $arr;
                $this->tpl['paginator'] = array('pages' => $pages, 'row_count' => $row_count, 'count' => $count);
                $this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
                
                
                // Customer
                $cpage = isset($_GET['cpage']) && (int) $_GET['cpage'] > 0 ? intval($_GET['cpage']) : 1;
                $ccount = array();
                $ccount = $BookingModel->getAll(array('group_by' => 't1.customer_email'));
                
                $row_count = 10;
                $pages = ceil(count($ccount) / $row_count);
                $offset = ((int) $cpage - 1) * $row_count;
                
                $this->tpl['carr'] = $BookingModel->getcustomer(array_merge(compact('offset', 'row_count'), array('col_name' => 't1.customer_name', 'direction' => 'ASC', 'group_by' => 't1.customer_email')));
                $this->tpl['cpaginator'] = compact('pages');
                //End Customer
                
                $_SESSION[$this->cartName] = array();
                
                $this->tpl['calendar'] = $this->calendar($this->getCalendarId());
                $this->css[] = array('file' => 'calendar.css', 'path' => CSS_PATH);
                $this->css[] = array('file' => 'index.php?controller=AdminCalendars&action=css&cid=' . $this->getCalendarId(), 'path' => NULL);
                
                $this->js[] = array('file' => 'jquery.ui.button.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
                $this->js[] = array('file' => 'jquery.ui.position.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
                $this->js[] = array('file' => 'jquery.ui.dialog.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
                
                $this->css[] = array('file' => 'jquery.ui.button.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
                $this->css[] = array('file' => 'jquery.ui.dialog.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');

                $this->js[] = array('file' => 'jquery.effects.core.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
                
                $this->js[] = array('file' => 'jabb-0.2.js', 'path' => JS_PATH);
                $this->js[] = array('file' => 'jabb.sort.js', 'path' => JS_PATH);
                                
                # Datepicker
                $this->js[] = array('file' => 'jquery.ui.datepicker.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
                $this->css[] = array('file' => 'jquery.ui.datepicker.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
                
                $this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
                $this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
            } else {
                $this->tpl['status'] = 2;
            }
        } else {
            $this->tpl['status'] = 1;
        }
    }
    
    
/**
 * Download CSV
 * */
    function download_csv(){
        if ($this->isLoged()) {
            
            Object::import('Model', array('Booking'));
            $BookingModel = new BookingModel();
        
            $this->tpl['arr_download_csv'] = $BookingModel->getcustomer( array('col_name' => 't1.customer_name', 'direction' => 'desc', 'group_by' => 't1.customer_email'));
            $this->array_to_csv_download($this->tpl['arr_download_csv']);
            
        }
    }
    
    function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    
        // Download the file
        $output = '';
        $output .= 'Name, Phome, Mail, Count';
        $output .= "\n";
        foreach ($array as $a) {
            $output .= $a['customer_name'] . ', ' . $a['customer_phone'] . ', ' . $a['customer_email'] . ', ' . $a['count'];
            $output .= "\n";
        }
    
        $output = str_replace('  ', ' ', $output);
        $output = trim($output);
    
        $filename = "Customer.csv";
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$filename);
    
        echo $output;
        exit;
    
    }
/**
 * Update booking
 *
 * @param int $id
 * @access public
 * @return void
 */
    function update($id=null)
    {
        if ($this->isLoged())
        {
            if ($this->isAdmin() || $this->isOwner())
            {
                Object::import('Model', array('Booking', 'BookingSlot'));
                $BookingModel = new BookingModel();
                $BookingSlotModel = new BookingSlotModel();
                
                if (isset($_POST['booking_update']))
                {
                    if ($this->isDemo())
                    {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=7");
                    }

                    $data = array();
                    if ($_POST['payment_method'] == 'creditcard')
                    {
                        $data['cc_exp'] = $_POST['cc_exp_year'] . '-' . $_POST['cc_exp_month'];
                    } else {
                        $data['cc_type'] = array('NULL');
                        $data['cc_exp'] = array('NULL');
                        $data['cc_num'] = array('NULL');
                        $data['cc_code'] = array('NULL');
                    }
                    
                    $BookingModel->update(array_merge($_POST, $data));
                    
                    Object::import('Model', 'WorkingTime');
                    $WorkingTimeModel = new WorkingTimeModel();
                    $wt_arr = $WorkingTimeModel->get($this->getCalendarId());
                    
                    $BookingSlotModel->delete(array('booking_id' => $_POST['id']));
                    $slot = array();
                    $slot['booking_id'] = $_POST['id'];
                    foreach ($_SESSION[$this->cartName] as $cid => $date_arr)
                    {
                        if ($cid != $this->getCalendarId())
                        {
                            continue;
                        }
                        foreach ($date_arr as $date => $time_arr)
                        {
                            $slot['booking_date'] = $date;
                            $dayOfWeek = strtolower(date("l", strtotime($date)));
                            foreach ($time_arr as $time => $q)
                            {
                                list($start_ts, $end_ts) = explode("|", $time);
                                if ((int) @$wt_arr[$dayOfWeek . '_limit'] > $BookingSlotModel->checkBooking($this->getCalendarId(), $start_ts, $end_ts))
                                {
                                    $slot['start_time'] = date("H:i:s", $start_ts);
                                    $slot['end_time'] = date("H:i:s", $end_ts);
                                    $slot['start_ts'] = $start_ts;
                                    $slot['end_ts'] = $end_ts;
                                    $BookingSlotModel->save($slot);
                                }
                            }
                        }
                    }
                    
                    Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=5");
                    
                } else {
                    $arr = $BookingModel->get($id);
                    
                    if (count($arr) == 0)
                    {
                        Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminBookings&action=index&err=8");
                    }
                    $this->tpl['arr'] = $arr;
                    
                    if ($this->getCalendarId() != $arr['calendar_id'])
                    {
                        $_SESSION[$this->default_user]['calendar_id'] = $arr['calendar_id'];
                        $this->option_arr = $this->models['Option']->getPairs($arr['calendar_id']);
                        $this->tpl['option_arr'] = $this->option_arr;
                
                        $this->setTime();
                    }
                    
                    Object::import('Model', array('Country'));
                    $CountryModel = new CountryModel();
                    
                    $this->tpl['country_arr'] = $CountryModel->getAll(array('t1.status' => 'T', 'col_name' => 't1.country_title', 'direction' => 'asc'));
                    $bs_arr = $BookingSlotModel->getAll(array('t1.booking_id' => $id, 'col_name' => '', 'direction' => 'asc'));
                    $cart_arr = array();
                    foreach ($bs_arr as $v)
                    {
                        if (!array_key_exists($v['booking_date'], $cart_arr))
                        {
                            $cart_arr[$v['booking_date']] = array();
                        }
                        $cart_arr[$v['booking_date']][$v['start_ts'] . "|" . $v['end_ts']] = 1;
                    }
                    $_SESSION[$this->cartName] = array($arr['calendar_id'] => $cart_arr);
                    //$BookingSlotModel->checkBooking($bs_arr[2], 3);
                    
                    $this->tpl['calendar'] = $this->calendar($this->getCalendarId());
                    $this->css[] = array('file' => 'calendar.css', 'path' => CSS_PATH);
                    $this->css[] = array('file' => 'index.php?controller=AdminCalendars&action=css&cid=' . $this->getCalendarId(), 'path' => NULL);

                    $this->js[] = array('file' => 'jabb-0.2.js', 'path' => JS_PATH);
                    $this->js[] = array('file' => 'jabb.sort.js', 'path' => JS_PATH);
                
                    $this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
                    $this->js[] = array('file' => 'adminBookings.js', 'path' => JS_PATH);
                }
            } else {
                $this->tpl['status'] = 2;
            }
        } else {
            $this->tpl['status'] = 1;
        }
    }
}

function _clean($val)
{
    $newLines = array("\r\n", "\n\r", "\n", "\r");
    
    return str_replace($newLines, " ", '"' . $val . '"');
}
?>
