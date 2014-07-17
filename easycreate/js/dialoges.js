  var sliderId = '';
	// ------------------------------ Image editor menu starts here ---------------------------/
	function getFileMenus(val)	{
		var options1 = [
			{title:"Change Image ", action:{type:"fn",callback:"(function(){ loadImageEditor('"+val+"','1'); })"}}	];
		return options1;
	}
	 $(".editablefile").live('click',(function() {
 	 	var test=$(this).attr('id');	 	 
	  	jQuery(this).jjmenu("click", getFileMenus(test),'',{show:"fadeIn", xposition:"mouse", yposition:"mouse"}); 
	  }));
	 // function to load the image editor popup
	 function loadImageEditor(val,type){  
		if(type==1)			dialogTitle = EDITOR_MANGE_IMAGES;
		else if(type==3) 	dialogTitle = EDITOR_IMAGE_SETTINGS;
	    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
	          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
	           dlg.load('editor_imageeditor.php?param='+val+'&type='+type, function(){   dlg.dialog('open');  });
	 }
	 // -------------------------- Image editor menu ends here ----------------------------

	 
	 
	 // -------------------------- Image slider starts here ----------------------
	 /* function getSliderMenus(val)	{
			var options1 = [
				{title:"Image Slider", action:{type:"fn",callback:"(function(){ loadSlideShowEditor('"+val+"','1'); })"}}	];
			return options1;
	 }*/
	 $(".editableslider").live('click',(function() {
		 /*
	 	 	var test=$(this).attr('data-param');	 	 
		  	jQuery(this).jjmenu("click", getSliderMenus(test),'',{show:"fadeIn", xposition:"mouse", yposition:"mouse"});
		  	*/
			dialogTitle = EDITOR_SLIDE_SHOW;
			var val=$(this).attr('data-param');	
			 sliderId = val;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		           dlg.load('editor/editor_slideshowmanagement.php?param='+val+'&type=1', function(){   dlg.dialog('open');  });
		    
                     //Track dialog box close
        $('div#opendialogbox').bind('dialogclose', function(event) {
            if(sliderId != ''){
                $.ajax({
                url: "editor/ajax_fetchBannerImage.php?slider="+sliderId,
                type: "POST",
                data: {},
                cache: false,
                dataType:'html',
                success: function(result) { //alert(result);
              
                if(result){
                    imageUrl = result;
                    $('.editableslider').attr('src',result);
                }else{
                    imageUrl = defaultSliderImage;
                    $('.editableslider').attr('src',defaultSliderImage);
                }
                }
                });
            }
           
            if(imageUrl)
                       $('.editableslider').attr('src',imageUrl);
        });
                    return false;
	 }));
         
         
        
	 // function to load the image editor popup
	 /*
	 function loadSlideShowEditor(val,type){  
			dialogTitle = "Manage Slide Show";
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		           dlg.load('editor/editor_slideshowmanagement.php?param='+val+'&type='+type, function(){   dlg.dialog('open');  });
	 }*/
	 
	 
	 // ---------------------------- image slider ends here ----------------------

	 
	 // ------------------------- social share data type starts --------------------
	 $(".editableshare").live('click',(function() {
			dialogTitle = EDITOR_SOCIAL_SHARE;
			var val=$(this).attr('data-param');	
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		           dlg.load('editor/editor_sociallinkmanagement.php?param='+val+'&type=1', function(){   dlg.dialog('open');  });
		    return false;
	 }));
	 // -------------------------- social share data type ends ----------------------
	 


	 // -------------------------- contact form datatype starts -------------------- 
	 $(".editablecontactform").live('click',(function() {

		var val= $(this).attr('data-type');
		var params= $(this).attr('id');	 	
		dialogTitle = EDITOR_CUSTOM_FORM;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:650, height:450  });
		          dlg.load('editor/editor_externalcustomformeditor.php?param='+val+'&type=1&params='+params, function(){  dlg.dialog('open');  });
	 }));
	 // ---------------------------- contact form datatype ends ----------------------------
	 
	 
	 
	 
	 // ------------------- Editable box code starts --------------------------------------
	 $(".editablebox").live('click',(function() {
	 	 	var elemid=$(this).attr('id');	 	 
			var params=$(this).attr('data-param');
		  	jQuery(this).jjmenu("click", getBoxMenus(elemid,params),'',{show:"fadeIn", xposition:"mouse", yposition:"mouse"}); 
	}));
	 function getBoxMenus(val,params)	{
			var options1 = [
				{title:"Remove Panel ", action:{type:"fn",callback:"(function(){ loadBoxEditor('"+val+"','1','"+params+"'); })"}},
				{title:"Settings", action:{type:"fn",callback:"(function(){ loadBoxEditor('"+val+"','2','"+params+"'); })"}} ];
			return options1;
	 }
	 // function to load the box editor popup
	 function loadBoxEditor(val,type,params){  
		dialogTitle = EDITOR_MANAGESOCIAL_SHARE;
	    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
	          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
	          dlg.load('editor/editor_externalboxeditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open'); });
	          return false;
	  }
	 // ------------------- Editable box code ends --------------------------------------
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 // ---------------------- Editable menu code starts --------------------------------
	 $(".editablenavmenu").live('click',(function() {
	 	 	var elemid=$(this).attr('id');	 	 
			var params=$(this).attr('data-param');
		  	jQuery(this).jjmenu("click", getNavmenuMenus(elemid,params),'',{show:"fadeIn", xposition:"mouse", yposition:"mouse"}); 
	}));
	 function getNavmenuMenus(val,params)	{
			var options1 = [
				{title:"Menu Settings ", action:{type:"fn",callback:"(function(){ loadMenuEditor('"+val+"','1','"+params+"'); })"}},
				{title:"Add Menu items", action:{type:"fn",callback:"(function(){ loadMenuEditor('"+val+"','2','"+params+"'); })"}},
				{title:"View Menu items", action:{type:"fn",callback:"(function(){ loadMenuEditor('"+val+"','3','"+params+"'); })"}}	];
			return options1;
	 }
	 // function to load the box editor popup
	 function loadMenuEditor(val,type,params){  
		dialogTitle = EDITOR_MENU_SETTINGS;
	    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
	          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
	          dlg.load('editor/editor_externalmenueditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	  }
	 // ---------------------- Editable menu code ends --------------------------------
	 
	
	 
	 
	 
	 //------------------------- editable form menu starts -------------------- 
	 $(".editableform").live('click',(function() {
	 	 	var elemid=$(this).attr('id');	 	 
			var params=$(this).attr('data-param');
		  	jQuery(this).jjmenu("click", getFormMenus(elemid,params),'',{show:"fadeIn", xposition:"mouse", yposition:"mouse"}); 
	 }));
	 function getFormMenus(val,params)	{
			var options1 = [
				{title:"Form Settings ", action:{type:"fn",callback:"(function(){ loadFormEditor('"+val+"','1','"+params+"'); })"}},
				{title:"Add Form items", action:{type:"fn",callback:"(function(){ loadFormEditor('"+val+"','2','"+params+"'); })"}},
				{title:"View Form items", action:{type:"fn",callback:"(function(){ loadFormEditor('"+val+"','3','"+params+"'); })"}}	];
			return options1;
	 }
	 function loadFormEditor(val,type,params){  
			dialogTitle = EDITOR_FORMSETTINGS;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:450, height:400  });
		          dlg.load('editor/editor_externalformeditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }
	 /* Load the custom form settings page	  */
     function loadCustomFormEditor(val,type,params){
			dialogTitle = EDITOR_CUSTOM_FORM;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:650, height:450  });
		          dlg.load('editor/editor_externalcustomformeditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }

     function loadGoogleAdsenseEditor(val,type,params){
			dialogTitle = EDITOR_GOOGLE_ADSENSE;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		          dlg.load('editor/editor_externaladsenseeditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }
	 // ------------------------ editable form menu ends ----------------------
	 
	 
	 
	 
	 
	 
	 
	 
	 // ----------------------------- Add new page popup code starts ------------------------------
	 $("#jqeditoraddpage").live('click',(function() {
		 	var action=$(this).attr('data-param');
			dialogTitle = EDITOR_PAGESETTINGS;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		          dlg.load('editor/editor_pagemanagement.php?action='+action, function(){  dlg.dialog('open');  });
	 })); 
	 // ----------------------------- Add new page popup code ends ---------------------------------


         // For Modified selectbox oncahnge
          // ----------------------------- Add new page popup code starts ------------------------------
	 $(".jQPages").live('change',(function() { 
            var action = this.value;
            //loadPageData(action);
            if(action =='addpage'){
                loadPageAddPopup(action);
                action = 'index';
            }
            var page = action;
            $('#jqsiteeditor').load('editor_pageloader.php?page='+page);
            return false;

	 }));


         $("#jQAddPage").live('click',(function() {
												
            var action = this.value;
            loadPageAddPopup(action);
	 }));

		

         function loadPageAddPopup(action){
             if(action=='addpage'){
                dialogTitle = EDITOR_PAGESETTINGS;
                var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
                autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
                dlg.load('editor/editor_pagemanagement.php?action='+action, function(){  dlg.dialog('open');  });
            }
         }

         /*

         function loadPageData(action){
             if(action=='addpage'){
                dialogTitle = "Page Settings";
                var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
                autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
                dlg.load('editor/editor_pagemanagement.php?action='+action, function(){  dlg.dialog('open');  });
            }else{
                var page = action;
                $('#jqsiteeditor').load('editor_pageloader.php?page='+page);
                return false;
            }
         }
         */
	 // ----------------------------- Add new page popup code ends ---------------------------------
         // For Modified

         /*****Add new page popup code- option mouseup*****/
         /*
           $("select").mouseup(function() {
            var open = $(this).data("isopen");

            if(open) {
                var action = this.value;
                //alert(action);
                if(action=='addpage'){
                    dialogTitle = "Page Settings";
                    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
                    autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
                    dlg.load('editor/editor_pagemanagement.php?action='+action, function(){  dlg.dialog('open');  });
                }else{
                    var page = action; 
                    $('#jqsiteeditor').load('editor_pageloader.php?page='+page);
                    $(this).data("isopen", !open);
                    return false;
                }
            }

            $(this).data("isopen", !open);
        }); */
	 // ----------------------------- Add new page popup code ends option mouseup ---------------------------
         // For Modified

         /*
         $(".jQPages").live('change',(function() {
            var action = this.value;
            if(action=='addpage'){
                dialogTitle = "Page Settings";
                var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
                autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
                dlg.load('editor/editor_pagemanagement.php?action='+action, function(){  dlg.dialog('open');  });
            }else{
                var page = action;
                $('#jqsiteeditor').load('editor_pageloader.php?page='+page);
                return false;
            }
	 }));
         */
	 /******Add new page popup code ends -option******/


	 
	 
	 // ---------------------------- Menu items popup code starts -----------------------------------
	 $(".jqeditormenusettings").live('click',(function() {
		 	var action=$(this).attr('data-param');
			dialogTitle = EDITOR_MENU_MANAGEMENT;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		          dlg.load('editor/editor_menumanagement.php?action=menumgmnt&id='+action, function(){  dlg.dialog('open');  });
	 }));
	 // ----------------------------- Menu item popup code ends -----------------------------------
	 
	 
	 
	 // ------------------------------ Widget : html box editor starts ------------------------
	 function loadHtmlboxEditor(val,type,params){  
			dialogTitle = EDITOR_HTML;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		          dlg.load('editor/editor_widgethtmlboxeditor.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }
	 // ---------------------------- Widget : html box editor ends -------------------------
	 

	 // ------------------------------ Widget : Image slide show starts  ------------------------
	 function loadSliderEditor(val,type,params){  
		 dialogTitle = EDITOR_IMAGE_SLIDE;
		 var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
			 autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		     dlg.load('editor/editor_slidermanagement.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }
	 // ---------------------------- Widget : image slide show ends -------------------------

	 
	 // ------------------------------ Widget : Guest book script starts  ------------------------
	 function loadGuestBookEditor(val,type,params){  
		 dialogTitle = EDITOR_GUEST;
		 var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
			 autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		     dlg.load('editor/editor_slidermanagement.php?param='+val+'&type='+type+'&params='+params, function(){  dlg.dialog('open');  });
	 }
	 // ---------------------------- Widget : Guest book script ends -------------------------
	 
	 
	 $('.classmailer').live('click',(function(){
			dialogTitle = EDITOR_CONTACTDETAILS;
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:600, height:400  });
		          dlg.load('editor/editor_contactformmanagement.php', function(){  dlg.dialog('open');  });

	 }));
	 
	 
	 
	 
	 
	 // --------------------------- open dialogu box from url starts --------------------------
	/*
	 $(".jqopendialoge").live('click',(function() {
		 	var action=$(this).attr('data-param');
			dialogTitle = "Menu Settings";
		    var dlg=$('#opendialogbox').dialog({ title: dialogTitle, resizable: true,
		          autoOpen:false,modal: true, hide: 'fade', width:500, height:300  });
		          dlg.load('editor/editor_menumanagement.php?action=menumgmnt&id='+action, function(){  dlg.dialog('open');  });
	 }));
	 */
	 // -------------------------- open dialogu box from url ends ----------------------
