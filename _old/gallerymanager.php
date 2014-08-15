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
?>
<?php

$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "ajax_image_upload.php";

$_SESSION['sess_edittype']="usergallery";
$_SESSION['currentimage']="";
$_SESSION["session_checkid"]="";

//delete from tempeditimage
if (isset($_SESSION["session_userid"]) AND $_SESSION["session_userid"] <> "") {
    if(is_dir("./tmpeditimages/".$_SESSION["session_userid"])) {
        remove_dir("./tmpeditimages/".$_SESSION["session_userid"]);
    }
}

include "includes/userheader.php";
?>
<script type='text/javascript' src="js/jquery.js"></script>
<script type='text/javascript' src="js/jquery.ui.js"></script>
<script type='text/javascript' src="js/jquery.imgareaselect.js"></script>
<script type='text/javascript' src="js/jquery.icolor.js"></script>
<script type='text/javascript' src="js/ajaxupload.js"></script>
<script type='text/javascript'>
    var chk = /ERROR:/g;
    var flip_count = 0;
    var flop_count = 0;
    var dragCount = 0;
    var zindex = 10;

    var mainUrl = '<?php echo BASE_URL ?>'; //alert(mainUrl);

    var userAction = 0; //check if user has made any modifications

    $(function(){
        //-----------------------------------------AJAX upload------------------------------------//
        new AjaxUpload('user_image', {
            action: 'ajax_image_upload.php',
            name: 'userImages',
            onSubmit : function(file , ext){
                if(ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
                    $("div#upload_div").hide();
                    $("div#msg_div").show();
                    return true;
                }
                else{
                    alert('<?php echo VAL_IMAGE;?>');
                    return false;
                }
            },
            onComplete : function(file, response){
                if(!chk.test(response)){
                    if($.trim($("div.thumb_container").html()) == '<?php echo VAL_NOIMAGE;?>'){
                        $("div.thumb_container").html('');
                    }
                    $("div.thumb_container").prepend("<label onClick='loadImage(this, 1)' style='padding-right:2px;'><img src='"+response+"' class='highlight'/></label>");
                    $("#jQsuccessMsgDiv").show();
                    $("#jQsuccessMsgDiv").html("<?php echo VAL_IMAGE_SUCC;?>");
                    setTimeout(function(){$("#jQsuccessMsgDiv").hide()},10000);
                    //slider();
                }
                else alert(response);
                $("div#msg_div").hide();
                $("div#upload_div").show();
            }
        });

        //-------------------------Initailize the tabs----------------------------//
        $(".editor_property_content").hide(); //Hide all content
        $("ul.tabs li:first a").addClass("selected").show(); //Activate first tab
        $(".editor_property_content:first").show(); //Show first tab content

        //On Click Event
        $("ul.tabs li").click(function() {
            $("ul.tabs li a").removeClass("selected"); //Remove any "active" class
            $(this).find("a").addClass("selected"); //Add "active" class to selected tab
            $(".editor_property_content").hide(); //Hide all tab content
            var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            $(activeTab).fadeIn(); //Fade in the active content
            return false;
        });

        //----------------------colorpicker------------------------//
        $("#text_color").icolor({
            col:4,
            colors:["FFFFFF", "EEEEEE", "FFFF88", "FF7400", "CDEB8B", "6BBA70", "C3D9FF", "4096EE", "FF0000", "C0C0C0", "800000", "00FF00", "356AA0", "FF0096", "B02B2C", "000000"],
            onSelect:function(c){
                var tmp = c.split("#");
                $("#text_color").val(tmp[1]);
                //updateLabel($("input#edtlabelid").val());
            }
        });

        //----------------------sliders------------------------------//
        initBrightnessSlider();
        initContrastSlider();

        $("#txt_apply_btn").click(function(){updateText();});
        $("#txt_delete_btn").click(function(){deleteText();});
        $("#txt_done_btn").click(function(){resetText();});
        $("#image_save").click(function(){getPos();});
        $("#crop_lnk").click(function(){initSelectionArea();});

        $("#imglink").click(function(){confirmAction();});
        $("#txtlink").click(function(){confirmAction();});
        $("input#hid_coords").val('');
    });

    function loadImage(elem, onclickload)
    {
        if(onclickload == 1){
            var imgPath = $(elem).children("img").attr("src");
        }
        else{
            var imgPath = elem;
        }

        imgPath  = imgPath.split('?time='); 
        var imgParts = imgPath[0].split('/');
        

        var dataExplode      = imgPath[0].replace(mainUrl,"");
        var dataExplodeParts = dataExplode.split('/');

        $.ajax({
            type: "POST",
            url: "ajax_image_upload.php?get_img_attributes="+dataExplodeParts[2],
            success: function(response){ //alert(response);
                var param = response.split("#");

                if(param[0] > 562 )
                    var widthVal = 562;
                else
                    var widthVal = param[0];
                $('img#custom_img').css({width : widthVal+'px'});
            }
        }); 

        $.ajax({
            async: true,
            url: "ajax_image_upload.php?browserimage="+imgParts[imgParts.length-1]+"&randomnum="+Math.floor(Math.random()*10001)
        });

        $('div#img_holder_div').empty();
        $('div#img_holder_div').append("<div id='img_parent'><img  id='custom_img' src='ajax_image_upload.php?browserimage="+imgParts[imgParts.length-1]+"&randomnum="+Math.floor(Math.random()*10001)+"' /></div>");
        $("input#img_name").val(imgParts[imgParts.length-1]);

        initBrightnessSlider();
        initContrastSlider();
        $("input#rotate_angle").val(0);
        getImageAttr(imgParts[imgParts.length-1]);
        flip_count = 0;
        flop_count = 0;
        $("#image_effects").val('NA');

        $('input.hiddenvars').val(0);
        userAction = 0;
        resetSelectionArea();
    }

    function updateImage()
    {
        var isImgSelected = $('div#img_parent').children('img').length;
        if(isImgSelected == 0){
            alert('Please select an image.');
        }
        else{
            var imgPath = $("input#img_name").val();
            var rotAngle = $("input#rotate_angle").val();
            var brightness = $("input#brightness_value").val();
            var contrast = $("input#contrast_value").val();

            var height = $("input#image_height").val();
            var width = $("input#image_width").val();
            var effects = $("#image_effects :selected").val();

            var coords = $('input#x1').val()+'_'+$('input#x2').val()+'_'+$('input#y1').val()+'_'+$('input#y2').val();

            $.ajax({
                async: true,
                url: "ajax_image_upload.php?browserimage="+imgPath+"&resize=1&randomnum="+Math.floor(Math.random()*10001)+"&rotate="+rotAngle+"&brightness="+brightness+"&contrast="+contrast+"&width="+width+"&height="+height+"&flip="+flip_count+"&flop="+flop_count+"&effects="+effects+"&coords="+coords
            });

            $('div#img_holder_div').empty();
            $('div#img_holder_div').append("<div id='img_parent'><img id='custom_img' src='ajax_image_upload.php?browserimage="+imgPath+"&resize=1&randomnum="+Math.floor(Math.random()*10001)+"&rotate="+rotAngle+"&brightness="+brightness+"&contrast="+contrast+"&width="+width+"&height="+height+"&flip="+flip_count+"&flop="+flop_count+"&effects="+effects+"&coords="+coords+"' /></div>");

            //if rotated, attributes will change so reset them
            $.ajax({
                url: "ajax_image_upload.php?browserimage="+imgPath+"&resize=1&randomnum="+Math.floor(Math.random()*10001)+"&rotate="+rotAngle+"&brightness="+brightness+"&contrast="+contrast+"&width="+width+"&height="+height+"&flip="+flip_count+"&flop="+flop_count+"&effects="+effects+"&coords="+coords+"&noimage=1",
                success: function(response){
                    var tmp = response.split("_");
                    $("input#image_height").val(tmp[0]);
                    $("input#image_width").val(tmp[1]);
                }
            });

            //initSelectionArea();

            userAction = 1;
        }
    }

    function initBrightnessSlider()
    {
        // idea is to destory the element for each instance of image
        $("#ui_slider_brightness").slider("destroy");
        $("input#brightness_value").val(0);

        //$("div#msg_loader").show();
        $("#ui_slider_brightness").slider({
            value: 0,
            min: -255,
            max: 255,
            stop: function(event, ui) { $("input#brightness_value").val(ui.value); updateImage();  }
        });
    }

    function initContrastSlider()
    {
        // idea is to destory the element for each instance of image
        $("#ui_slider_contast").slider("destroy");
        $("input#contrast_value").val(0);

        $("#ui_slider_contast").slider({
            value: 0,
            min: -255,
            max: 255,
            stop: function(event, ui) { $("input#contrast_value").val(ui.value); updateImage() }
        });
    }

    function getImageAttr(imageName)
    {
        $.ajax({
            type: "POST",
            url: "ajax_image_upload.php?get_img_attributes="+imageName,
            success: function(response){
                //alert(response);
                var param = response.split("#");
                $("input#image_height").val(param[1]);
                $("input#image_width").val(param[0]);

                $('div#img_parent').css({width : param[0]+'px', height : param[1]+'px', position : 'absolute'});
            }
        });
    }

    function flipImage()
    {
        var isflip = flip_count == 0 ? 1 : 0;
        flip_count = isflip;

        updateImage();
    }

    function flopImage()
    {
        var isflop = flop_count == 0 ? 1 : 0;
        flop_count = isflop;

        updateImage();
    }

    function initSelectionArea()
    {
        var isImgSelected = $('div#img_parent').children('img').length;
        if(isImgSelected == 0){
            alert('<?php echo VAL_IMAGE_SELECT;?>');
        }
        else{
            $('img#custom_img').imgAreaSelect({
                x1: 10,
                y1: 10,
                x2: 40,
                y2: 40,
                handles: true,
                onSelectEnd: function (img, selection) {
                    //alert('x1: ' + selection.x1 + '; x2: ' + selection.x2);
                    /*if(confirm("Crop the current selection?")){
                            $('img#custom_img').imgAreaSelect({remove : true});
                        }*/
                    $('input#x1').val(selection.x1);
                    $('input#x2').val(selection.x2);
                    $('input#y1').val(selection.y1);
                    $('input#y2').val(selection.y2);
                }
            });
            $('#txtli').hide();
            $('#crop_img_handles').slideToggle('slow');
            $('#default_img_handles').slideToggle('slow');
        }
    }

    function resetSelectionArea(){
        $('img#custom_img').imgAreaSelect({remove: true});
        $('#crop_img_handles').slideUp('slow');
        $('#default_img_handles').slideDown('slow');
        $('#txtli').show();
        $('input#x1').val();
        $('input#x2').val();
        $('input#y1').val();
        $('input#y2').val();
    }

    function saveCropArea(){
        $('img#custom_img').imgAreaSelect({remove: true});
        updateImage();
        getPos();
    }

    function insertNthChar(string,chr,nth) {
        var output = '';
        for (var i=0; i<string.length; i++) {
            if (i>0 && i%4 == 0)
                output += chr;
            output += string.charAt(i);
        }

        return output;
    }

    function modifyString(str){
        var mdstr='';
        var count=40;
        for(var i=0;i<str.length;i=i+count){
            var temp= str.substr(i,count);
            mdstr+=temp+'<br>';

        }
        return mdstr;
    }

    function updateText(){

        var isImgSelected = $('div#img_parent').children('img').length;
        if(isImgSelected == 0){
            alert('<?php echo VAL_IMAGE_SELECT;?>');
        }
        else{
            if($.trim($("textarea#img_text").attr('value')) != ''){
                var txt = $("textarea#img_text").attr('value').replace(/\n/g,'<br>');
                var len=txt.length;
                var ss='';
                var index=txt.search('<br>');
                var str=txt.split('<br>');
                for(var i=0;i<str.length;i++){
                    if(str[i].length>60){
                        str[i]=modifyString(str[i]);

                        // str[i] = str[i].splice(40, 0, "<br>" );

                        // "foo bar baz"
                        ss+=str[i];
                    }else{
                        ss+=str[i]+'<br>';
                    }
                }
                txt=ss;

                var fnt = $("#text_font :selected").val();
                var wght = $("#text_size :selected").val();
                var fntclr = $("#text_color").val();

                var datetime = Math.round(new Date().getTime() / 1000);

                var handle = document.createElement('div');
                handle.id = 'draggable_'+dragCount;
                handle.align = 'left';
                handle.datetime = datetime;
                handle.className = "draggable DOM "+fnt;
                handle.style.fontSize = wght+'px';
                zindex++;
                handle.style.zIndex = zindex;
                handle.style.position = 'absolute';
                handle.innerHTML = "<label fontclr='"+fntclr+"' fontsize='"+wght+"' txt='"+txt+"' fonttyp='"+fnt+"' class='edit' id='lab_"+dragCount+"' datetime='"+datetime+"'><img src='ajax_image_upload.php?textgen=1&msg="+urlEncode(txt)+"&size="+wght+"&fore="+fntclr+"&font="+fnt+"&randnum="+Math.floor(Math.random()*10001)+"' /></label>";
                $('div#img_parent').prepend(handle);
                $("#draggable_"+dragCount).draggable({
                    containment: "parent"
                });
                $("#draggable_"+dragCount).click(function(){editDiv(this.id);});

                $("textarea#img_text").attr('value','');
                $("#text_font").attr('selectedIndex', 0);
                $("#text_size").attr('selectedIndex', 0);
                $("#text_color").val("000000");

                dragCount++;
            }
        }
    }

    function editDiv(param)
    {
        showTab("txtli");
        var labid = param.split("_");

        $("#text_font").val($("#lab_"+labid[1]).attr('fonttyp'));
        $("#text_size").val($("#lab_"+labid[1]).attr('fontsize'));
        $("#text_color").val($("#lab_"+labid[1]).attr('fontclr'));
        $("textarea#img_text").attr('value', $("#lab_"+labid[1]).attr('txt').replace(/<BR>/gi,'\n'));

        $("#txt_apply_btn").unbind('click');
        $("#txt_apply_btn").click(function(){editText();});

        $("#txt_delete_btn").show();
        $("#txt_done_btn").show();
        $("input#sel_txt_div_id").val(param);
    }

    function urlEncode(url){
        var op = '';
        $.ajax({
            type: "POST",
            async: false,
            url: "ajax_image_upload.php",
            data: ({str : url}),
            success: function(response){
                op = response;
            }
        });
        return op;
    }

    function showTab(param)
    {
        $("ul.tabs li a").removeClass("selected"); //Remove any "active" class
        $("li#"+param).find("a").addClass("selected"); //Add "active" class to selected tab
        $(".editor_property_content").hide(); //Hide all tab content
        var activeTab = $("li#"+param).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
        $(activeTab).fadeIn(); //Fade in the active content
        return false;
    }

    function deleteText()
    {
        $("div#"+$("input#sel_txt_div_id").val()).remove();
        $("#txt_delete_btn").hide();
        $("#txt_done_btn").hide();
        $("#txt_apply_btn").unbind('click');
        $("#txt_apply_btn").click(function(){updateText();});

        $("textarea#img_text").attr('value','');
        $("#text_font").attr('selectedIndex', 0);
        $("#text_size").attr('selectedIndex', 0);
        $("#text_color").val("000000");
    }

    function editText()
    {
        var isImgSelected = $('div#img_parent').children('img').length;
        if(isImgSelected == 0){
            alert('<?php echo VAL_IMAGE_SELECT;?>');
        }
        else{
            if($.trim($("textarea#img_text").attr('value')) != ''){
                var txt = $("textarea#img_text").attr('value').replace(/\n/g,'<br>');
                var fnt = $("#text_font :selected").val();
                var wght = $("#text_size :selected").val();
                var fntclr = $("#text_color").val();

                var div_id = $("input#sel_txt_div_id").val();
                var labid = div_id.split("_");

                $("label#lab_"+labid[1]).attr('fontclr', fntclr);
                $("label#lab_"+labid[1]).attr('fontsize', wght)
                $("label#lab_"+labid[1]).attr('txt', txt)
                $("label#lab_"+labid[1]).attr('fonttyp', fnt)

                $("label#lab_"+labid[1]).html("<img src='ajax_image_upload.php?textgen=1&msg="+urlEncode(txt)+"&size="+wght+"&fore="+fntclr+"&font="+fnt+"&randnum="+Math.floor(Math.random()*10001)+"' />");
            }
        }
    }

    function resetText()
    {
        $("textarea#img_text").attr('value','');
        $("#text_font").attr('selectedIndex', 0);
        $("#text_size").attr('selectedIndex', 0);
        $("#text_color").val("000000");

        $("#txt_apply_btn").unbind('click');
        $("#txt_done_btn").hide();
        $("#txt_delete_btn").hide();
        $("#txt_apply_btn").click(function(){updateText();});
    }

    function getPos(){
        var isImgSelected = $('div#img_parent').children('img').length; 
        if(isImgSelected == 0){
            alert('<?php echo VAL_IMAGE_SELECT;?>');
        }
        else{
            //--------------Reset initial values-------------//
            $("input#hid_coords").val('');

            //--------------Design offset-------------//
            //var offset_left = 530.5;
            //var offset_top = 41;

            var offset_left = 0;
            var offset_top = 0;

            var curr_text;
            $(".draggable").each(function(i,j){
                var pos = $(j).position(); 
                var left = pos.left - offset_left; 
                var top = pos.top - offset_top; 

                curr_text = $("input#hid_coords").val();
                curr_text += "#"+top+"|"+left+"|"+$(j).children("label").attr('txt')+"|"+$(j).children("label").attr('fontclr')+"|"+$(j).children("label").attr('fontsize')+"|"+$(j).children("label").attr('fonttyp');
                $("input#hid_coords").val(curr_text);
            });

            $("input#flip_count").val(flip_count);
            $("input#flop_count").val(flop_count);
            $("form[name=imageEditor]").submit();
        }
    }

    function confirmAction()
    {
        if($(".draggable").length != 0 || userAction != 0){
            if(confirm("<?php echo VAL_IMAGE_SAVE;?>")){
                getPos();
            }
            else{
                if($("#img_name").val() != ''){
                    loadImage($("#img_name").val(), 0);
                }
                return true;
            }
        }
    }
