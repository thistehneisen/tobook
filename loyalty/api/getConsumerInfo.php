<?php
	require_once("../../DB_Connection.php");
	require_once("../common/functions.php");

    $result = "success";
    $error = "";
    $msg = "";
    $data = array();

    $customerToken = mysql_escape_string( $_POST['customerToken'] );
    $consumerId = mysql_escape_string( $_POST['consumerId'] );
    $customerId = base64_decode( base64_decode( $customerToken ) );
    
    $sql = "select first_name as firstName, last_name as lastName, email as email, phone as phone
    			 , address1 as address1, city as city, created_time as createdTime, updated_time as updatedTime, current_score as currentScore
    		  from tbl_loyalty_consumer
    		 where loyalty_consumer = $consumerId";
    $dataConsumer = $db->queryArray($sql);
    $dataConsumer = $dataConsumer[0];   
    
    $data['firstName'] = $dataConsumer['firstName'];
    $data['lastName'] = $dataConsumer['lastName'];
    $data['email'] = $dataConsumer['email'];
    $data['phone'] = $dataConsumer['phone'];
    $data['address1'] = $dataConsumer['address1'];
    $data['city'] = $dataConsumer['city'];
    $data['createdTime'] = $dataConsumer['createdTime'];
    $data['updatedTime'] = $dataConsumer['updatedTime'];
    $data['currentScore'] = $dataConsumer['currentScore'];
    
    $sql = "select t1.loyalty_stamp as stampId, t1.stamp_name as stampName, t1.cnt_required as cntRequired, t1.cnt_free as cntFree
    			 , t1.created_time as createdTime, ifnull( t2.cnt_used, 0 ) as cntCurrentUsed, ifnull( t2.cnt_free, 0 ) as cntFreeUse
    		  from tbl_loyalty_stamp t1
    		  left join tbl_loyalty_consumer_stamp t2 on t1.loyalty_stamp = t2.loyalty_stamp and t2.loyalty_consumer = $consumerId
    		 where t1.owner = $customerId";
	$usedStampList = $db->queryArray($sql);
	if( $usedStampList == null )
		$usedStampList = array( );
	
    $sql = "select * 
              from tbl_loyalty_stamp
             where auto_add_yn = 'Y'
               and owner = $customerId";
    $stampList = $db->queryArray($sql);
    
    for ($i = 0; $i < count($stampList); $i++) {
        $stampId = $stampList[$i]['loyalty_stamp'];
        $sql = "select *
                  from tbl_loyalty_consumer_stamp
                 where loyalty_consumer = $consumerId
                   and loyalty_stamp = $stampId";
        $dataConsumerStamp = $db->queryArray($sql);
        if( $dataConsumerStamp == null ){
            $sql = "insert into tbl_loyalty_consumer_stamp( loyalty_consumer, loyalty_stamp, cnt_used, cnt_free, created_time, updated_time )
                    values( $consumerId, $stampId, 1, 0, now(), now() )";
            $db->query($sql);
        }else{
            $dataConsumerStamp = $dataConsumerStamp[0];
            $cntUsed = $dataConsumerStamp['cnt_used'];
        
            $sql = "select * from tbl_loyalty_stamp where loyalty_stamp = $stampId";
            $dataStamp = $db->queryArray($sql);
            $dataStamp = $dataStamp[0];
             
            $cntStampRequired = $dataStamp['cnt_required'];
            $cntStampFree = $dataStamp['cnt_free'];
             
            if( ( $cntUsed + 1 ) % $cntStampRequired != 0 ){
                $cntStampFree = 0;
                $subStr = "cnt_used + 1";
            }else{
                $subStr = "0";
            }
             
            $sql = "update tbl_loyalty_consumer_stamp
                       set cnt_used = $subStr
                         , cnt_free = cnt_free + $cntStampFree
                     where loyalty_consumer = $consumerId
                       and loyalty_stamp = $stampId";
            $db->query($sql);
        }
    }
	
	$data['usedStampList'] = $usedStampList;
    $data['msg'] = $msg;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
