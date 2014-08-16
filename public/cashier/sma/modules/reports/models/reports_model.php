<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*
| -----------------------------------------------------
| PRODUCT NAME: 	SCHOOL MANAGER 
| -----------------------------------------------------
| AUTHER:			MIAN SALEEM 
| -----------------------------------------------------
| EMAIL:			saleem@tecdiary.com 
| -----------------------------------------------------
| COPYRIGHTS:		RESERVED BY TECDIARY IT SOLUTIONS
| -----------------------------------------------------
| WEBSITE:			http://tecdiary.net
| -----------------------------------------------------
|
| MODULE: 			Reports
| -----------------------------------------------------
| This is reports module model file.
| -----------------------------------------------------
*/


class Reports_model extends CI_Model
{
	
	
	public function __construct()
	{
		parent::__construct();

	}
	
		
	public function getAllProducts() 
	{
		$q = $this->db->get('products');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getAllUsers() 
	{
		$q = $this->db->get('users');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getAllCategories() 
	{
		$q = $this->db->get('categories');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getStockValue() 
	{
        $ownerId = $this->getOwnerId();
		$q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(warehouses_products.quantity), 0)*price as by_price, COALESCE(sum(warehouses_products.quantity), 0)*cost as by_cost FROM  ". $this->db->dbprefix('products') ." AS products JOIN  ". $this->db->dbprefix('warehouses_products') ." AS warehouses_products ON warehouses_products.product_id=products.id WHERE products.owner_id = {$ownerId} GROUP BY products.id )a");
		 if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;
	}
	
	public function getWarehouseStockValue($id) 
	{
        $ownerId = $this->getOwnerId();
		 $q = $this->db->query("SELECT SUM(by_price) as stock_by_price, SUM(by_cost) as stock_by_cost FROM ( Select COALESCE(sum(warehouses_products.quantity), 0)*price as by_price, COALESCE(sum(warehouses_products.quantity), 0)*cost as by_cost FROM  ". $this->db->dbprefix('products') ." AS products JOIN  ". $this->db->dbprefix('warehouses_products') ." AS warehouses_products ON warehouses_products.product_id=products.id WHERE products.owner_id =  {$ownerId} AND warehouses_products.warehouse_id = ? GROUP BY products.id ) a", array($id));
		 if( $q->num_rows() > 0 )
		  {
			return $q->row();
		  } 
		
		  return FALSE;
	}
	
	public function getmonthlyPurchases() 
	{
        $ownerId = $this->getOwnerId();
		$myQuery = "SELECT (CASE WHEN date_format( date, '%b' ) Is Null THEN 0 ELSE date_format( date, '%b' ) END) as month, SUM( COALESCE( total, 0 ) ) AS purchases FROM  ". $this->db->dbprefix('purchases') ." AS purchases WHERE owner_id = {$ownerId} AND date >= date_sub( now( ) , INTERVAL 12 MONTH ) GROUP BY date_format( date, '%b' ) ORDER BY date_format( date, '%m' ) ASC";
		$q = $this->db->query($myQuery);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getChartData() 
	{
        $ownerId = $this->getOwnerId();
		$myQuery = "SELECT S.month,
					   COALESCE(S.sales, 0) as sales,
					   COALESCE( P.purchases, 0 ) as purchases,
					   COALESCE(S.tax1, 0) as tax1,
					   COALESCE(S.tax2, 0) as tax2,
					   COALESCE( P.ptax, 0 ) as ptax
					FROM (	SELECT	date_format(date, '%Y-%m') Month,
								SUM(total) Sales,
								SUM(total_tax) tax1,
								SUM(total_tax2) tax2
						FROM  ". $this->db->dbprefix('sales') ." AS sales
						WHERE sales.owner_id = {$ownerId} AND sales.date >= date_sub( now( ) , INTERVAL 12 MONTH )
						GROUP BY date_format(date, '%Y-%m')) S
					LEFT JOIN (	SELECT	date_format(date, '%Y-%m') Month,
									SUM(total_tax) ptax,
									SUM(total) purchases
							FROM  ". $this->db->dbprefix('purchases') ." AS purchases
							GROUP BY date_format(date, '%Y-%m')) P
					ON S.Month = P.Month
					GROUP BY S.Month
					ORDER BY S.Month";
		$q = $this->db->query($myQuery);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	/*public function getDailySales() 
	{
		$year = '2013'; $month = '3';
		$myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date, SUM( COALESCE( total, 0 ) ) AS sales, SUM( COALESCE( total_tax, 0 ) ) as tax1, SUM( COALESCE( total_tax2, 0 ) ) as tax2
			FROM sales
			WHERE DATE_FORMAT( date,  '%Y-%m' ) =  '2013-4'
			GROUP BY DATE_FORMAT( date,  '%e' )";
		$q = $this->db->query($myQuery);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}*/
	
	
	public function getAllWarehouses() 
	{
		$q = $this->db->get('warehouses');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getAllCustomers() 
	{
		$q = $this->db->get('customers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getAllBillers() 
	{
		$q = $this->db->get('billers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getAllSuppliers() 
	{
		$q = $this->db->get('suppliers');
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getDailySales($year, $month) 
	{
	// updated by Jeni 2014-05-18
		$ownerId = $this->getOwnerId();
	$myQuery = "SELECT DATE_FORMAT( date,  '%e' ) AS date
		 , SUM(if( t2.product_type = 'products', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) productTotal
		, SUM(if( t2.product_type = 'services', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) servicesTotal
		, SUM(if( t2.product_type = 'giftcard', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) giftcardTotal
		, SUM(if( t3.paid_by = 'cash' or t3.paid_by = 'cashCC' or t3.paid_by = 'cashGC', t3.paid, 0)) cashTotal
		, SUM(if( t3.paid_by = 'CC' or t3.paid_by = 'CCGC' or t3.paid_by = 'cashCC', t3.cc_no, 0)) ccTotal
		, SUM(if( t3.paid_by = 'GC' or t3.paid_by = 'CCGC' or t3.paid_by = 'cashGC', t3.paid_giftcard, 0)) gcTotal
		, SUM(COALESCE( t3.paid + t3.paid_giftcard + t3.cc_no, 0 )) total
		, SUM( COALESCE( t1.val_tax, 0 ) ) AS productTax
		FROM ". $this->db->dbprefix('sale_items'). " t1, ". $this->db->dbprefix('products'). " t2, ". $this->db->dbprefix('sales')." t3
		WHERE t1.owner_id = {$ownerId}
        AND t1.product_id = t2.id
		AND t3.id = t1.sale_id
		AND DATE_FORMAT( date,  '%Y-%m' ) =  '{$year}-{$month}'
		GROUP BY DATE_FORMAT( date,  '%e' )";		
		@error_log($myQuery, 3, "C:\data.log");
		
		$q = $this->db->query($myQuery, false);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	public function getMonthlySales($year) 
	{
		// updated by Jeni 2014-05-18
        $ownerId = $this->getOwnerId();
		$myQuery = "SELECT DATE_FORMAT( date,  '%c' ) AS date
						 , SUM(if( t2.product_type = 'products', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) productTotal
						, SUM(if( t2.product_type = 'services', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) servicesTotal
						, SUM(if( t2.product_type = 'giftcard', t3.paid + t3.paid_giftcard + t3.cc_no, 0)) giftcardTotal
						, SUM(if( t3.paid_by = 'cash' or t3.paid_by = 'cashCC' or t3.paid_by = 'cashGC', t3.paid, 0)) cashTotal
						, SUM(if( t3.paid_by = 'CC' or t3.paid_by = 'CCGC' or t3.paid_by = 'cashCC', t3.cc_no, 0)) ccTotal
						, SUM(if( t3.paid_by = 'GC' or t3.paid_by = 'CCGC' or t3.paid_by = 'cashGC', t3.paid_giftcard, 0)) gcTotal
						, SUM(COALESCE( t3.paid + t3.paid_giftcard + t3.cc_no, 0 )) total
						, SUM( COALESCE( t1.val_tax, 0 ) ) AS productTax
						FROM ". $this->db->dbprefix('sale_items'). " t1, ". $this->db->dbprefix('products'). " t2, ". $this->db->dbprefix('sales')." t3
								WHERE t1.owner_id = {$ownerId} 
                                AND t1.product_id = t2.id
								AND t3.id = t1.sale_id
								AND DATE_FORMAT( date,  '%Y' ) =  '{$year}'
								GROUP BY DATE_FORMAT( date,  '%c' )";
					
		$q = $this->db->query($myQuery, false);
		if($q->num_rows() > 0) {
			foreach (($q->result()) as $row) {
				$data[] = $row;
			}
				
			return $data;
		}
	}
	
	
}
