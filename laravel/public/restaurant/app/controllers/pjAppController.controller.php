<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once FRAMEWORK_PATH . 'pjController.class.php';
class pjAppController extends pjController
{
	var $models = array();
	
    function setTimezone($timezone="UTC")
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

    function setMySQLServerTime($offset="-0:00")
    {
		mysql_query("SET SESSION time_zone = '$offset';");
    }
        
	function responseJson($arr)
	{
		header("Content-type: application/json; charset=utf-8");
		echo pjAppController::toJson($arr);
		exit;
	}
	
	function toJson($arr)
	{
		pjObject::import('Component', 'pjServices_JSON');
		$pjServices_JSON = new pjServices_JSON();
		return $pjServices_JSON->encode($arr);
	}

	function getPrice($option_arr, $date, $time, $code=null)
	{
		$code_arr = array();
		if (!is_null($code))
		{
			pjObject::import('Model', 'pjVoucher');
			$pjVoucherModel = new pjVoucherModel();
			$code_arr = $pjVoucherModel->getVoucher($code, $date, $time);
		}
		$price = $option_arr['booking_price'];
		
		if (count($code_arr) > 0)
		{
			switch ($code_arr['type'])
			{
				case 'percent':
					$price_after_discount = $price - (($price * $code_arr['discount']) / 100);
					$price_after_discount = $price_after_discount > 0 ? $price_after_discount : 0;
					break;
				case 'amount':
					$price_after_discount = $price - $code_arr['discount'] > 0 ? $price - $code_arr['discount'] : 0;
					break;
			}
			$total = $price_after_discount;
			
			$p = array(
				'type' => $code_arr['type'],
				'discount' => $code_arr['discount'],
				'discount_formated' => $code_arr['type'] == 'amount' ? pjUtil::formatCurrencySign(number_format($code_arr['discount'], 2), $option_arr['currency']) : (float) $code_arr['discount'] .'%',
				'price_before_discount' => round($price, 2),
				'price_after_discount' => round($price_after_discount, 2),
				'price_before_formated' => pjUtil::formatCurrencySign(number_format($price, 2), $option_arr['currency']),
				'price_after_formated' => pjUtil::formatCurrencySign(number_format($price_after_discount, 2), $option_arr['currency']),
				'total' => round($total, 2)
			);
		} else {
			$total = $price;
		
			$p = array(
				'price' => round($price, 2),
				'price_formated' => pjUtil::formatCurrencySign(number_format($price, 2), $option_arr['currency']),
				'total' => round($total, 2)
			);
		}
		return $p;
	}

	function getData($option_arr, $booking_arr, $salt)
	{
		$country = NULL;
		if (isset($booking_arr['c_country']) && !empty($booking_arr['c_country']))
		{
			pjObject::import('Model', 'pjCountry');
			$pjCountryModel = new pjCountryModel();
			$country_arr = $pjCountryModel->get($booking_arr['c_country']);
			if (count($country_arr) > 0)
			{
				$country = $country_arr['country_title'];
			}
		}
		
		$row = array();
		if (isset($booking_arr['table_arr']))
		{
			foreach ($booking_arr['table_arr'] as $v)
			{
				$row[] = stripslashes($v['name']);
			}
		}
		$booking_data = count($row) > 0 ? join("\n", $row) : NULL;
		$dt = NULL;
		if (isset($booking_arr['dt']) && !empty($booking_arr['dt']))
		{
			$tm = strtotime(@$booking_arr['dt']);
			$dt = date($option_arr['date_format'], $tm) . ", " . date($option_arr['time_format'], $tm);
		}
		
		$cancelURL = INSTALL_URL . 'index.php?controller=pjFront&action=cancel&id='.@$booking_arr['id'].'&hash='.sha1(@$booking_arr['id'].@$booking_arr['created'].$salt);
		$search = array(
			'{Title}', '{FirstName}', '{LastName}', '{Email}', '{Phone}', '{Country}',
			'{City}', '{State}', '{Zip}', '{Address}',
			'{Company}', '{CCType}', '{CCNum}', '{CCExp}',
			'{CCSec}', '{PaymentMethod}', '{UniqueID}', '{DtFrom}',
			'{Total}', '{People}', '{Notes}',
			'{BookingID}', '{Table}', '{CancelURL}');
		$replace = array(
			$booking_arr['c_title'], $booking_arr['c_fname'], $booking_arr['c_lname'], $booking_arr['c_email'], $booking_arr['c_phone'], $country,
			$booking_arr['c_city'], $booking_arr['c_state'], $booking_arr['c_zip'], $booking_arr['c_address'],
			$booking_arr['c_company'], @$booking_arr['cc_type'], @$booking_arr['cc_num'], (@$booking_arr['payment_method'] == 'creditcard' ? @$booking_arr['cc_exp'] : NULL),
			@$booking_arr['cc_code'], @$booking_arr['payment_method'], @$booking_arr['uuid'], $dt,
			@$booking_arr['total'] . " " . $option_arr['currency'], @$booking_arr['people'], @$booking_arr['c_notes'],
			@$booking_arr['id'], $booking_data, $cancelURL);

		return compact('search', 'replace');
	}
	
	function getWorkingTime($date)
	{
		pjObject::import('Model', 'pjDate');
		$pjDateModel = new pjDateModel();
			
		$date_arr = $pjDateModel->getWorkingTime($date);
		if ($date_arr === false)
		{
			# There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
			pjObject::import('Model', 'pjWorkingTime');
			$pjWorkingTimeModel = new pjWorkingTimeModel();
			$wt_arr = $pjWorkingTimeModel->getWorkingTime(1, $date);
			if (count($wt_arr) == 0)
			{
				# It's Day off
				return false;
			}
			$t_arr = $wt_arr;
		} else {
			# There is custom working time/prices for given date
			if (count($date_arr) == 0)
			{
				# It's Day off
				return false;
			}
			$t_arr = $date_arr;
		}
		return $t_arr;
	}
}
?>