</script>
<link rel="stylesheet" href="style/editor.css" />
<?php $linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
        FOOTER_GALLERY_MANAGER =>'gallerymanager.php');
echo getBreadCrumb($linkArray);?>

<h2><?php echo FOOTER_GALLERY_MANAGER; ?></h2>
<span class="msgdescription"><?php echo GALLERY_DESCRIPTION ;?> </span>
<div id="jQsuccessMsgDiv" style="padding: 0 0 0 20px; color: green;"></div>
<form name="imageEditor" method="post" action="ajax_image_upload.php" >
    <div style="width:900px; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px;">

        <div class="editor_container">
            <div class="editor_left">

                <div class="editor_left_box">
                    <div class="top"></div>
                    <div class="cnt">
                        <div class="editor_left_head"><?php echo SELECT_IMAGE ;?></div>
                        <div class="comm_div">
                            <div class="thumb_container">
                                <?php
                                $images = getthumbs();
                                if(empty($images)) {
                                    echo "No Images Found.";
                                }
                                else {
                                    foreach($images as $imagePath) {
                                        ?>
                                <label onClick='loadImage(this, 1)'><img src="<?php echo $imagePath; ?>"></label>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>

                        <div class="editor_left_head"><?php echo UPLOAD_IMAGE ;?></div>
                        <div class="comm_div">
                            <div class="left" id="upload_div">
                                <input name="user_image" id="user_image" type="file">
                            </div>
                            <div id="msg_div" style="display:none"><?php echo UPLOADING ;?></div>
                            <div class="clear"></div>
                        </div>

                    </div>
                    <div class="btm"></div>
                </div>

                <div class="editor_left_box">
                    <div class="editor_tab_container">
                        <ul class="tabs">
                            <li id="imgli"><a href="#tab1" class="selected" id="imglink"><?php echo IMAGE ;?></a></li>
                            <li id="txtli"><a href="#tab2" id="txtlink"><?php echo TEXT ;?></a></li>
                        </ul>
                    </div>
                    <div id="tab1" class="editor_property_content">

                        <div id="default_img_handles">
                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo ROTATE ;?></h4>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<img src="images/editor/btn_rotate.jpg">
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;<input type="text" class="editor_input" name="rotate_angle" id="rotate_angle" size="5" value="0" onkeyup="updateImage()">
                                    <input type="hidden" name="brightness_value" id="brightness_value" value="0">
                                    <input type="hidden" name="contrast_value" id="contrast_value" value="0">
                                    <input name="img_name" id="img_name" type="hidden">
                                    <input name="x1" id="x1" class="hiddenvars" type="hidden">
                                    <input name="x2" id="x2" class="hiddenvars" type="hidden">
                                    <input name="y1" id="y1" class="hiddenvars" type="hidden">
                                    <input name="y2" id="y2" class="hiddenvars" type="hidden">
                                </div>
                                <div class="left top_padding">
                                    &nbsp;&nbsp;<?php echo DEGREES ;?>
                                </div>


                                <div class="clear"></div>
                            </div>


                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo BRIGHTNESS ;?></h4>
                                </div>

                                <div id="ui_slider_brightness" style="width:170px; float: right"></div>

                                <div class="clear"></div>
                            </div>

                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo CONTRAST ;?></h4>
                                </div>

                                <div id="ui_slider_contast" style="width:170px; float: right"></div>

                                <div class="clear"></div>
                            </div>


                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo WIDTH ;?></h4>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;<input type="text" class="editor_input" id="image_width" name="image_width" size="4" onkeyup="updateImage()">
                                </div>
                                <div class="left top_padding">
                                    <h4>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo HEIGHT ;?></h4>
                                </div>

                                <div class="left">
                                    &nbsp;&nbsp;<input type="text" class="editor_input" id="image_height" name="image_height" size="4" onkeyup="updateImage()">
                                </div>


                                <div class="clear"></div>
                            </div>


                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo CROP ;?></h4>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" id="crop_lnk"><img src="images/editor/btn_crop.jpg"></a>
                                </div>

                                <div class="left left_padding">
                                    <h4><?php echo FLIP ;?></h4>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="flipImage()"><img src="images/editor/btn_vflip.jpg"></a>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;<a href="javascript:void(0)" onclick="flopImage()"><img src="images/editor/btn_hflip.jpg"></a>
                                </div>



                                <div class="clear"></div>
                            </div>

                            <div class="editor_property_row">
                                <div class="left top_padding">
                                    <h4><?php echo EFFECTS ;?></h4>
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;<select class="editor_input" name="image_effects" id="image_effects" onchange="updateImage()" style="width:170px; ">
                                        <option value="NA"><?php echo SELECT ;?></option>
                                        <option value="negative"><?php echo NEGATIVE ;?></option>
                                        <option value="greyscale"><?php echo GREYSCALE ;?></option>
                                        <option value="smooth"><?php echo SMOOTH ;?></option>
                                        <option value="blur"><?php echo BLUR ;?></option>
                                    </select>
                                </div>





                                <div class="clear"></div>
                            </div>

                        </div>

                        <div id="crop_img_handles" style="display:none">
                            <div class="editor_left_head"><?php echo CROP_IMAGE ;?></div>
                            <label><?php echo CLICK ;?> <b><?php echo APPLY ;?></b> <?php echo SAVING_IMAGE ;?></label>
                            <br/>
                            <label><?php echo CLICK ;?> <b><?php echo CANCEL ;?></b> <?php echo RESET_SELECTION ;?></label>
                            <div style="text-align:center; padding-top: 10px">
                                <a href="javascript:void(0)" onclick="saveCropArea()"><img src="images/editor/btn_apply.png"></a>
                                &nbsp;
                                <a href="javascript:void(0)" onclick="resetSelectionArea()"><img src="images/editor/btn_cancel.png"></a>
                            </div>
                        </div>

                    </div>

                    <!-- <div class="comm_div" align="center">

                      <a href="javascript:void(0)" onclick="updateImage()"><img src="images/editor/btn_apply.png"></a>
							</div> -->


                    <!-- from this portion comes for the text options-->


                    <div id="tab2" class="editor_property_content">
                        <div class="editor_property_row">
                            <div class="left left_item">
                                <h4><?php echo TEXT ;?></h4>
                            </div>
                            <div class="left">
                                <textarea rows="2"  style="width:150px; " name="img_text" id="img_text" class="editor_input"></textarea>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="editor_property_row">
                            <div class="editor_comm_div">
                                <div class="left left_item">
                                    <h4><?php echo FONT ;?></h4>
                                </div>
                                <div class="left">
                                    <select class="editor_input" name="text_font" id="text_font" style="width:170px; ">
                                        <?php
                                        $font_list = getfonts();
                                        foreach($font_list as $font) {
                                            ?>
                                        <option value="<?php echo $font['val']; ?>"><?php echo $font['name']; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="clear"></div>
                            </div>

                            <div class="editor_comm_div">

                                <div class="left left_item">
                                    <h4><?php echo SIZE ;?></h4>
                                </div>

                                <div class="left">
                                    <select name="text_size" id="text_size" class="editor_input" style="width:60px; ">
                                        <option value="16">16</option>
                                        <option value="20">20</option>
                                        <option value="24">24</option>
                                        <option value="28">28</option>
                                        <option value="28">32</option>
                                    </select>
                                </div>
                                <!-- <div class="left top_padding" >
								&nbsp;&nbsp;<input name="" type="checkbox" value="">
								</div>
								<div class="left">
                                <img src="images/editor/italic.jpg">
								</div>
								<div class="left top_padding">
                                <input name="" type="checkbox" value="">
								</div>
								<div class="left">
                                <img src="images/editor/bold.jpg">
								</div> -->

                                <div class="clear"></div>
                            </div>

                            <div class="editor_comm_div">
                                <div class="left left_item">
                                    <h4><?php echo COLOR ;?></h4>
                                </div>
                                <div class="left">
                                    <input type="text" name="text_color" id="text_color" class="editor_input" value="000000" size="10">
                                    <input type="hidden" id="sel_txt_div_id" name="sel_txt_label_id" />
                                    <input type="hidden" id="hid_coords" name="hid_coords" />
                                    <input type="hidden" id="flip_count" name="flip_count" />
                                    <input type="hidden" id="flop_count" name="flop_count" />
                                </div>
                                <div class="left">
                                    &nbsp;&nbsp;<a><img src="images/editor/color_picker.jpg"></a>
                                </div>
                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>


                        <!-- <div class="editor_property_row">

								<div class="left left_item">
								<h4>Rotate</h4>
								</div>
								<div class="left">
                        <input type="text" class="editor_input" size="5">
								</div>
								<div class="left top_padding">
								&nbsp;&nbsp;Degrees
								</div>

								<div class="clear"></div>
								</div> -->

                        <div class="comm_div" align="center">
                            <a href="javascript:void(0)" id="txt_apply_btn"><img src="images/editor/btn_apply.png"></a>
                            &nbsp;
                            <a href="javascript:void(0)" id="txt_done_btn" style="display: none"><img src="images/editor/btn_done.png"></a>
                            &nbsp;
                            <a href="javascript:void(0)" id="txt_delete_btn" style="display: none"><img src="images/editor/btn_delete.png"></a>
                        </div>




                        <!-- text options ends-->





                    </div>
                    <div class="btm"></div>
                </div>


            </div>
            <div class="editor_right">
                <div class="editor_imagearea">
                    <div id="msg_loader" style="display:none; text-align: center; margin: 30px;"><?php echo LOADING ;?></div>
                    <div class="editor_imgbox">
                        <div id="img_holder_div" >

                        </div>
                    </div>
                </div>

                <div class="editor_bottom">
                    <div class="left top_padding"><input name="replace_or_overwrite" type="radio" value="0">&nbsp;&nbsp;<label><?php echo REPLACE_FILE ;?></label></div>
                    <div class="left left_padding"><input name="replace_or_overwrite" checked type="radio" value="1">&nbsp;&nbsp;<label><?php echo SAVE_NEWFILE ;?></label></div>
                    <div class="left left_padding">&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" id="image_save"><img src="images/editor/btn_save.png"></a></div>
                    <div class="clear"></div>
                </div>

            </div>
            <div class="clear"></div>
        </div>


    </div>
</form>
<?php
include "includes/userfooter.php";
?>
<!-- <script type="text/javascript" src="https://getfirebug.com/firebug-lite-debug.js"></script>  -->