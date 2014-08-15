<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script language='javascript' src='JSClass/FusionCharts.js'></script>
<title>Untitled Document</title>
</head>
<?php

  # Include FusionCharts PHP Class
  if(include('FusionCharts/Class/FusionCharts_Gen.php'))
  	//echo "class included";


?>
  <body>

<?php
   # Include FusionCharts PHP Class
   //include('../Class/FusionCharts_Gen.php');

   # Create Column 3D + Line Dual Y-Axis Combination Chart
   $FD = new FusionCharts("MSLine","350","300");

   # Set the relative path of the swf file
   $FD->setSWFPath("FusionCharts/FusionCharts/");

   # Store chart attributes in a varia	ble
   $strParam="caption=Daily visit statistics; subcaption=; xAxisName=Month; yAxisMinValue=15000; yAxisName=Count; numberPrefix=; showNames=1; showValues=0; rotateNames=0; showColumnShadow=1; animation=1; showAlternateHGridColor=1; AlternateHGridColor=ff5904; divLineColor=ff5904; divLineAlpha=20; alternateHGridAlpha=5; canvasBorderColor=666666; baseFontColor=666666;";

   # Set chart attributes
   $FD->setChartParams($strParam);

   # Add category names
   $FD->addCategory("Day 1");
     $FD->addCategory("Day 2");
	   $FD->addCategory("Day 3");
	    $FD->addCategory("Day 4");
		 $FD->addCategory("Day 5");
		  $FD->addCategory("Day 6");
		   $FD->addCategory("Day 7");
   # Add a new dataset with dataset parameters
   $FD->addDataset("Clicks","parentYaxis=S");
$FD->addChartData("64");
$FD->addChartData("70");
$FD->addChartData("52");
$FD->addChartData("81");
$FD->addChartData("70");
$FD->addChartData("52");
$FD->addChartData("81");

 $FD->addDataset("Views","parentYaxis=S");
$FD->addChartData("23");
$FD->addChartData("34");
$FD->addChartData("45");
$FD->addChartData("45");
$FD->addChartData("34");
$FD->addChartData("45");
$FD->addChartData("45");

$FD->renderChart();
?>
<?php
   # Include FusionCharts PHP Class
   //include('../Class/FusionCharts_Gen.php');

   # Create Column 3D + Line Dual Y-Axis Combination Chart
   $FD = new FusionCharts("MSLine","350","300");

   # Set the relative path of the swf file
   $FD->setSWFPath("FusionCharts/");

   # Store chart attributes in a varia	ble
   $strParam="caption=Weekly visit statistics; subcaption=; xAxisName=Month; yAxisMinValue=15000; yAxisName=Count; numberPrefix=; showNames=1; showValues=0; rotateNames=0; showColumnShadow=1; animation=1; showAlternateHGridColor=1; AlternateHGridColor=cccccc; divLineColor=ff5904; divLineAlpha=20; alternateHGridAlpha=5; canvasBorderColor=666666; baseFontColor=666666;";

   # Set chart attributes
   $FD->setChartParams($strParam);

   # Add category names
   $FD->addCategory("Week 1");
     $FD->addCategory("Week 2");
	   $FD->addCategory("Week 3");
	    $FD->addCategory("Week 4");
		
   # Add a new dataset with dataset parameters
   $FD->addDataset("Clicks","parentYaxis=S");
$FD->addChartData("64");
$FD->addChartData("12");
$FD->addChartData("52");
$FD->addChartData("45");


 $FD->addDataset("Views","parentYaxis=S");
$FD->addChartData("23");
$FD->addChartData("34");
$FD->addChartData("45");
$FD->addChartData("45");

$FD->renderChart();
?>
<?php
   # Include FusionCharts PHP Class
   //include('../Class/FusionCharts_Gen.php');

   # Create Column 3D + Line Dual Y-Axis Combination Chart
   $FD = new FusionCharts("MSLine","350","300");

   # Set the relative path of the swf file
   $FD->setSWFPath("FusionCharts/");

   # Store chart attributes in a varia	ble
   $strParam="caption=Monthly visit statistics; subcaption=; xAxisName=Month; yAxisMinValue=15000; yAxisName=Count; numberPrefix=; showNames=1; showValues=0; rotateNames=0; showColumnShadow=1; animation=1; showAlternateHGridColor=1; AlternateHGridColor=ff5904; divLineColor=777777; divLineAlpha=20; alternateHGridAlpha=5; canvasBorderColor=666666; baseFontColor=666666;";

   # Set chart attributes
   $FD->setChartParams($strParam);

   # Add category names
   $FD->addCategory("Month 1");
     $FD->addCategory("Month 2");
	   $FD->addCategory("Month 3");
	    $FD->addCategory("Month 4");
   # Add a new dataset with dataset parameters
   $FD->addDataset("Clicks","parentYaxis=S");
$FD->addChartData("64");
$FD->addChartData("70");
$FD->addChartData("52");
$FD->addChartData("81");
$FD->addChartData("52");
$FD->addChartData("81");

 $FD->addDataset("Views","parentYaxis=S;color=0099FF");
$FD->addChartData("23");
$FD->addChartData("65");
$FD->addChartData("34");
$FD->addChartData("15");
$FD->addChartData("34");
$FD->addChartData("15");

$FD->renderChart();
?>
  </body>
</html>
