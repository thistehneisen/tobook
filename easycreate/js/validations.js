 my_function = null;
$(document).ready(function(){ 

    //register page validations
    $("#btnHtmlPanelUpdate").live('click',(function() { 
        var panelId = $('#panelid').val();
        $.ajax({
            url: "../editor/updatepanel.php",
            type: "POST",
            data: $("#frmHtmlPanelUpdator").serialize(),
            cache: false,
            dataType:'html',
            success: function(html) { 
            	 //alert(html);
                var res = html.split("**");
                if(res[0] == 1){
                    newPanelContent = $('#panelhtml').val();
                    $('#panelEditResult').html('<span class="success">Successfully Updated the Panel</span>');
                    window.parent.$('#editpanel_'+panelId).html(newPanelContent);
                    setTimeout(function() {parent.$.fn.colorbox.close(); },500);
                }
                else
                    $('#panelEditResult').html('<span class="error">Some unexpected error occurred</span>');
            }
        });	  
    }));

    
    $("#btnPlainPanelUpdate").live('click',(function() {   
        var content = CKEDITOR.instances['editor1'].getData(); 
        var panelId = $('#panelid').val();
        var templateId = $('#templateid').val();
        var commonpanel = $('#commonpanel').val();
        $.ajax({
            url: "../editor/updatepanel.php",
            type: "POST",
            data: {panelId:panelId,templateId:templateId,content:content,type:'plain',commonpanel:commonpanel},
            cache: false,
            dataType:'html',
            success: function(html) {  //alert(html);
                var res = html.split("**");
                if(res[0] == 1){
                    content = res[1];
                    $('#panelEditResult').html('<span class="success">Successfully Updated the Panel</span>');
                    window.parent.$('#editpanel_'+panelId).html(content);
                    setTimeout(function() {parent.$.fn.colorbox.close() },500);
                }
                else
                    $('#panelEditResult').html('<span class="error">Some unexpected error occurred</span>');
            }
        });
    }));


     $("#btnPanelCancel").live('click',(function() {
        parent.$.fn.colorbox.close();
     }));
    
		  
		  
    // function to load the html editor
    $("#editor_showhtmlpanel").live('click',(function() {
        $('#editor_htmleditor').show();
        $('#editor_plaineditor').hide();
        return false;
    }));

    // function to load the plain editor
    $("#editor_showplainpanel").live('click',(function() {
        var params=$(this).attr('data-params');
        $('#editor_htmleditor').hide();
        $('#editor_plaineditor').show();
        //$('#editor_plaineditor').load('editor/plaineditorpage.php?params='+params);
        return false;
    }));

    // Function to save sitedetails to db
    $(".jQSaveSiteDetails").live('click',(function() { 
		$('#editactionloader').show();										   
        $.ajax({
            url: "editor/ajax_savesitedetails.php",
            type: "POST",
            data: {},
            cache: false,
            dataType:'html',
            success: function(result) { //alert(result);
                if(result == 1){
                    $('#editactionloader').hide();
                    var message = 'Site details saved successfully.';
                    var title = 'Save Site Details';
                    messageAlertsWithColorBox('success',message,'500px','200px',title);
                    /* $('#jQSavedSite').html("Site saved successfully");
                    setTimeout(function() {$('#jQSavedSite').hide(); },2000); */
                }
            }
        });
        return false;
    }));


    // Function to publish site 
    $(".jQPublishSite").live('click',(function() {
		$('#editactionloader').show();	
        $.ajax({
            url: "editor/ajax_publishsite.php",
            type: "POST",
            data: {},
            cache: false,
            dataType:'html',
            success: function(result) {  
                var paymentData = result.split("**"); 

                paymentStatus = paymentData[0];
                paymentType = paymentData[1];

                if(paymentStatus > 0){
                    window.location = 'downloadsite.php';
                }else{
                    if(paymentType == 'no'){
                        window.location = 'downloadsite.php';
                    }else{
                        window.location = 'publishpage.php';
                    }
                } 
            }
        });
        return false;
    }));

         
    $(".noclick").live('click',(function() {
        return false;
    }));
		  
		  
		  
		  
   		  		  
		  
    $("#showuserimageuploader").live('click',(function() {
    	$(".jqloadPage").removeClass('linkactive');
    	$(".jqloadSlidePage").removeClass('linkactive');
		$(this).addClass('linkactive');
        $('#editor_imguploader').show();
        $('#editorimagegallery').hide();
        return false;
    }));
		 
		  
    // function to load the another pages of the user created site
    $(".jqsitepageloader").live('click',(function() {
    	$(".jqsitepageloader").removeClass("active");
    	$(this).addClass('active');
        var page = $(this).attr('data-params');
        $('#jqsiteeditor').load('editor_pageloader.php?page='+page);
        return false;
    }));
	
    
    
    // function to delete the external applications
    $(".jqdeleteextapp").live('click',(function() { 
    	if (confirm('Are you sure to remove the panel')) {
    		var panelId=$(this).attr('data-params');
    		$.ajax({
				url: "editor/editor_ajaxcallresult.php?type=deleteapp",	
				type: "POST",
				data: {panelId:panelId},
				cache: false,
				dataType:'html',			
				success: function(html) {  //alert(html);
					if(html == 'success') {  
						$('#item_'+panelId).remove();
						$('#'+panelId).remove();
					}
				}
    		}); 		
    	}
    	return false;
    }));
    
  
    // function to show the external link option
    $("#jqChkextlink").live('click',(function() {
    		$('#jqMenuPagelist').hide();
    		$('#jqMenuExtBox').show();
        return false;
    }));
    
    $("#jqCancelextlink").live('click',(function() {
		$('#jqMenuExtBox').hide();
		$('#jqMenuPagelist').show();
		return false;
    }));
    
    
    $('.jtheme').live('click',(function() {
   	 
  	 
    	$(".jtheme").removeClass("themeactive");
    	$(this).addClass('themeactive');
    	return false;
    }));
    
		  
    function my_fun(url){
    	$('#homeimage').attr('src','images/loading.gif');
    	$('#homeimage').attr('src',url).load(function(){
    		$('#jqtemplateloading').hide();
    	});
    	 
    }  
    my_function = my_fun;
    
    
});
 


 
  
