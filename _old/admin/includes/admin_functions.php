<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+

 function getSiteCreatedGraph() {

        $sitePayType = getSitePayType(); 
        $sites       = getSites($sitePayType);
        $graphObj = new graph(1,420);
        $graphObj->setChartParams("",1,1,0,5,0,"Month","Created Site's Count");
        $arrData[0][0] = "Sites";
        $arrData[0][1] = "";

        #Store the chart attributes in a variable
        //$strParam="yAxisName=Users Count;yAxisMaxValue=100;showPlotBorder=1";
        
        for($i=1;$i<=12;$i++){
            $arrCatNames[$i] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[0][$i+1] = $sites[$i]['site_count']?$sites[$i]['site_count']:0;
        }

        $graphObj->addChartData($arrData,$arrCatNames);
        return $graphObj;
    }


    function getSites($sitePayType) {
        
        $query = "SELECT EXTRACT(MONTH FROM sm.ddate) as month,count(sm.nsite_id) as site_count,sm.ddate 
                  FROM ".MYSQL_TABLE_PREFIX."site_mast sm
                  LEFT JOIN ".MYSQL_TABLE_PREFIX."payment p ON sm.nsite_id = p.nsite_id
                  WHERE sm.ndel_status='0' AND sm.is_published='1'";

//        if($sitePayType=='no')
//            $query .= " AND p.vpayment_type = 'Free'";
//        else
//            $query .= " AND p.vpayment_type != 'Free'";
        
        $query .= " GROUP BY month ORDER BY month ASC";
        $result = mysql_query($query);
        if($result){
            while($row = mysql_fetch_array($result)) {
                $siteArray[$row[0]]['site_count']= $row[1];
            }
        }
        return $siteArray;
    }

    function getSitePayType() {

        
        $query  = "SELECT vvalue
                   FROM ".MYSQL_TABLE_PREFIX."lookup WHERE vname='paymentsupport'";
        $result = mysql_query($query);
        $row    = mysql_fetch_assoc($result);
        return $row['vvalue'];
    }


    // Sales Graph
    function getSalesGraph() {

        $sales    = getSales();
        $graphObj = new graph(1,420);
        $graphObj->setChartParams("",1,1,0,5,0,"Month","Sale's Count");
        $arrData[0][0] = "Sales";
        $arrData[0][1] = "";


        for($i=1;$i<=12;$i++){
            $arrCatNames[$i] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[0][$i+1] = $sales[$i]['sales_count']?$sales[$i]['sales_count']:0;
        }
        $graphObj->addChartData($arrData,$arrCatNames);
        return $graphObj;
    }
    
     // Sales Graph
    function getUsersGraph() {

        $users    = getUsers();
        $graphObj = new graph(1,420);
        $graphObj->setChartParams("",1,1,0,5,0,"Month","User's Count");
        $arrData[0][0] = "Users";
        $arrData[0][1] = "";


        for($i=1;$i<=12;$i++){
            $arrCatNames[$i] = date("M", mktime(0, 0, 0, $i, 10));
            $arrData[0][$i+1] = $users[$i]['users_count']?$users[$i]['users_count']:0;
        }
        $graphObj->addChartData($arrData,$arrCatNames);
        return $graphObj;
    }

    function getSales() {

        

         $query = "SELECT EXTRACT(MONTH FROM p.ddate) as month,sum(p.namount) as sales_count,p.ddate
                  FROM ".MYSQL_TABLE_PREFIX."payment p
                  INNER JOIN ".MYSQL_TABLE_PREFIX."site_mast sm ON  sm.nsite_id=p.nsite_id
                  GROUP BY month ORDER BY month ASC";
        $result = mysql_query($query);

        while($row = mysql_fetch_array($result)) {
            $salesArray[$row[0]]['sales_count']= $row[1];
        }
        return $salesArray;
    }
    
    function getUsers() {

        
         $query = "SELECT EXTRACT(MONTH FROM u.duser_join) as month,count(u.nuser_id) as users_count,u.duser_join
                  FROM ".MYSQL_TABLE_PREFIX."user_mast u
                  WHERE u.vdel_status ='0' 
                  GROUP BY month ORDER BY month ASC";
        $result = mysql_query($query);

        while($row = mysql_fetch_array($result)) {
            $usersArray[$row[0]]['users_count']= $row[1];
        }
        return $usersArray;
    }
    
    //Latest Orders
    function getLatestOrders() {
        
        
        $query = "SELECT p.*,s.vsite_name,DATE_FORMAT(p.ddate,'%m/%d/%Y') as orderDate, concat(u.vuser_name,' ',u.vuser_lastname) as userName
                  FROM ".MYSQL_TABLE_PREFIX."payment p
                  INNER JOIN ".MYSQL_TABLE_PREFIX."site_mast s ON s.nsite_id=p.nsite_id
                  INNER JOIN ".MYSQL_TABLE_PREFIX."user_mast u ON u.nuser_id=s.nuser_id
                  WHERE  s.ndel_status='0' AND p.vpayment_type!='Free'
                  ORDER BY p.ddate DESC LIMIT 0,10";
        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) {
            $orderArray[]= $row;
        }
        return $orderArray;
    }
    
    //Latest Unpublished Sites
    function getUnpublishedSites() {
        
        
         $query = "SELECT s.vsite_name,DATE_FORMAT(s.ddate,'%m/%d/%Y') as siteDate, concat(u.vuser_name,' ',u.vuser_lastname) as userName
                  FROM ".MYSQL_TABLE_PREFIX."site_mast s
                  INNER JOIN ".MYSQL_TABLE_PREFIX."user_mast u ON u.nuser_id=s.nuser_id
                  WHERE s.ndel_status='0' AND is_published='0' 
                  ORDER BY s.ddate DESC LIMIT 0,10";
        $result = mysql_query($query);

        while($row = mysql_fetch_assoc($result)) {
            $unPublishedArray[]= $row;
        }
        return $unPublishedArray;
    }

    // Settings

    function updateSettingsValue($fieldName,$fieldValue){
        
        $sql="UPDATE ".MYSQL_TABLE_PREFIX."lookup SET vvalue='".addslashes($fieldValue)."' where vname='".$fieldName."'";
        mysql_query($sql);
    }


    ?>