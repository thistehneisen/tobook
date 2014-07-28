<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    $userId = mysql_escape_string($_POST['userId']);
    $userName = mysql_escape_string($_POST['userName']);
    $employeeId = mysql_escape_string($_POST['employeeId']);
    $startDate = mysql_escape_string($_POST['startDate']);
    $endDate = mysql_escape_string($_POST['endDate']);
    $viewMode = mysql_escape_string($_POST['viewMode']);
    $table_prefix = str_replace("-", "", $userName)."_hey_appscheduler";
    
    $wtSql = "
			select foreign_id as employeeId
			     , round(( time_to_sec(monday_to) - time_to_sec(monday_from) ) / 60) as mon
			     , round(( time_to_sec(tuesday_to) - time_to_sec(tuesday_from) ) / 60) as tue
			     , round(( time_to_sec(wednesday_to) - time_to_sec(wednesday_from) ) / 60) as wed
			     , round(( time_to_sec(thursday_to) - time_to_sec(thursday_from) ) / 60) as thu
			     , round(( time_to_sec(friday_to) - time_to_sec(friday_from) ) / 60) as fri
			     , round(( time_to_sec(saturday_to) - time_to_sec(saturday_from) ) / 60) as sat
			     , round(( time_to_sec(sunday_to) - time_to_sec(sunday_from) ) / 60) as sun
			  from ".$table_prefix."_working_times
			 where type = 'employee'";
    
    $wtSql = "
		select employeeId, 1 as dayNo, sun as minute
		  from ( $wtSql ) t1
		union all
		select employeeId, 2 as dayNo, mon as minute
		  from ( $wtSql ) t2
		union all
		select employeeId, 3 as dayNo, tue as minute
		  from ( $wtSql ) t3
		union all
		select employeeId, 4 as dayNo, wed as minute
		  from ( $wtSql ) t4		  		  		
		union all
		select employeeId, 5 as dayNo, thu as minute
		  from ( $wtSql ) t5
		union all
		select employeeId, 6 as dayNo, fri as minute
		  from ( $wtSql ) t6
		union all
		select employeeId, 7 as dayNo, sat as minute
		  from ( $wtSql ) t7";
    
    $employeeList = array();
    if( $employeeId == "individual" ) {
    	$table_prefix = str_replace("-", "", $userName)."_hey_appscheduler";
    	
	    $sql = "select t1.id, t2.content as name
	    		  from ".$table_prefix."_employees t1, ".$table_prefix."_multi_lang t2
	    		 where t2.model = 'pjEmployee'
	    		   and t2.locale = 1
	    		   and t2.foreign_id = t1.id";
	    $employeeList = $db->queryArray( $sql );
    }
    
    $arrWtSql = array();
    if( $employeeId == "" ){
    	$arrWtSql[] = "select dayNo, minute, 'All Employees' as employeeName
    			    	 from ( $wtSql ) t1
    			   	 	group by dayNo";
    }else if( $employeeId == "individual" ){
    	for( $i = 0; $i < count( $employeeList ); $i ++ ){
    		$arrWtSql[] = "select dayNo, minute, '".$employeeList[$i]["name"]."' as employeeName
    						 from ( $wtSql ) t1
    					 	where t1.employeeId = '".$employeeList[$i]["id"]."'";    		    		
    	}    	
    }else{
    	$arrWtSql[] = "select dayNo, minute, t2.content as employeeName
    				from ( $wtSql ) t1, ".$table_prefix."_multi_lang t2
    			   where t1.employeeId = '$employeeId'
    				 and t2.model = 'pjEmployee'
    		   		 and t2.locale = 1
    				 and t2.foreign_id = t1.employeeId";
    }
    
    $sql = "select t1.employee_id as employeeId, t1.date, t1.total, t1.price
    		  from ".$table_prefix."_bookings_services t1
    		 where t1.date >= '$startDate' and t1.date <= '$endDate'";
	$arrSql = array( );
    if( $employeeId == "" ){
    	$arrSql[] = "select t1.date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
    			  from ( $sql ) t1
    			 group by t1.date";
    }else if( $employeeId == "individual" ){
    	for( $i = 0; $i < count( $employeeList ); $i ++ ){
	    	$arrSql[] = "select t1.date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    			  	   from ( $sql ) t1
	    			 	  where t1.employeeId = '".$employeeList[$i]["id"]."'
	    			 	  group by t1.date";
    	}    	
    }else{
    	$arrSql[] = "select t1.date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
    			  from ( $sql ) t1
    			 where t1.employeeId = '$employeeId'
    			 group by t1.date";
    }
    $dateSql = "select datediff( '$endDate', '$startDate' ) as days";
    $days = $db->queryArray( $dateSql );
    $days = $days[0];
    $days = $days['days'];
    
    $dateSql = "";
    $days++;
    for( $i = 0 ; $i < $days; $i ++ ){
    	$dateSql.="select date(date_add('$startDate',interval $i day)) as date";
    	if( $i != $days - 1  ){
    		$dateSql.=" union all ";
    	}
    }
    $employeeChartList = array( );
    for( $i = 0; $i < count( $arrSql ); $i ++ ){   
	    $arrSql[$i] = "select t2.date, ifnull( t1.bookingHours, 0) as bookingHours, ifnull( t1.revenue, 0) as revenue, ifnull( t1.cntBooking, 0) as cntBooking
	    		  from ( ".$arrSql[$i]." ) t1
	    		 right join ( $dateSql ) t2 on t1.date = t2.date";
	    
	    $arrSql[$i] = "select t1.*, t2.minute as workingHours, t2.employeeName
	    		  from ( ".$arrSql[$i]." ) t1, ( ".$arrWtSql[$i]." ) t2 
	   			 where dayofweek(t1.date) = t2.dayNo
	    		 order by t1.date asc";
	    $employeeChartList[] = $db->queryArray( $arrSql[$i] );
    }
           
    $data['chartList'] = $employeeChartList;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