function changehomeimage(template_id,type,imageurl)
{
	$('#jqtemplateloading').show();
	//url = "showtemplateimage.php?type="+type+"&tmpid="+template_id+"&imgname="+imageurl;
	url = "templates/"+template_id+"/"+imageurl;
	my_function(url);
    //document.getElementById("homeimage").src= "showtemplateimage.php?type="+type+"&tmpid="+template_id+"&imgname="+imageurl;
	
  
}

function changesubimage(template_id,type,imageurl)
{
    //document.getElementById("subimage").src= "showtemplateimage.php?type="+type+"&tmpid="+template_id+"&imgname="+imageurl;
	document.getElementById("subimage").src = "templates/"+template_id+"/"+imageurl;
}
function changehiddenvalue(template_id,theme_id)
{
	  
    document.getElementById("chekSelTemplate").value=+template_id+'_'+theme_id;
}




function changehomeimagead(template_id,type,imageurl)
{
    //document.getElementById("homeimage").src= "../admin/showtemplateimage.php?type="+type+"&tmpid="+template_id+"&imgname="+imageurl;
    document.getElementById("homeimage").src= "../templates/"+template_id+"/"+imageurl;}

function changesubimagead(template_id,type,imageurl)
{
	 document.getElementById("subimage").src = "../templates/"+template_id+"/"+imageurl;
    //document.getElementById("subimage").src= "../admin/showtemplateimage.php?type="+type+"&tmpid="+template_id+"&imgname="+imageurl;
}

function messageAlertsWithColorBox(messageType,message,width,height,title){
    
    var mlink="editor/editor_messages.php?messageType="+messageType+"&message="+message;
    $.colorbox({width:width, height:height, iframe:true, href:mlink, title:title, overlayClose: false});
    return false;

}



 




