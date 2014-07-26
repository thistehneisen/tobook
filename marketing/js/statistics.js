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

	if( userId == "" ){ alert("Please Select User."); return; }
	$.ajax({
        url: "async-getStatisticsBookingInfo.php",
        dataType : "json",
        type : "POST",
        data : { userId : userId, userName : userName, employeeId : employeeId, startDate : startDate, endDate : endDate, viewMode : viewMode },
        success : function(data){
            if(data.result == "success"){
            	console.log( data.chartList );
            	if( data.chartList.length > 0 ){            		
            		var dateList = [];
            		var revenueList = [];
            		var cntBookingList = [];
            		var workingHoursList = [];
            		var bookingHoursList = [];
            		for( var i = 0 ; i < data.chartList.length; i ++ ){
            			dateList[i] = data.chartList[i].date;
            			revenueList[i] = Number(data.chartList[i].revenue);
            			cntBookingList[i] = Number(data.chartList[i].cntBooking);
            			workingHoursList[i] = Number(data.chartList[i].workingHours) / 60;
            			bookingHoursList[i] = Number(Number(Number(data.chartList[i].bookingHours) / 60).toFixed(2));
            		}
            		
            		chart1 = $('#divChart1').highcharts();
            		chart1.xAxis[0].setCategories( dateList );
                	if( chart1.series != undefined && chart1.series != null ){
                    	for (var i = chart1.series.length; i > 0 ; i--) {
                    		chart1.series[ i - 1 ].remove( );
                        }
                	}
                	chart1.addSeries({ id : 1, name: userName, data: revenueList });
                	chart1.redraw();
                	
            		chart2 = $('#divChart2').highcharts();
            		chart2.xAxis[0].setCategories( dateList );
                	if( chart2.series != undefined && chart2.series != null ){
                    	for (var i = chart2.series.length; i > 0 ; i--) {
                    		chart2.series[ i - 1 ].remove( );
                        }
                	}
                	chart2.addSeries({ id : 1, name: userName, data: cntBookingList });
                	chart2.redraw();
                	
            		chart3 = $('#divChart3').highcharts();
            		chart3.xAxis[0].setCategories( dateList );
                	if( chart3.series != undefined && chart3.series != null ){
                    	for (var i = chart3.series.length; i > 0 ; i--) {
                    		chart3.series[ i - 1 ].remove( );
                        }
                	}
                	chart3.addSeries({ id : 1, name: userName + " Working Hours", data: workingHoursList });
                	chart3.addSeries({ id : 2, name: userName + " Booking Hours", data: bookingHoursList });
                	chart3.redraw();               
                	             	
                	
            		
            	}
            }else{
            	
            }
        }
    });	
}