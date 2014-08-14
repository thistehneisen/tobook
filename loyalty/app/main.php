<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
	<head>
	    <meta charset="UTF-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap.style.css">
		<link rel="stylesheet" href="css/responsive.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
		<link rel='stylesheet' href="css/style.css" type='text/css' media='all'/>
		<link rel="stylesheet" href="http://www.datatables.net/media/blog/bootstrap_2/DT_bootstrap.css">
		<title>NFC Desktop App</title>
		<?php
			if (isset($_COOKIE['CUSTOMER_TOKEN'])) {
				$customerToken = $_COOKIE['CUSTOMER_TOKEN'];
			} else {
				header("location: login.php");
			}
		?>
	</head>	
	<body>
		<input type="hidden" id="customerToken" value="<?php echo $customerToken;?>">
		<div class="frontTopBackground" style="position:relative;">
			<div class="frontTopTitle">Kantiskortti</div>
			<div id="btnLogout">Kirjaudu ulos</div>
		</div>
		<div class="greyDivider"></div>
		
		<div class="frontHomeBackground container" style="text-align:left;width:1000px;">
			<div style="margin-left: 3%;width:60%;"  class="floatleft">
				<div style="height:30px;"></div>
				<div class="floatleft"><h3>Asiakaslista</h3></div>
				<div class="floatright">
					<button class="btn-u btn-u-blue" id="btnAddConsumer" >Lisää</button>&nbsp;
					<button class="btn-u btn-u-red" id="btnDeleteConsumer" >Poista</button>
				</div>
				<div class="clearboth"></div>
				<table class="table table-striped" id="tblDataList">
					<thead>
						<tr>
							<th style="width:50px;"></th>
							<th style="width:50px;">N.</th>
							<th>Nimi</th>
							<th>Sähköposti</th>
							<th>Puhelin</th>
							<th>Pisteet</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>				
			</div>
			<div style="width:30%;display:none;margin-left:4%" id="divConsumerInfo" class="floatleft">
				<div style="height:30px;"></div>
				<h3 id="consumerName">&nbsp;</h3>
				<span id="consumerEmail" style="color:#777;">&nbsp;</span>
				<span id="consumerPhone" style="color:#777;">&nbsp;</span>
				<p id="consumerScore">&nbsp;</p>
				<hr/>
				<button class="btn-u btn-block btn-u-sea" id="btnOpenGiveScore">Anna Pisteita</button>
				<div style="height:10px;"></div>
				<h3>Palkinnot</h3>
				<div id="pointList">
					
				</div>
				<div style="height:10px;"></div>
				<h3>Leimat</h3>
				<div id="stampList">

				</div>
				<div style="height:10px;"></div>
				<button class="btn-u btn-block btn-u-sea" id="btnWriteCard">Luo uusi kanta-asiakaskortti</button>
				<div style="height:10px;"></div>
				<button class="btn-u btn-u-sea">Takaisin</button>
			</div>
			<div class="clearboth"></div>
		</div>
		
		<div class="greyDivider"></div>
		
		<div id="cloneStampItem" style="display:none;">
			<div class="col-md-4">
				<button class="btn-u btn-u-blue btn-block" style="padding:2px;" id="btnAddStamp" onclick="onAddStamp(this)">Lisää</button>
				<button class="btn-u btn-u-blue btn-block" style="padding:2px;" id="btnUseStamp" onclick="onUseStamp(this)">Käytä</button>
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
			<div class="col-md-5"><button class="btn-u btn-u-blue btn-block" style="padding:5px;" id="btnUsePoint" onclick="onUsePoint(this)" >Käytä etu</button></div>
			<div class="clearboth"></div>
		</div>
		
		<div class="modal fade" id="dlgConsumerInfo">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sulje</span></button>
		        <h4 class="modal-title">Consumer Info</h4>
		      </div>
		      <div class="modal-body">
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Etunimi</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="firstName" placeholder="Etunimi"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Sukunimi</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="lastName" placeholder="Sukunimi"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Sähköposti</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="email" placeholder="Sähköposti"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Puhelin</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="phone" placeholder="Puhelin"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Osoite</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="address1" placeholder="Osoite"></div>
				<div class="clearboth"></div>
				
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Kaupunki</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="city" placeholder="Kaupunki"></div>
				<div class="clearboth"></div>																				
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseDlgConsumerInfo">Sulje</button>
		        <button type="button" class="btn btn-primary" id="btnSaveConsumer" >Tallenna</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->			
		
		<div class="modal fade" id="dlgGiveScore">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Sulje</span></button>
		        <h4 class="modal-title">Pisteet</h4>
		      </div>
		      <div class="modal-body">
				<div class="col-md-3 col-md-offset-1" style="text-align:right;line-height:35px;margin-top:5px;font-weight:bold;">Pisteet</div>
				<div class="col-md-7" style="margin-top:5px;"><input type="text" class="form-control" id="giveScore" placeholder="Pisteet"></div>
				<div class="clearboth"></div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal" id="btnCloseDlgGiveScore">Sulje</button>
		        <button type="button" class="btn btn-primary" id="btnGiveScore">Tallenna</button>
		      </div>
		    </div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->				
		
		<script type="text/javascript" src="js/responsive.js"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/jquery_cookie.js"></script>
		<script type="text/javascript" src="js/respond.js"></script>
        <script type="text/javascript" src="js/jquery.dataTables.js"></script>
        <script type="text/javascript" src="js/DT_bootstrap.js"></script>		
		<script type="text/javascript" src="js/main.js"></script>			
	</body>
</html>