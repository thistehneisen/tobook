var chart1;
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
            text: $("#statisticsOfRevenue").val(),
            x: -20
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: $("#revenue").val()
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
            text: $("#statisticsOfReservations").val(),
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: $("#reservations").val()
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
        title: {
            text: $("#statisticsOfCovers").val(),
            x: -20 //center
        },
        xAxis: {
            categories: ['']
        },
        yAxis: {
            title: {
                text: $("#covers").val()
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
function onCalculate( ){
	var userId = $("#userList").val( );
	var userName = $("#userList option:selected").text();
	var startDate = $("#startDate").val();
	var endDate = $("#endDate").val();
	var viewMode = $("#viewMode").val();

	$.ajax({
        url: "async-getStatisticsRBInfo.php",
        dataType : "json",
        type : "POST",
        data : { userId : userId, userName : userName, startDate : startDate, endDate : endDate, viewMode : viewMode },
        success : function(data){
            if(data.result == "success"){
            	console.log( data.chartList );
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
                	
            		chart2 = $('#divChart2').highcharts();
            		chart2.xAxis[0].setCategories( dateList );
                	if( chart2.series != undefined && chart2.series != null ){
                    	for (var k = chart2.series.length; k > 0 ; k--) {
                    		chart2.series[ k - 1 ].remove( );
                        }
                	}
                	
            		chart3 = $('#divChart3').highcharts();
            		chart3.xAxis[0].setCategories( dateList );
                	if( chart3.series != undefined && chart3.series != null ){
                    	for (var k = chart3.series.length; k > 0 ; k--) {
                    		chart3.series[ k - 1 ].remove( );
                        }
                	}
                	
            		for( var i = 0 ; i < data.chartList.length; i ++ ){
            			var revenueList = [];
                		var cntPeopleList = [];
                		var cntBookingList = [];
                		var employeeName = data.chartList[i][0].userName;;
                		
                		for( var j = 0 ; j < data.chartList[i].length; j ++ ){
                			revenueList[j] = Number(data.chartList[i][j].revenue);
                			cntPeopleList[j] = Number(data.chartList[i][j].cntPeople);
                			cntBookingList[j] = Number(data.chartList[i][j].cntBooking);
                		}

                    	chart1.addSeries({ id : i, name: employeeName, data: revenueList });
                    	chart1.redraw();
                    	
                    	chart2.addSeries({ id : i, name: employeeName, data: cntBookingList });
                    	chart2.redraw();
                    	
                    	chart3.addSeries({ id : i, name: employeeName, data: cntPeopleList });
                    	chart3.redraw();                    	

                    	for( var k = 0 ; k < data.chartList[i].length; k ++ ){
                			strHTML += "<tr>" 
                				+ "<td style='text-align:center;'>" + String( i * dateList.length + k + 1 )+ "</td>"
                				+ "<td style='text-align:center;'>" + dateList[k] + "</td>"
                				+ "<td style='text-align:center;'>" + employeeName + "</td>"
                				+ "<td style='text-align:right;'>" + String(revenueList[k]) + " Euro" + "</td>"
                				+ "<td style='text-align:center;'>" + String(cntBookingList[k]) + "</td>"
                				+ "<td style='text-align:center;'>" + String(cntPeopleList[k]) + "</td>"
                				+ "</tr>";
                		}
                    	if( userId == "" ){
                    		$("#lblListType").html( $("#users").val() );
                    	}else{
                    		$("#lblListType").html( $("#services").val() );
                    	}
            		}
                	$("#tblStatistics").find("tbody").html( strHTML );
            	}
            }else{
            	
            }
        }
    });	
}