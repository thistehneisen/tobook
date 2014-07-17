 
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
 
<STYLE>
 
#accordion { width: 210px;}
#accordion li{
	display: block;
	background-color: #CCC;
	font-weight: bold;
	margin: 1px;
	cursor: pointer;
	padding: 5 5 5 7px;
	list-style: circle;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
}
#accordion ul { list-style: none; padding: 0 0 0 0;	display: none;}
#accordion ul li{ font-weight: normal; cursor: auto; background-color: #fff; padding: 0 0 0 7px; }
#accordion a { 	text-decoration: none; }
#accordion a:hover { text-decoration: underline; }

</STYLE>
 
 
   
			
			
			
			
<ul id="accordion">
	<li>Pages</li>
	<ul>
		 
		<li>
		<div id="jqsitepagelist">
 		<?php 
		foreach($_SESSION['siteDetails']['pages'] as $pages){
			echo '<a href="#" class="jqsitepageloader" data-params="'.$pages['title'].'">'.$pages['title'].'</a> <br>';
		}
		?>  </div>
		
		</li>
		<li><a href="#" id="jqeditoraddpage" data-param="viewpage">View pages</a></li>
		<li><a href="#" id="jqeditoraddpage" data-param="addpage">Add page</a></li>
	</ul>
	<li>Applications</li>
	<ul>
		<li> Social Share 
		<!--  
			<div class="column" id="externalapp_socialshare" >
					<div class="dragbox" id="exterbox_socialshare_1" >
						<h4>SocialShare</h4>
							<div class="editablebox" style="padding:5px 0px 5px 20px;" data-param="exterbox_socialshare_1" id="editablebox_10">
							 	 
							 	<?php 
							  
							 	$appParams = getEditorAppParams(EDITOR_APP_SOCIAL_SHARE);
							 	if(mysql_num_rows($appParams) > 0) {
							 		while($row = mysql_fetch_assoc($appParams)){
										if($row['param_img'] != '') {
											echo '<img src="'.EDITOR_IMAGES.$row['param_img'].'">&nbsp;&nbsp;&nbsp;';
										}
								    }
								}
							 	?><br> [click here to edit]
							 </div>
					</div>
			</div>
		-->
		
		</li>
 		<li> Navigation Menu 
 		
  		  <!-- 
			<div class="column" id="externalapp_navmenu" >
					<div class="dragbox" id="exterbox_navmenu_1" >
						<h4>Navigation menu  </h4> 
							<div class="editablenavmenu" data-param="exterbox_navmenu_1" id="editablebox_4">
					 			 [click here to edit] <ul id="menueditablebox_4"></ul>
							</div>
					</div>
			</div>
		 
	 -->

		
		</li>
		
		<li> Forms
		
		</li>
		<li> New nav 
 		<!-- 	<div class="column" id="externalapp_navmenu" data-attr="exterbox_navmenu_1">
					<div class="dragbox" id="exterbox_navmenu_1" >
						<h4>Navigation menu  </h4> 
							 
					</div>
			</div> -->
 		</li>
		
		
		<br><br><br>
		
	</ul>
	<li>Settings</li>
	<ul>
		<li><a href="#">Site Details</a></li>
		<li><a href="#">Meta Details</a></li>
 	</ul>
</ul>


 
<SCRIPT>
$("#accordion > li").click(function(){

	if(false == $(this).next().is(':visible')) {
		$('#accordion > ul').slideUp(300);
	}
	$(this).next().slideToggle(300);
});

$('#accordion > ul:eq(0)').show();

</SCRIPT>
 