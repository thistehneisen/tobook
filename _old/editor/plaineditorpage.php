<?php   
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to geenerate the HTMl Editor         									                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";
include "../includes/config.php";

?>
<link rel="stylesheet" href="../style/colorbox.css" />
<script language="javascript" src="../js/jquery.js"></script>
<script language="javascript" src="../js/jquery.colorbox.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>ckeditor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>js/validations.js"></script>


<!--div >
<textarea id="editor1" name="editor1"><?php echo trim(htmlentities($panelHtml));?></textarea>
</div-->
<script type="text/javascript">
$(document).ready(function(){

    $(".iframe").colorbox({iframe:true, width:"50%", height:"60%"});
    /*
    CKEDITOR.replace( 'editor1', {
        toolbar :[
        ['Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList'],
        ['Font','FontSize', 'TextColor']]
    });

    CKEDITOR.config.width = 490;
    CKEDITOR.config.height = 150;
    //CKEDITOR.config.fullPage = true;
*/
});
</script>

<p><a class='iframe' href="edittemplate.php?panelid=122">Edit</a></p>
<!--a id="plaineditorpage.php" class="modal jqShowPopup" href="javascript:void(0);" title="Click to edit the site" >Edit</a-->