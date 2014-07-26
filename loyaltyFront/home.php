<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
	<head>
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap.style.css">
		<link rel="stylesheet" href="css/responsive.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
			
		<link rel='stylesheet' href="css/style.css" type='text/css' media='all'/>
		
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script src="js/jquery_cookie.js"></script>
				
		<script type="text/javascript" src="js/home.js"></script>	
		<title>NFC Desktop App</title>
		<?php
			if( isset( $_COOKIE['CUSTOMER_TOKEN'] ) ){
				$customerToken = $_COOKIE['CUSTOMER_TOKEN'];
			}else{
				header("location: login.php");
			}
		?>
	</head>	
	<body>
		<input type="hidden" id="customerToken" value="<?php echo $customerToken;?>">
		<div class="frontTopBackground" style="position:relative;">
			<div class="frontTopTitle">Kantiskortti</div>
			<div onclick="onLogout()" style="position:absolute;color:#000;font-size:15px;right:50px; bottom:10px;font-weight:bold;cursor:pointer;">Kirjaudu ulos</div>
		</div>
		<div class="greyDivider"></div>
		
		<div class="frontHomeBackground container" style="text-align:left;">
			<div class="col-md-7 col-md-offset-1">
				<div style="height:30px;"></div>
				<div class="floatleft"><h3>Consumer List</h3></div>
				<div class="floatright">
					<button class="btn-u btn-u-blue" onclick="onAddConsumer()">ADD</button>
					<button class="btn-u btn-u-red" onclick="onDeleteConsumer()">DELETE</button>
				</div>
				<div class="clearboth"></div>
				<table class="table table-striped" id="tblDataList">
					<thead>
						<tr>
							<th style="width:50px;"></th>
							<th style="width:50px;">No</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Points</th>
							<th>Registered</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>				
			</div>
			<div class="col-md-3">
				<div style="height:30px;"></div>
				<h3 id="consumerName">&nbsp;</h3>
				<span id="consumerEmail" style="color:#777;">&nbsp;</span>
				<span id="consumerPhone" style="color:#777;">&nbsp;</span>
				<p id="consumerScore">&nbsp;</p>
				<hr/>
				<button class="btn-u btn-block btn-u-sea" onclick="onClickGiveScore()">Anna Pisteita</button>
				<div style="height:10px;"></div>
				<h3>Palkinnot</h3>
				<div id="pointList">
					
				</div>
				<div style="height:10px;"></div>
				<h3>Leimat</h3>
				<div id="stampList">

				</div>
				<div style="height:10px;"></div>
				<button class="btn-u btn-block btn-u-sea">Luo uusi kanta-asiakaskortti</button>
				<div style="height:10px;"></div>
				<button class="btn-u btn-u-sea">Takaisin</button>
			</div>
		</div>
		
		<div class="greyDivider"></div>
		
		<div id="cloneStampItem" style="display:none;">
			<div class="col-md-4">
				<button class="btn-u btn-u-blue btn-block" style="padding:2px;" onclick="onAddStamp(this)">Lis‰‰</button>
				<button class="btn-u btn-u-blue btn-block" style="padding:2px;" onclick="onUseStamp(this)">K‰yt‰</button>
			</div>
			<div class="col-md-8">
				<span id="stampRequired"></span>&nbsp;<span id="stampName"></span>
			</div>						
			<div class="clearboth"></div>
		</div>		
		
		<div id="clonePointItem" style="display:none;">
			<div class="col-md-7">
				<div id="pointName"></div>
				<div id="scoreRequired"></div>
			</div>
			<div class="col-md-5"><button class="btn-u btn-u-blue btn-block" style="padding:5px;" onclick="onUsePoint(this)">K‰yt‰ etu</button></div>
			<div class="clearboth"></div>
		</div>
		
		<div class="modal fade" id="dlgConsumerInfo">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h4 class="modal-title">Consumer Info</h4>
		      </div>
		      <div class="modal-body">
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">First Name</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="firstName" placeholder="First Name"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Last Name</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="lastName" placeholder="Last Name"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Email</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="email" placeholder="Email Address"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Phone</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="phone" placeholder="Phone No"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Address1</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="address1" placeholder="Address1"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">City</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="city" placeholder="City"></div>
				<div class="clearboth"></div>																				
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseDlgConsumerInfo">Close</button>
		        <button type="button" class="btn btn-primary" onclick="onSaveConsumer()">Save</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->			
		
		<div class="modal fade" id="dlgGiveScore">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
		        <h4 class="modal-title">Give Score</h4>
		      </div>
		      <div class="modal-body">
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Score</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="giveScore" placeholder="Score"></div>
				<div class="clearboth"></div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseDlgGiveScore">Close</button>
		        <button type="button" class="btn btn-primary" onclick="onGiveScore()">Save</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->				
		
	</body>
</html>