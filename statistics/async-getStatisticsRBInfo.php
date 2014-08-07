<?php
	require_once("../DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    $userId = mysql_escape_string($_POST['userId']);
    $userName = mysql_escape_string($_POST['userName']);
    $startDate = mysql_escape_string($_POST['startDate']);
    $endDate = mysql_escape_string($_POST['endDate']);
    $viewMode = mysql_escape_string($_POST['viewMode']);
    
    if( $userId == "" ){
    	if( $viewMode == "daily" ) {
    		$strSelect = "t1.dt as dt";
    		$strGroupBy = "t1.dt";    		
    	}else if( $viewMode == "weekly" ){
    		$strSelect = "str_to_date( concat(year(t1.dt), weekofyear(t1.dt) - 1, ' Monday'), '%X%V %W' ) as dt";
    		$strGroupBy = "weekofyear( t1.dt )";
    	}else{
    		$strSelect = "date_format( t1.dt, '%Y-%m') as dt";
    		$strGroupBy = "date_format( t1.dt, '%Y-%m')";
    	}

        $sql = "
			select $strSelect, t1.owner_id, sum(t1.people) as cntPeople, count(*) as cntBooking, sum(t1.people * t2.s_price) as revenue
			  from (
				select date(t1.dt) as dt, t1.people, t1.owner_id, t2.id as serviceId
				  from
				    (
				    select dt, dt_to, people, owner_id
				      from rb_bookings
				     where date(dt) between '$startDate' and '$endDate'
				       and status = 'confirmed'
				    ) t1,
				    (
				    select id, start_time, end_time, owner_id
				      from rb_service
				    ) t2
				 where t1.owner_id = t2.owner_id
				   and time(t1.dt) between time(t2.start_time) and time(t2.end_time)
				) t1, rb_service t2
			 where t1.serviceId = t2.id
			 group by $strGroupBy, t1.owner_id
        	";
        
        $userSql = "select nuser_id, vuser_login
                      from tbl_user_mast
        			 where vdel_status = 0";
        $userList = $db->queryArray( $userSql );
        
        $dateSql = "select datediff( '$endDate', '$startDate' ) as days";
        $days = $db->queryArray( $dateSql );
        $days = $days[0];
        $days = $days['days'];
        
        $dateSql = "";
        $days++;
        for( $i = 0 ; $i < $days; $i ++ ){
        	for( $j = 0 ; $j < count( $userList ); $j ++ ){
        		$userId = $userList[$j]['nuser_id'];
        		$userName = $userList[$j]['vuser_login'];
        		
	        	$dateSql.="select date_add('$startDate',interval $i day) as date, $userId as userId, '$userName' as userName";
	        	if( !($i == $days - 1 && $j == count( $userList ) - 1) ){
	        		$dateSql.=" union all ";
	        	}
        	}
        }

        if( $viewMode == "daily" ) {

        }else if( $viewMode == "weekly" ){        	
        	$dateSql = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date, userId, userName
        			      from ( $dateSql ) t1
        				 group by weekofyear( t1.date ), userId";
        }else{
        	$dateSql = "select date_format( t1.date, '%Y-%m') as date, userId, userName
        				  from ( $dateSql ) t1
        				 group by date_format( t1.date, '%Y-%m'), userId";        	
        }        
		$days = $db->queryArray( $dateSql );
		$days = count( $days ) / count( $userList );
        
        $sql = "select t2.date, t2.userId as userId, t2.userName as userName, ifnull(t1.cntPeople, 0) as cntPeople, ifnull( t1.cntBooking, 0) as cntBooking, ifnull( t1.revenue, 0 ) as revenue 
        		  from ( $sql ) t1
        		 right join ( $dateSql ) t2 on t1.dt = t2.date and t1.owner_id = t2.userId
        		 order by t2.userId, t2.date asc";
        $dataList = $db->queryArray( $sql );
        $chartList = array( );
        for( $i = 0 ; $i < count( $userList ); $i ++ ){
        	$tempList = array( );
        	for( $j = 0 ; $j < $days; $j ++ ){
        		$tempList[] = $dataList[ $i * $days + $j ];
        	}
        	$chartList[] = $tempList;
        }
    }else{
    	if( $viewMode == "daily" ) {
    		$strSelect = "t1.dt as dt";
    		$strGroupBy = "t1.dt";
    	}else if( $viewMode == "weekly" ){
    		$strSelect = "str_to_date( concat(year(t1.dt), weekofyear(t1.dt) - 1, ' Monday'), '%X%V %W' ) as dt";
    		$strGroupBy = "weekofyear( t1.dt )";
    	}else{
    		$strSelect = "date_format( t1.dt, '%Y-%m') as dt";
    		$strGroupBy = "date_format( t1.dt, '%Y-%m')";
    	}
    	
    	$sql = "
    		select $strSelect, sum(t1.people) as cntPeople, count(*) as cntBooking, sum(t1.people * t2.s_price) as revenue, t1.serviceId as serviceId
    		  from (
    			   select date(t1.dt) as dt, t1.people, t2.id as serviceId
    				 from
    					(
    					select dt, dt_to, people, owner_id
    					  from rb_bookings
    					 where date(dt) between '$startDate' and '$endDate'
    					   and status = 'confirmed'
    					   and owner_id = $userId
    					) t1,
				    	(
				    	select id, start_time, end_time, owner_id
				    	  from rb_service
				    	 where owner_id = $userId 
				    	) t2
				    where time(t1.dt) between time(t2.start_time) and time(t2.end_time)
				    ) t1, rb_service t2
				where t1.serviceId = t2.id
				group by $strGroupBy, t1.serviceId
    	";
    	
    	$serviceSql = "select id, s_name
                         from rb_service
        			    where owner_id = $userId";
        $serviceList = $db->queryArray( $serviceSql );
    	
    	$dateSql = "select datediff( '$endDate', '$startDate' ) as days";
    	$days = $db->queryArray( $dateSql );
    	$days = $days[0];
    	$days = $days['days'];
    	
    	$dateSql = "";
    	$days++;
    	for( $i = 0 ; $i < $days; $i ++ ){
    		for( $j = 0 ; $j < count( $serviceList ); $j ++ ){
    			$serviceId = $serviceList[$j]['id'];
    			$serviceName = $serviceList[$j]['s_name'];
    	
    			$dateSql.="select date_add('$startDate',interval $i day) as date, $serviceId as serviceId, '$serviceName' as serviceName";
		    	if( !($i == $days - 1 && $j == count( $serviceList ) - 1) ){
		    		$dateSql.=" union all ";
		    	}
    		}
    	}
    	
    	if( $viewMode == "daily" ) {
    	
    	}else if( $viewMode == "weekly" ){
	    	$dateSql = "select str_to_date( concat(year(t1.date), weekofyear(t1.date) - 1, ' Monday'), '%X%V %W' ) as date, serviceId, serviceName
	    				  from ( $dateSql ) t1
	    				 group by weekofyear( t1.date ), serviceId";
    	}else{
    		$dateSql = "select date_format( t1.date, '%Y-%m') as date, serviceId, serviceName
    					  from ( $dateSql ) t1
    					 group by date_format( t1.date, '%Y-%m'), serviceId";
    	}

    	$days = $db->queryArray( $dateSql );
    	$days = count( $days ) / count( $serviceList );
    	
    	$sql = "select t2.date, t2.serviceId as userId, t2.serviceName as userName, ifnull(t1.cntPeople, 0) as cntPeople, ifnull( t1.cntBooking, 0) as cntBooking, ifnull( t1.revenue, 0 ) as revenue
    			  from ( $sql ) t1
    			 right join ( $dateSql ) t2 on t1.dt = t2.date and t1.serviceId = t2.serviceId
    			 order by t2.serviceId, t2.date asc";
    	$dataList = $db->queryArray( $sql );

    	$chartList = array( );
    	for( $i = 0 ; $i < count( $serviceList ); $i ++ ){
    		$tempList = array( );
    		for( $j = 0 ; $j < $days; $j ++ ){
    			$tempList[] = $dataList[ $i * $days + $j ];
    		}
    		$chartList[] = $tempList;
    	}
    }
           
    $data['chartList'] = $chartList;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
