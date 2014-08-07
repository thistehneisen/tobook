<?php
	require_once("../DB_Connection.php");
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
    

    if( $userId == "" ){
    	$wtSql = "
    			select foreign_id as employeeId
    				 , owner_id as ownerId
    				 , round(( time_to_sec(monday_to) - time_to_sec(monday_from) ) / 60) as mon
    				 , round(( time_to_sec(tuesday_to) - time_to_sec(tuesday_from) ) / 60) as tue
    				 , round(( time_to_sec(wednesday_to) - time_to_sec(wednesday_from) ) / 60) as wed
    				 , round(( time_to_sec(thursday_to) - time_to_sec(thursday_from) ) / 60) as thu
    				 , round(( time_to_sec(friday_to) - time_to_sec(friday_from) ) / 60) as fri
    				 , round(( time_to_sec(saturday_to) - time_to_sec(saturday_from) ) / 60) as sat
    				 , round(( time_to_sec(sunday_to) - time_to_sec(sunday_from) ) / 60) as sun
    			  from as_working_times
    			 where type = 'employee'";
    	
    	$wtSql = "select ownerId, sum(mon) as mon, sum(tue) as tue, sum(wed) as wed, sum(thu) as thu, sum(fri) as fri, sum(sat) as sat, sum(sun) as sun
    			    from ( $wtSql ) t1
    			   group by ownerId";

    	$wtSql = "
		    	select ownerId, 1 as dayNo, sun as minute from ( $wtSql ) t1
		    	union all
		    	select ownerId, 2 as dayNo, mon as minute from ( $wtSql ) t2
		    	union all
		    	select ownerId, 3 as dayNo, tue as minute from ( $wtSql ) t3
		    	union all
		    	select ownerId, 4 as dayNo, wed as minute from ( $wtSql ) t4
		    	union all
		    	select ownerId, 5 as dayNo, thu as minute from ( $wtSql ) t5
		    	union all
		    	select ownerId, 6 as dayNo, fri as minute from ( $wtSql ) t6
		    	union all
		    	select ownerId, 7 as dayNo, sat as minute from ( $wtSql ) t7";    	
    	
    	$sql = "select nuser_id as id, vuser_login as name 
    			  from tbl_user_mast";
    	$userList = $db->queryArray( $sql );

    	$sql = "select t1.owner_id as ownerId, t1.date, t1.total, t1.price
    			  from as_bookings_services t1
    			 where t1.date >= '$startDate' and t1.date <= '$endDate'";
    	
    	$arrSql = array( );
    	
    	for( $i = 0; $i < count( $userList ); $i ++ ){
    		if( $viewMode == "daily" ){
    			$arrSql[] = "select t1.date as date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
    						   from ( $sql ) t1
    						  where t1.ownerId = '".$userList[$i]["id"]."'
    						  group by t1.date";
	    	}else if( $viewMode == "weekly" ){
    		    $arrSql[] = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
    		    				  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
    		    			   from ( $sql ) t1
    		    			  where t1.ownerId = '".$userList[$i]["id"]."'
    		    			  group by str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' )";
	    	}else if( $viewMode == "monthly" ){
    			$arrSql[] = "select date_format(t1.date, '%Y-%m') as date
    							  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
    						   from ( $sql ) t1
    						  where t1.ownerId = '".$userList[$i]["id"]."'
    						  group by date_format(t1.date, '%Y-%m')";
    		}
    	}    

    	$dateSql = "select datediff( '$endDate', '$startDate' ) as days";
    	$days = $db->queryArray( $dateSql );
    	$days = $days[0];
    	$days = $days['days'];
    	 
    	$dateSql = "";
    	$days++;
    	for( $i = 0 ; $i < $days; $i ++ ){
    		$dateSql.="select date_add('$startDate',interval $i day) as date";
    		if( $i != $days - 1  ){
    			$dateSql.=" union all ";
    		}
    	}
    	$dateSql = "select t1.date, t2.ownerId, t2.minute
    				  from ( $dateSql ) t1, ( $wtSql ) t2
    				 where dayofweek(t1.date) = t2.dayNo";
    	
    	if( $viewMode == "daily" ){
    	
    	}else if( $viewMode == "weekly" ){
	    	$dateSql = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
	    	    			 , t1.ownerId as ownerId, sum( t1.minute ) as minute
	    	    		  from ( $dateSql ) t1
	    	    		 group by weekofyear( t1.date ), ownerId";
	    	    	 
	    }else if( $viewMode == "monthly" ){
	    	$dateSql = "select date_format( t1.date, '%Y-%m') as date, t1.ownerId as ownerId, sum( t1.minute ) as minute
	    				  from ( $dateSql ) t1
	    				 group by date_format( t1.date, '%Y-%m'), ownerId";
    	}
    	
    	$userChartList = array( );
    	for( $i = 0; $i < count( $arrSql ); $i ++ ){
    		$dateSql1 = "select date, minute as minute, ownerId from ( $dateSql ) t1 where ownerId = ".$userList[$i]['id'];
    		
    		$arrSql[$i] = "select t2.ownerId, t2.date, ifnull( t1.bookingHours, 0) as bookingHours, ifnull( t2.minute, 0) as workingHours, ifnull( t1.revenue, 0) as revenue, ifnull( t1.cntBooking, 0) as cntBooking
	    		  		 	 from ( ".$arrSql[$i]." ) t1
    		    		  	right join ( $dateSql1 ) t2 on t1.date = t2.date";

    		$arrSql[$i] = "select t1.*, t2.vuser_login as employeeName
		    		  		 from ( ".$arrSql[$i]." ) t1
	    			    	 left join tbl_user_mast t2
	    			    	   on t1.ownerId = t2.nuser_id
    		    			order by t1.date asc";
    		$employeeChartList[] = $db->queryArray( $arrSql[$i] );
    		
    		
    	}
    }else{
	    $wtSql = "
				select foreign_id as employeeId
				     , round(( time_to_sec(monday_to) - time_to_sec(monday_from) ) / 60) as mon
				     , round(( time_to_sec(tuesday_to) - time_to_sec(tuesday_from) ) / 60) as tue
				     , round(( time_to_sec(wednesday_to) - time_to_sec(wednesday_from) ) / 60) as wed
				     , round(( time_to_sec(thursday_to) - time_to_sec(thursday_from) ) / 60) as thu
				     , round(( time_to_sec(friday_to) - time_to_sec(friday_from) ) / 60) as fri
				     , round(( time_to_sec(saturday_to) - time_to_sec(saturday_from) ) / 60) as sat
				     , round(( time_to_sec(sunday_to) - time_to_sec(sunday_from) ) / 60) as sun
				  from as_working_times
				 where type = 'employee'
	    		   and owner_id = $userId";
	    
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
	    	
		    $sql = "select t1.id, t2.content as name
		    		  from as_employees t1, as_multi_lang t2
		    		 where t2.model = 'pjEmployee'
		    		   and t2.locale = 1
		    		   and t2.foreign_id = t1.id
		    		   and t1.owner_id = $userId
		    		   and t2.owner_id = $userId";
		    $employeeList = $db->queryArray( $sql );
	    }
	    
	    $sql = "select t1.employee_id as employeeId, t1.date, t1.total, t1.price
	    		  from as_bookings_services t1
	    		 where t1.date >= '$startDate' and t1.date <= '$endDate'
	    		   and t1.owner_id = $userId";
		$arrSql = array( );
	    if( $employeeId == "" ){
	    	if( $viewMode == "daily" ){
	    		$arrSql[] = "select t1.date as date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    					   from ( $sql ) t1
	    					  group by t1.date";
	    		
	    	}else if( $viewMode == "weekly" ){
	    		$arrSql[] = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
	    						  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    					   from ( $sql ) t1
	    					  group by str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' )";    		
	    	}else if( $viewMode == "monthly" ){
	    		$arrSql[] = "select date_format(t1.date, '%Y-%m') as date
	    						  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    					   from ( $sql ) t1
	    					  group by date_format(t1.date, '%Y-%m')";    		
	    	}
	    }else if( $employeeId == "individual" ){
	    	for( $i = 0; $i < count( $employeeList ); $i ++ ){
		    	if( $viewMode == "daily" ){
		    		$arrSql[] = "select t1.date as date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
		    					   from ( $sql ) t1
		    					  where t1.employeeId = '".$employeeList[$i]["id"]."'
		    					  group by t1.date";
		    	}else if( $viewMode == "weekly" ){
		    		$arrSql[] = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
		    						  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
		    					   from ( $sql ) t1
		    					  where t1.employeeId = '".$employeeList[$i]["id"]."'
		    					  group by str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' )";
		    	}else if( $viewMode == "monthly" ){
		    		$arrSql[] = "select date_format(t1.date, '%Y-%m') as date
		    						  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
		    					   from ( $sql ) t1
		    					  where t1.employeeId = '".$employeeList[$i]["id"]."'
		    					  group by date_format(t1.date, '%Y-%m')";
		    	}
	    	}
	    }else{    	
	    	if( $viewMode == "daily" ){
	    		$arrSql[] = "select t1.date as date, ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    					   from ( $sql ) t1
	    					  where t1.employeeId = '$employeeId'
	    					  group by t1.date";
		    }else if( $viewMode == "weekly" ){
				$arrSql[] = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
	    		    			  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    		    		   from ( $sql ) t1
	    		    		  where t1.employeeId = '$employeeId'
	    		    		  group by str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' )";
		    }else if( $viewMode == "monthly" ){
	    		$arrSql[] = "select date_format(t1.date, '%Y-%m') as date
	    						  , ifnull(sum(t1.total), 0) as bookingHours, ifnull(sum(t1.price), 0) as revenue, count(*) cntBooking
	    					   from ( $sql ) t1
	    					  where t1.employeeId = '$employeeId'
	    					  group by date_format(t1.date, '%Y-%m')";
	    	}
	    }
	    
	    $dateSql = "select datediff( '$endDate', '$startDate' ) as days";
	    $days = $db->queryArray( $dateSql );
	    $days = $days[0];
	    $days = $days['days'];
	     
	    $dateSql = "";
	    $days++;
	    for( $i = 0 ; $i < $days; $i ++ ){
	    	$dateSql.="select date_add('$startDate',interval $i day) as date";
	    	if( $i != $days - 1  ){
	    		$dateSql.=" union all ";
	    	}
	    }
	    $dateSql = "select t1.date, t2.employeeId, t2.minute
	    			  from ( $dateSql ) t1, ( $wtSql ) t2
	    			 where dayofweek(t1.date) = t2.dayNo";    
	
	    if( $viewMode == "daily" ){
	
	    }else if( $viewMode == "weekly" ){
	    	$dateSql = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date
	    					 , t1.employeeId as employeeId, sum( t1.minute ) as minute
	    			      from ( $dateSql ) t1
	    				 group by weekofyear( t1.date ), employeeId";
	    	
	    }else if( $viewMode == "monthly" ){
	    	$dateSql = "select date_format( t1.date, '%Y-%m') as date
	    					 , t1.employeeId as employeeId, sum( t1.minute ) as minute
	    				  from ( $dateSql ) t1
	    				 group by date_format( t1.date, '%Y-%m'), employeeId";
	    }
	
	    $employeeChartList = array( );
	    for( $i = 0; $i < count( $arrSql ); $i ++ ){
	    	if( $employeeId == "" ){
	    		$dateSql1 = "select date, sum(minute) as minute from ( $dateSql ) t1 group by date";
	    	}else if( $employeeId != "individual" ){
	    		$dateSql1 = "select date, minute as minute from ( $dateSql ) t1 where employeeId = $employeeId";
	    	}else{
	    		$dateSql1 = "select date, minute as minute from ( $dateSql ) t1 where employeeId = ".$employeeList[$i]['id'];
	    	}
	    	
		    $arrSql[$i] = "select t2.date, ifnull( t1.bookingHours, 0) as bookingHours, ifnull( t2.minute, 0) as workingHours, ifnull( t1.revenue, 0) as revenue, ifnull( t1.cntBooking, 0) as cntBooking
		    		  		 from ( ".$arrSql[$i]." ) t1
		    		 		right join ( $dateSql1 ) t2 on t1.date = t2.date";
		    	
		    if( $employeeId == "" ){
			    $arrSql[$i] = "select t1.*, 'All Employees' as employeeName
			    		  		 from ( ".$arrSql[$i]." ) t1
			    		 		order by t1.date asc";
		    }else if( $employeeId != "individual" ){
		    	$arrSql[$i] = "select t1.*, t2.content as employeeName
			    		  		 from ( ".$arrSql[$i]." ) t1
		    			    	 left join as_multi_lang t2
		    			    	   on t2.model = 'pjEmployee' and t2.locale = 1 and t2.foreign_id = $employeeId and t2.owner_id = $userId
		    			    	order by t1.date asc";
		    }else{
		    	$arrSql[$i] = "select t1.*, t2.content as employeeName
			    		  		 from ( ".$arrSql[$i]." ) t1
		    			    	 left join as_multi_lang t2
		    			    	   on t2.model = 'pjEmployee' and t2.locale = 1 and t2.foreign_id = ".$employeeList[$i]['id']." and t2.owner_id = $userId
		    		    		order by t1.date asc";
		    }
		    $employeeChartList[] = $db->queryArray( $arrSql[$i] );	    	    
	    }
    }
           
    $data['chartList'] = $employeeChartList;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
