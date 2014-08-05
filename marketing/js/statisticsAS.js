var chart1;
var chart2;
var chart3;
var chart4;
$(document).ready( function(){
	$('#startDate, #endDate').datepicker({format: 'yyyy-mm-dd', showToday: true});
	
	var startDate = new Date();
	startDate.setDate( startDate.getDate() - 6 );
	var year = startDate.getFullYear();
	var month = startDate.getMonth() + 1;
	if( month < 10 ) month = "0" + month;
	var date = startDate.getDate();
	if( date < 10 ) date = "0" + date;
	var strStartDate = year + "-" + month + "-" + date;
	
	var endDate = new Date();
	var year = endDate.getFullYear();
	var month = endDate.getMonth() + 1;
	if( month < 10 ) month = "0" + month;
	var date = endDate.getDate();
	if( date < 10 ) date = "0" + date;
	var strEndDate = year + "-" + month + "-" + date;	
	
	$('#startDate').val( strStartDate );
	$('#endDate').val( strEndDate );
	
	chart1 = $('#divChart1').highcharts({
        title: {
            text: 'Statistic Info of Revenue',
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: 'Revenue'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
        	legend : false
        },
        series: []
    });
	
	chart2 = $('#divChart2').highcharts({
        title: {
            text: 'Statistic Info of Booking Count',
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: 'Number Of Booking'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
        	legend : false
        },
        series: []
    });

	chart3 = $('#divChart3').highcharts({
		chart: {
            type: 'area'
        },		
        title: {
            text: 'Statistic Info of Hours',
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: ' Hours'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
        	legend : false
        },
        series: []
    });	
	
	chart4 = $('#divChart4').highcharts({	
        title: {
            text: 'Percent of Booking & Working Hours',
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: ' Percent'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
        	legend : false
        },
        series: []
    });		

});
function onChangeUser( ){
	var userId = $("#userList").val();
	var userName = $("#userList option:selected").text();
	$.ajax({
        url: "async-getEmployeeListFromUserId.php",
        dataType : "json",
        type : "POST",
        data : { userId : userId, userName : userName },
        success : function(data){
            if(data.result == "success"){
            	var strHTML = '<option value="">All Employees</option>';
            	strHTML+= '<option value="individual">Individual Employees</option>';
            	var employeeList = data.employeeList;
            	for( var i = 0 ; i < employeeList.length; i ++ ){
            		strHTML+= '<option value="' + employeeList[i].id + '">' + employeeList[i].name + '</option>';
            	}
            	$("#employeeList").html( strHTML );
            }else{
            	
            }
        }
    });
}
function onCalculate( ){
	var userId = $("#userList").val( );
	var userName = $("#userList option:selected").text();
	var employeeId = $("#employeeList").val( );
	var startDate = $("#startDate").val();
	var endDate = $("#endDate").val();
	var viewMode = $("#viewMode").val();

	$.ajax({
        url: "async-getStatisticsBookingInfo.php",
        dataType : "json",
        type : "POST",
        data : { userId : userId, userName : userName, employeeId : employeeId, startDate : startDate, endDate : endDate, viewMode : viewMode },
        success : function(data){
            if(data.result == "success"){
            	if( data.chartList.length > 0 ){
            		var dateList = [];
            		var strHTML = "";
            		for( var i = 0 ; i < data.chartList[0].length; i ++ ){
            			dateList[i] = data.chartList[0][i].date;
            		}
            		chart1 = $('#divChart1').highcharts();
            		chart1.xAxis[0].setCategories( dateList );
                	if( chart1.series != undefined && chart1.series != null ){
                    	for (var k = chart1.series.length; k > 0 ; k--) {
                    		chart1.series[ k - 1 ].remove( );
                        }
                	}
            		chart3 = $('#divChart3').highcharts();
            		chart3.xAxis[0].setCategories( dateList );
                	if( chart3.series != undefined && chart3.series != null ){
                    	for (var i = chart3.series.length; i > 0 ; i--) {
                    		chart3.series[ i - 1 ].remove( );
                        }
                	}
                	
                	chart2 = $('#divChart2').highcharts();
            		chart2.xAxis[0].setCategories( dateList );
                	if( chart2.series != undefined && chart2.series != null ){
                    	for (var i = chart2.series.length; i > 0 ; i--) {
                    		chart2.series[ i - 1 ].remove( );
                        }
                	}
                	
            		chart3 = $('#divChart3').highcharts();
            		chart3.xAxis[0].setCategories( dateList );
                	if( chart3.series != undefined && chart3.series != null ){
                    	for (var i = chart3.series.length; i > 0 ; i--) {
                    		chart3.series[ i - 1 ].remove( );
                        }
                	}                	
                	
            		chart4 = $('#divChart4').highcharts();
            		chart4.xAxis[0].setCategories( dateList );
                	if( chart4.series != undefined && chart4.series != null ){
                    	for (var i = chart4.series.length; i > 0 ; i--) {
                    		chart4.series[ i - 1 ].remove( );
                        }
                	} 
                	
            		for( var i = 0 ; i < data.chartList.length; i ++ ){
            			var revenueList = [];
                		var cntBookingList = [];
                		var workingHoursList = [];
                		var bookingHoursList = [];
                		var rateHoursList = [];
                		var employeeName = data.chartList[i][0].employeeName;;
                		
                		for( var j = 0 ; j < data.chartList[i].length; j ++ ){
                			revenueList[j] = Number(data.chartList[i][j].revenue);
                			cntBookingList[j] = Number(data.chartList[i][j].cntBooking);
                			workingHoursList[j] = Number(data.chartList[i][j].workingHours) / 60;
                			bookingHoursList[j] = Number(Number(Number(data.chartList[i][j].bookingHours) / 60).toFixed(2));
                			rateHoursList[j] = Number(Number(Number(data.chartList[i][j].bookingHours) / Number(data.chartList[i][j].workingHours) * 100).toFixed(2));
                		}

                    	chart1.addSeries({ id : i, name: employeeName, data: revenueList });
                    	chart1.redraw();
                    	
                    	chart2.addSeries({ id : i, name: employeeName, data: cntBookingList });
                    	chart2.redraw();
                    	
                    	chart3.addSeries({ id : i, name: employeeName, data: bookingHoursList });
                    	chart3.redraw();
                    	
                    	chart4.addSeries({ id : i, name: employeeName, data: rateHoursList });
                    	chart4.redraw();
                    	for( var k = 0 ; k < data.chartList[i].length; k ++ ){
                			strHTML += "<tr>" 
                				+ "<td style='text-align:center;'>" + String( i * dateList.length + k + 1 )+ "</td>"
                				+ "<td style='text-align:center;'>" + dateList[k] + "</td>"
                				+ "<td style='text-align:center;'>" + employeeName + "</td>"
                				+ "<td style='text-align:right;'>" + String(revenueList[k]) + " Euro" + "</td>"
                				+ "<td style='text-align:center;'>" + String(cntBookingList[k]) + "</td>"
                				+ "<td style='text-align:center;'>" + String(workingHoursList[k]) + "</td>"
                				+ "<td style='text-align:center;'>" + String(bookingHoursList[k]) + "</td>"
                				+ "<td style='text-align:center;'>" + Number((Number(bookingHoursList[k]) / Number(workingHoursList[k])) * 100).toFixed(2) + "%"+ "</td>"
                				+ "</tr>";
                		}                     	
            		}
                	$("#tblStatistics").find("tbody").html( strHTML );
            	}
            }else{
            	
            }
        }
    });	
}